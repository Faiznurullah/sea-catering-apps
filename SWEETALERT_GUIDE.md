# SweetAlert Integration untuk Laravel

## Cara Penggunaan SweetAlert di Aplikasi Catering

### 1. Include SweetAlert di Blade Template

#### Opsi 1: Menggunakan Partial (Recommended)
```blade
@include('partials.sweetalert')
```

#### Opsi 2: Manual Include
```blade
<!-- Di section CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Di section JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### 2. Session Flash Messages di Controller

#### Success Message
```php
return redirect()->back()->with('success', 'Data berhasil disimpan!');
```

#### Error Message
```php
return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data!');
```

#### Warning Message
```php
return redirect()->back()->with('warning', 'Perhatian! Data akan diubah.');
```

#### Info Message
```php
return redirect()->back()->with('info', 'Informasi tambahan untuk user.');
```

### 3. Contoh Implementasi di Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
            ]);

            // Simpan data
            // Model::create($request->all());

            return redirect()->back()->with('success', 'Data berhasil disimpan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors akan ditangani otomatis oleh SweetAlert
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Model::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data!');
        }
    }
}
```

### 4. Fungsi JavaScript yang Tersedia

#### Konfirmasi Delete
```html
<a href="#" onclick="confirmDelete('/delete/1', 'Hapus Data?', 'Data tidak dapat dikembalikan!')" class="btn btn-danger">
    Delete
</a>
```

#### Konfirmasi Custom Action
```html
<a href="#" onclick="confirmAction('/approve/1', 'Setujui Data?', 'Data akan disetujui')" class="btn btn-success">
    Approve
</a>
```

#### Toast Notification
```javascript
// Success toast
showToast('success', 'Data berhasil disimpan!');

// Error toast
showToast('error', 'Terjadi kesalahan!');

// Warning toast
showToast('warning', 'Perhatian!');

// Info toast
showToast('info', 'Informasi');
```

### 5. Contoh Custom SweetAlert

```javascript
// Custom alert dengan konfigurasi khusus
Swal.fire({
    title: 'Konfirmasi Pembayaran',
    text: 'Anda akan melakukan pembayaran sebesar Rp 100.000',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonColor: '#dc3545',
    confirmButtonText: 'Bayar Sekarang',
    cancelButtonText: 'Batal'
}).then((result) => {
    if (result.isConfirmed) {
        // Proses pembayaran
        window.location.href = '/payment/process';
    }
});
```

### 6. Styling dan Customization

#### Mengubah warna default
```javascript
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: 'Operasi berhasil',
    confirmButtonColor: '#007bff', // Custom color
    background: '#f8f9fa', // Custom background
    color: '#495057' // Custom text color
});
```

#### Toast dengan posisi custom
```javascript
const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end', // top-end, top-start, bottom-start, etc.
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

Toast.fire({
    icon: 'success',
    title: 'Berhasil disimpan'
});
```

### 7. Error Handling untuk AJAX

```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
    url: '/api/data',
    type: 'POST',
    data: {
        name: 'John Doe',
        email: 'john@example.com'
    },
    success: function(response) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.message
        });
    },
    error: function(xhr) {
        let errorMessage = 'Terjadi kesalahan!';
        
        if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            errorMessage = Object.values(errors).flat().join('<br>');
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: errorMessage
        });
    }
});
```

### 8. Integrasi dengan Form

```html
<form id="myForm" method="POST" action="/submit">
    @csrf
    <!-- form fields -->
    <button type="submit">Submit</button>
</form>

<script>
document.getElementById('myForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah data sudah benar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Submit',
        cancelButtonText: 'Cek Lagi'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>
```

### Notes:
- Pastikan SweetAlert2 sudah terload sebelum script custom
- Gunakan `@include('partials.sweetalert')` di layout utama untuk konsistensi
- Validation errors akan otomatis ditampilkan dalam format list
- Semua session flash message akan otomatis ditampilkan saat halaman dimuat
