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
                <div class="card-header">{{ __('Confirm Password') }}</div>

                <div class="card-body">
                    {{ __('Please confirm your password before continuing.') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
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
                     <h2>Confirm Password</h2>
                     <p class="auth-subtitle">Please confirm your password before continuing</p>


                     <form id="confirm-password-form" class="auth-form" method="POST"
                         action="{{ route('password.confirm') }}">
                         @csrf

                         <div class="form-group">
                             <label for="current-password">Current Password</label>
                             <div class="password-input">
                                 <input type="password" id="current-password" name="password" required>
                                 <button type="button" class="toggle-password">👁️</button>
                             </div>
                             <div class="error-message" id="password-error"></div>
                         </div>

                         <div class="form-actions">
                             <button type="submit" class="btn-primary">Confirm Password</button>
                             <a href="" class="btn-link">Forgot Your Password?</a>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </section>


 @endsection
 @section('javascript')
     <script src="{{ asset('js/scripts.js') }}"></script>
     <script src="{{ asset('js/forgot-password.js') }}"></script>
 @endsection
