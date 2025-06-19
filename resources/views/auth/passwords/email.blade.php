 @extends('layouts.template')
 @section('title', 'CateringApp')
 @section('css')
     <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
     <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
     <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
 @endsection
 @section('content')

     {{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
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
                     <p class="auth-subtitle">Enter your email address and we'll send you a password reset link</p>

                      @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                     <!-- Success message (hidden by default) -->
                     <div class="alert alert-success" id="status-message" style="display: none;">
                         We have emailed your password reset link!
                     </div>


                     <form class="auth-form" method="POST"
                         action="{{ route('password.email') }}">
                         @csrf

                         <div class="form-group">
                             <label for="forgot-email">Email Address</label>
                             <input type="email" id="forgot-email" name="email" required>
                             <div class="error-message" id="email-error"></div>
                         </div>

                         <button type="submit" class="btn-primary">Send Password Reset Link</button>

                         <div class="form-footer">
                             <a href="/login">Back to Login</a>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </section>


 @endsection
 @section('javascript')
     <script src="{{ asset('js/scripts.js') }}"></script> 
 @endsection
