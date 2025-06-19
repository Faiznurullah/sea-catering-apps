 @extends('layouts.template')
 @section('title', 'CateringApp')
 @section('css')
     <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
     <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
     <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
     <!-- SweetAlert2 CSS -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
 @endsection
 @section('content')

     {{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

     <section class="auth-section">
         <div class="container">
             <div class="auth-container">
                 <div class="auth-form-container active">
                     <h2>Reset Password</h2>
                     <p class="auth-subtitle">Enter your new password below</p>


                     <form method="POST" class="auth-form" id="reset-password-form" action="{{ route('password.update') }}">
                         @csrf 

                         <input type="hidden" id="reset-token" name="token" value="{{ $token }}">

                         <div class="form-group">
                             <label for="reset-email">Email Address</label>
                             <input type="email" id="reset-email" name="email" value="{{ $email ?? old('email') }}" required readonly>
                             @error('email')
                                 <span class="error-message">{{ $message }}</span>
                             @enderror
                         </div>

                         <div class="form-group">
                             <label for="new-password">New Password</label>
                             <div class="password-input">
                                 <input type="password" id="new-password" name="password" required>
                                 <button type="button" class="toggle-password">👁️</button>
                             </div>
                             <small class="password-requirements">Password must be at least 8 characters and include
                                 uppercase, lowercase, number, and special character</small>
                             @error('password')
                                 <span class="error-message">{{ $message }}</span>
                             @enderror
                         </div>

                         <div class="form-group">
                             <label for="confirm-new-password">Confirm New Password</label>
                             <div class="password-input">
                                 <input type="password" id="confirm-new-password" name="password_confirmation" required>
                                 <button type="button" class="toggle-password">👁️</button>
                             </div>
                             @error('password_confirmation')
                                 <span class="error-message">{{ $message }}</span>
                             @enderror
                         </div>

                         <button type="submit" class="btn-primary">Reset Password</button>

                         <div class="form-footer">
                             <a href="{{ route('login') }}">Back to Login</a>
                         </div>

                     </form>
                 </div>
             </div>
         </div>
     </section>


 @endsection
 @section('javascript')
     <!-- SweetAlert2 JS -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="{{ asset('js/scripts.js') }}"></script>
     <script src="{{ asset('js/reset-password.js') }}"></script>
     
     <!-- Session Messages with SweetAlert -->
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // Check for success session
             @if(session('status'))
                 Swal.fire({
                     icon: 'success',
                     title: 'Success!',
                     text: '{{ session('status') }}',
                     showConfirmButton: true,
                     confirmButtonColor: '#28a745',
                     timer: 5000,
                     timerProgressBar: true
                 }).then((result) => {
                     if (result.isConfirmed || result.isDismissed) {
                         window.location.href = '{{ route('login') }}';
                     }
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
                     timerProgressBar: true
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
         });
     </script>
 @endsection
