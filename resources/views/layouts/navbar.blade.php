<nav class="navbar">
    <div class="container">
        <div class="logo">
            <a href="/">SEA Catering</a>
        </div>
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-menu">
            <li><a href="/">Beranda</a></li>
            <li><a href="/menu">Menu</a></li>
            <li><a href="/subscription">Subscription</a></li>
            <li><a href="/contact">Contact Us</a></li>
               @auth 
                @if (Auth::user()->role === 'user')
                    <li class="user-menu">
                        <a href="#" class="user-menu-trigger">{{ Auth::user()->name }} ▼</a>
                        <ul class="user-dropdown">
                            <li><a href="/home">{{ __('profile.home') }}</a></li>
                            <li><a href="/profile">{{ __('profile.profile') }}</a></li>
                              <li><a href="/password/reset">Reset Password</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('profile.logout') }}
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                  <li class="user-menu">
                        <a href="#" class="user-menu-trigger">{{ Auth::user()->name }} ▼</a>
                        <ul class="user-dropdown">
                            <li><a href="/dashboard">{{ __('profile.dashboard') }}</a></li>
                            <li><a href="/profile">{{ __('profile.profile') }}</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('profile.logout') }}
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
                <li>@include('components.language-switcher')</li>
            @else
                {{-- User is not logged in - show login button --}}
                <li><a href="{{ route('login') }}" class="btn-login">{{ __('profile.login') }}</a></li>
                <li>@include('components.language-switcher')</li>
            @endauth
        </ul>
    </div>
</nav>
