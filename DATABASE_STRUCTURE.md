# Database Structure untuk Sistem Subscription SEA Catering

## Overview
Struktur database ini dirancang untuk mendukung sistem subscription catering yang memungkinkan:
1. Pelanggan membuat subscription dengan berbagai pilihan meal plan
2. Admin mengelola subscription dan pesanan pelanggan
3. Sistem menghitung harga otomatis berdasarkan formula

## Struktur Tabel

### 1. meal_plans
Tabel ini menyimpan jenis-jenis meal plan yang tersedia.

```sql
CREATE TABLE meal_plans (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,                    -- Diet Plan, Protein Plan, Royal Plan
    description TEXT NULL,                         -- Deskripsi plan
    price_per_meal DECIMAL(10,2) NOT NULL,        -- Harga per meal
    is_active BOOLEAN DEFAULT TRUE,               -- Status aktif/non-aktif
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Data Default:**
- Diet Plan: Rp 30.000 per meal
- Protein Plan: Rp 40.000 per meal  
- Royal Plan: Rp 60.000 per meal

### 2. subscriptions
Tabel utama untuk menyimpan data subscription pelanggan.

```sql
CREATE TABLE subscriptions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,                    -- Nama lengkap pelanggan
    phone VARCHAR(255) NOT NULL,                   -- Nomor telepon aktif
    meal_plan_id BIGINT UNSIGNED NOT NULL,         -- FK ke meal_plans
    allergies TEXT NULL,                           -- Alergi/pantangan makanan
    total_price DECIMAL(12,2) NOT NULL,           -- Total harga subscription
    status ENUM('active','inactive','pending','cancelled') DEFAULT 'pending',
    start_date DATE NULL,                          -- Tanggal mulai subscription
    end_date DATE NULL,                            -- Tanggal berakhir subscription
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (meal_plan_id) REFERENCES meal_plans(id) ON DELETE CASCADE
);
```

### 3. subscription_meals
Tabel untuk menyimpan jenis meal yang dipilih pelanggan (many-to-many relationship).

```sql
CREATE TABLE subscription_meals (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    subscription_id BIGINT UNSIGNED NOT NULL,      -- FK ke subscriptions
    meal_type ENUM('breakfast','lunch','dinner') NOT NULL, -- Jenis meal
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_subscription_meal (subscription_id, meal_type)
);
```

### 4. subscription_delivery_days
Tabel untuk menyimpan hari pengiriman yang dipilih pelanggan (many-to-many relationship).

```sql
CREATE TABLE subscription_delivery_days (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    subscription_id BIGINT UNSIGNED NOT NULL,      -- FK ke subscriptions
    day_of_week ENUM('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_subscription_day (subscription_id, day_of_week)
);
```

## Model Relationships

### MealPlan Model
```php
// Has many subscriptions
public function subscriptions()
{
    return $this->hasMany(Subscription::class);
}
```

### Subscription Model
```php
// Belongs to meal plan
public function mealPlan()
{
    return $this->belongsTo(MealPlan::class);
}

// Has many meal types
public function subscriptionMeals()
{
    return $this->hasMany(SubscriptionMeal::class);
}

// Has many delivery days
public function deliveryDays()
{
    return $this->hasMany(SubscriptionDeliveryDay::class);
}
```

### SubscriptionMeal Model
```php
// Belongs to subscription
public function subscription()
{
    return $this->belongsTo(Subscription::class);
}
```

### SubscriptionDeliveryDay Model
```php
// Belongs to subscription
public function subscription()
{
    return $this->belongsTo(Subscription::class);
}
```

## Formula Perhitungan Harga

```php
Total Price = (Plan Price) × (Number of Meal Types) × (Number of Delivery Days) × 4.3
```

**Contoh Perhitungan:**
- Plan: Protein Plan (Rp 40.000)
- Meal Types: Breakfast + Dinner (2 meal types)
- Delivery Days: Monday to Friday (5 days)
- Total: Rp 40.000 × 2 × 5 × 4.3 = Rp 1.720.000

## Implementasi dalam Model

```php
// Method dalam Subscription Model
public static function calculateTotalPrice($planPrice, $mealTypesCount, $deliveryDaysCount)
{
    return $planPrice * $mealTypesCount * $deliveryDaysCount * 4.3;
}
```

## Routing Structure

```php
// Form subscription
GET /subscription -> SubscriptionController@index
POST /subscription -> SubscriptionController@store

// Success page
GET /subscription/success -> SubscriptionController@success

// Management (untuk route /home)
GET /home/subscriptions -> SubscriptionController@manage

// Detail subscription
GET /subscription/{id} -> SubscriptionController@show

// Update status
PATCH /subscription/{id}/status -> SubscriptionController@updateStatus
```

## Fitur Utama

### 1. Form Subscription
- Input nama lengkap dan nomor telepon
- Pilihan meal plan (Diet/Protein/Royal)
- Multiple selection untuk meal types (Breakfast/Lunch/Dinner)
- Multiple selection untuk delivery days (Monday-Sunday)
- Input alergi/pantangan makanan
- Real-time price calculation

### 2. Management Dashboard
- List semua subscription dengan pagination
- Filter berdasarkan status
- Update status subscription (pending → active, active → inactive, dll)
- View detail subscription

### 3. Status Subscription
- **pending**: Subscription baru menunggu approval
- **active**: Subscription aktif dan berjalan
- **inactive**: Subscription di-pause sementara
- **cancelled**: Subscription dibatalkan

## Security & Validation

### Input Validation
```php
$validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255',
    'phone' => 'required|string|max:20',
    'meal_plan_id' => 'required|exists:meal_plans,id',
    'meal_types' => 'required|array|min:1',
    'meal_types.*' => 'in:breakfast,lunch,dinner',
    'delivery_days' => 'required|array|min:1',
    'delivery_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
    'allergies' => 'nullable|string'
]);
```

### Database Transaction
Menggunakan database transaction untuk memastikan konsistensi data saat membuat subscription dengan multiple tables.

```php
DB::beginTransaction();
try {
    // Create subscription
    // Create subscription meals  
    // Create delivery days
    DB::commit();
} catch (\Exception $e) {
    DB::rollback();
}
```

## Migration Commands

Untuk membuat dan menjalankan migration:

```bash
# Membuat migration dan model
php artisan make:model MealPlan -m
php artisan make:model Subscription -m
php artisan make:model SubscriptionMeal -m
php artisan make:model SubscriptionDeliveryDay -m

# Menjalankan migration
php artisan migrate

# Membuat seeder
php artisan make:seeder MealPlanSeeder

# Menjalankan seeder
php artisan db:seed --class=MealPlanSeeder
```

Struktur database ini mendukung semua requirement dari challenge Level 3 dan siap untuk diintegrasikan dengan sistem yang sudah ada.
