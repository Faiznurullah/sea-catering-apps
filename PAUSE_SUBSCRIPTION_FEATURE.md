# Fitur Pause Subscription - SEA Catering

## 📋 **Overview**
Fitur Pause Subscription memungkinkan pelanggan untuk menghentikan sementara subscription mereka dengan sistem perhitungan refund yang adil.

## 🔧 **Fitur yang Ditambahkan**

### **1. Database Schema**
```sql
ALTER TABLE subscriptions ADD COLUMN:
- pause_start_date DATE NULL
- pause_end_date DATE NULL  
- paused_days_total DECIMAL(8,2) DEFAULT 0
- refund_amount DECIMAL(12,2) DEFAULT 0
```

### **2. Status Subscription**
- **Active** → Subscription berjalan normal
- **Paused** → Subscription di-pause sementara
- **Inactive** → Subscription tidak aktif (berbeda dengan pause)
- **Cancelled** → Subscription dibatalkan permanen

### **3. Sistem Perhitungan Refund**

#### **Formula Refund:**
```php
Daily Rate = Monthly Total ÷ 30 hari
Refund Amount = Daily Rate × Jumlah Hari Pause
Adjusted Payment = Monthly Total - Refund Amount
```

#### **Contoh Perhitungan:**
- **Subscription:** Protein Plan Rp 1.720.000/bulan
- **Pause Duration:** 7 hari
- **Daily Rate:** Rp 1.720.000 ÷ 30 = Rp 57.333/hari
- **Refund:** Rp 57.333 × 7 = Rp 401.333
- **Adjusted Payment:** Rp 1.720.000 - Rp 401.333 = Rp 1.318.667

## 🚀 **Cara Kerja Fitur**

### **1. Pause Subscription**
1. User klik tombol "Pause Subscription" pada subscription yang active
2. Modal popup muncul dengan form:
   - **Start Date:** Tanggal mulai pause (minimum besok)
   - **End Date:** Tanggal berakhir pause
   - **Reason:** Alasan pause (opsional)
3. Sistem menghitung:
   - Durasi pause dalam hari
   - Estimated refund amount
4. User konfirmasi pause
5. Status berubah menjadi "Paused"
6. Refund amount dicatat dan bisa digunakan sebagai credit

### **2. Resume Subscription**
1. User klik tombol "Resume Subscription" pada subscription yang paused
2. Konfirmasi resume
3. Status kembali menjadi "Active"
4. Pause dates di-reset

### **3. Informasi yang Ditampilkan**
- **Total Paused Days:** Akumulasi total hari yang pernah di-pause
- **Refund/Credit:** Total refund yang sudah diberikan
- **Adjusted Payment:** Pembayaran bulanan setelah dikurangi refund
- **Pause Period:** Tanggal periode pause yang sedang aktif
- **Remaining Days:** Sisa hari pause jika sedang dalam periode pause

## 💰 **Sistem Pembayaran dengan Pause**

### **Skenario 1: Pause di Tengah Bulan**
- User sudah bayar untuk bulan ini: Rp 1.720.000
- Pause 10 hari di tengah bulan
- Refund: Rp 573.333
- **Opsi pembayaran:**
  - Credit untuk bulan berikutnya
  - Refund langsung ke rekening
  - Perpanjang subscription

### **Skenario 2: Multiple Pause dalam Sebulan**
- Pause pertama: 5 hari → Refund Rp 286.667
- Pause kedua: 3 hari → Refund Rp 172.000
- **Total refund:** Rp 458.667
- **Adjusted payment:** Rp 1.261.333

### **Skenario 3: Pause Berkelanjutan**
- User pause subscription selama 1 bulan penuh
- **Refund:** Rp 1.720.000 (full month)
- **Adjusted payment:** Rp 0
- Subscription tetap aktif tapi tidak ada delivery

## 🎛️ **Admin Dashboard Features**

### **1. Pause Management**
- Approve/reject pause requests
- View pause history dan statistics
- Manage refund processing
- Monitor subscription health

### **2. Financial Reports**
- Total paused days per periode
- Refund amounts issued
- Impact pada revenue
- Customer retention analytics

## 🔄 **Workflow Process**

```
Active Subscription
       ↓
User Request Pause
       ↓
Select Pause Dates
       ↓
Calculate Refund
       ↓
Confirm Pause
       ↓
Status: Paused
       ↓
[During Pause Period]
       ↓
User Resume → Status: Active
   OR
Natural End → Status: Active
```

## 📊 **Benefits untuk Business**

### **1. Customer Retention**
- Reduce churn rate
- Flexible subscription management
- Better customer satisfaction

### **2. Financial Control**
- Fair refund calculation
- Transparent pricing
- Reduced payment disputes

### **3. Operational Efficiency**
- Automated pause/resume process
- Clear audit trail
- Simplified customer support

## 🛡️ **Validations & Rules**

### **1. Pause Restrictions**
- Minimum pause duration: 1 hari
- Maximum pause duration: 30 hari per request
- Start date: Minimum tomorrow
- End date: Must be after start date
- Only active subscriptions can be paused

### **2. Business Rules**
- Pause hanya berlaku untuk subscription yang sudah active
- Refund diberikan dalam bentuk credit kecuali request khusus
- Maximum 3 kali pause per bulan
- Pause tidak mempengaruhi subscription end date

## 🔮 **Future Enhancements**
- Auto-resume pada tanggal yang ditentukan
- Flexible refund options (cash/credit/extend)
- Pause templates untuk holiday seasons
- Integration dengan payment gateway untuk auto-refund
- Machine learning untuk predict pause patterns

Fitur ini memberikan flexibility maksimal kepada customer sambil menjaga kontrol keuangan yang baik! 🎉
