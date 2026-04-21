<x-guest-layout>
    <div class="auth-facebook-head">
        <p class="auth-panel-kicker">{{ __('Account Access') }}</p>
        <h1 class="auth-panel-title">{{ __('Log in') }}</h1>
        <p class="auth-panel-text">{{ __('Use your VIREON account to keep shopping, checkout faster, and manage your cart.') }}</p>
    </div>

    <x-auth-session-status class="auth-status mb-4" :status="session('status')" />

    <div class="auth-facebook-meta">
        <span>{{ __('Secure sign in') }}</span>
        <a class="auth-facebook-link" href="{{ route('register') }}">{{ __('Need an account? Register') }}</a>
    </div>

    <form method="POST" action="{{ route('login') }}" class="auth-facebook-form">
        @csrf

        <div class="auth-facebook-field">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <div class="auth-facebook-field">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <div class="auth-meta-row">
            <label for="remember_me" class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="auth-facebook-cta">
            {{ __('Log in') }}
        </x-primary-button>
    </form>

    <div class="auth-facebook-divider"></div>

    <div class="auth-facebook-foot">
        <span>{{ __('New to VIREON?') }}</span>
        <a class="auth-facebook-link" href="{{ route('register') }}">
            {{ __('Create an account') }}
        </a>
    </div>
</x-guest-layout>