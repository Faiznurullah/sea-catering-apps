<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Session Messages with SweetAlert -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check for success session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                confirmButtonColor: '#28a745',
                timer: 5000,
                timerProgressBar: true,
                toast: false,
                position: 'center'
            });
        @endif

        // Check for error/failed session
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonColor: '#dc3545',
                timer: 5000,
                timerProgressBar: true,
                toast: false,
                position: 'center'
            });
        @endif

        // Check for validation errors
        @if($errors->any())
            let errorMessages = [];
            @foreach($errors->all() as $error)
                errorMessages.push('{{ $error }}');
            @endforeach
            
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">' + 
                      errorMessages.map(msg => '<li>' + msg + '</li>').join('') + 
                      '</ul>',
                showConfirmButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        @endif

        // Check for warning session
        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: '{{ session('warning') }}',
                showConfirmButton: true,
                confirmButtonColor: '#ffc107',
                timer: 5000,
                timerProgressBar: true,
                toast: false,
                position: 'center'
            });
        @endif

        // Check for info session
        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Information',
                text: '{{ session('info') }}',
                showConfirmButton: true,
                confirmButtonColor: '#17a2b8',
                timer: 5000,
                timerProgressBar: true,
                toast: false,
                position: 'center'
            });
        @endif
    });

    // Function untuk konfirmasi delete dengan SweetAlert
    function confirmDelete(url, title = 'Are you sure?', text = 'You won\'t be able to revert this!') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    // Function untuk konfirmasi custom action
    function confirmAction(url, title = 'Are you sure?', text = '', icon = 'question') {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    // Function untuk toast notification
    function showToast(type, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }
</script>
