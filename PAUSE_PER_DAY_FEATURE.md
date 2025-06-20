# Fitur Pause Subscription Per Day - Enhancement

## 📋 **Overview**
Enhancement dari fitur pause subscription yang memungkinkan pelanggan untuk menghentikan sementara subscription mereka berdasarkan hari-hari tertentu (tidak harus rentang tanggal berurutan), dengan sistem perhitungan refund yang akurat per hari.

## 🆕 **Fitur Baru yang Ditambahkan**

### **1. Database Schema Enhancement**
```sql
CREATE TABLE subscription_paused_days (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    subscription_id BIGINT NOT NULL,
    paused_date DATE NOT NULL,
    daily_rate DECIMAL(10,2) NOT NULL,
    refund_amount DECIMAL(10,2) NOT NULL,
    reason VARCHAR(255) NULL,
    type VARCHAR(50) DEFAULT 'per_day',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE,
    INDEX (subscription_id, paused_date),
    UNIQUE (subscription_id, paused_date)
);
```

### **2. Fitur Pause Per Day**

#### **Cara Kerja:**
1. User klik tombol **"Pause Per Day"** pada subscription yang active
2. Modal popup menampilkan:
   - Pemilih bulan untuk menentukan periode
   - Calendar grid dengan hari-hari yang bisa dipilih
   - Visual indicator untuk:
     - ✅ **Delivery Days** (hijau) - bisa dipilih
     - ⏸️ **Already Paused** (kuning) - tidak bisa dipilih
     - 🚫 **Non-delivery Days** (abu-abu) - tidak bisa dipilih
     - 📅 **Past Dates** (abu-abu gelap) - tidak bisa dipilih
3. User memilih hari-hari yang ingin di-pause
4. Sistem menghitung refund per hari secara real-time
5. User konfirmasi pause

#### **Formula Refund Per Day:**
```php
Daily Rate = (Monthly Total ÷ 30) atau berdasarkan actual delivery schedule
Refund Per Day = Daily Rate × 1 hari
Total Refund = Sum of all selected days refund
```

#### **Contoh Perhitungan:**
- **Subscription:** Protein Plan Rp 1.720.000/bulan
- **Selected Days:** 3 hari (Senin 1 Jan, Rabu 3 Jan, Jumat 5 Jan)
- **Daily Rate:** Rp 57.333/hari
- **Total Refund:** Rp 57.333 × 3 = Rp 172.000
- **Adjusted Payment:** Rp 1.720.000 - Rp 172.000 = Rp 1.548.000

### **3. Enhanced Pause History**

#### **Individual Paused Days Display:**
- Menampilkan detail setiap hari yang di-pause
- Informasi tanggal, hari, dan refund amount per hari
- Alasan pause (jika diisi)
- Total akumulasi dari semua pause (range + per day)

### **4. Validasi Keamanan**

#### **Frontend Validations:**
- Hanya delivery days yang bisa dipilih
- Tidak bisa memilih tanggal lampau
- Tidak bisa memilih hari yang sudah di-pause
- Minimal 1 hari harus dipilih

#### **Backend Validations:**
- Validasi subscription status (harus active)
- Validasi tanggal (harus masa depan)
- Validasi delivery days (harus sesuai subscription)
- Validasi duplikasi (cek database)

## 🛠 **Implementation Details**

### **1. New Model: SubscriptionPausedDay**
```php
class SubscriptionPausedDay extends Model
{
    protected $fillable = [
        'subscription_id', 'paused_date', 'daily_rate', 
        'refund_amount', 'reason', 'type'
    ];
    
    public function subscription() {
        return $this->belongsTo(Subscription::class);
    }
}
```

### **2. Enhanced Subscription Model**
```php
// New relationship
public function pausedDays() {
    return $this->hasMany(SubscriptionPausedDay::class);
}
```

### **3. New Controller Methods**
- `pausePerDay(Request $request, $id)` - Handle pause per day
- `getPausedDays($id)` - Get already paused days for calendar

### **4. New Routes**
```php
Route::post('/subscription/{id}/pause-per-day', [SubscriptionController::class, 'pausePerDay']);
Route::get('/subscription/{id}/paused-days', [SubscriptionController::class, 'getPausedDays']);
```

### **5. Enhanced Frontend Components**
- **Calendar Grid Component** - Interactive monthly calendar
- **Real-time Calculation** - Live refund calculation
- **Visual Indicators** - Color-coded day status
- **Responsive Design** - Mobile-friendly calendar

## 🎨 **User Interface**

### **Modal Components:**
1. **Month Selector** - Memilih bulan untuk di-pause
2. **Interactive Calendar** - Grid 7x6 dengan visual indicators
3. **Selection Summary** - Real-time calculation dan selected days
4. **Reason Field** - Optional reason untuk pause

### **Color Coding:**
- 🟢 **#e8f5e8** - Delivery days (selectable)
- 🔵 **#007bff** - Selected days
- 🟡 **#ffc107** - Already paused days
- ⚪ **#f8f9fa** - Non-delivery days
- ⚫ **#e9ecef** - Past dates

### **Legend Display:**
- Visual legend menjelaskan makna setiap warna
- Responsive untuk mobile devices

## 📊 **Benefits**

### **1. Untuk Customer:**
- **Flexibility**: Pause hanya hari tertentu, tidak perlu full week/month
- **Cost Effective**: Refund akurat per hari, tidak overpay
- **Easy to Use**: Visual calendar interface yang intuitif
- **History Tracking**: Riwayat detail setiap pause

### **2. Untuk Business:**
- **Better Customer Retention**: Lebih fleksibel = customer lebih loyal
- **Accurate Billing**: Perhitungan refund yang precise
- **Data Insights**: Track pause patterns untuk business intelligence
- **Reduced Support**: Self-service yang mudah digunakan

## 🔧 **Technical Considerations**

### **1. Performance:**
- Index pada `(subscription_id, paused_date)` untuk query cepat
- AJAX loading untuk calendar data
- Minimal DOM manipulation

### **2. Data Integrity:**
- Foreign key constraints
- Unique constraint untuk prevent duplicate pause
- Transaction safety untuk atomic operations

### **3. Scalability:**
- Pagination untuk pause history jika data besar
- Caching untuk frequently accessed data
- Efficient database queries dengan proper indexing

## 🚀 **Usage Instructions**

### **For Users:**
1. Login ke account
2. Go to Home page → My Subscriptions
3. Pada subscription yang active, klik **"Pause Per Day"**
4. Pilih bulan di dropdown
5. Klik hari-hari yang ingin di-pause pada calendar
6. Review calculation dan klik **"Confirm Pause"**
7. Check Pause History untuk melihat detail

### **For Admins:**
- All pause activities tercatat di database
- View details di admin dashboard
- Monitor pause patterns untuk business insights

## 📝 **Future Enhancements**

1. **Bulk Pause**: Select multiple months sekaligus
2. **Recurring Pause**: Set pause pattern yang berulang
3. **Advanced Notifications**: Email/SMS untuk pause confirmations
4. **Reporting Dashboard**: Analytics untuk pause patterns
5. **Mobile App**: Dedicated mobile interface

---

**Version:** 1.0  
**Date:** June 20, 2025  
**Author:** Development Team
