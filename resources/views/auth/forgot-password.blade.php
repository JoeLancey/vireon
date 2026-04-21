<x-guest-layout>
    <div class="auth-trend-head">
        <p class="auth-trend-kicker">{{ __('Password Recovery') }}</p>
        <h1 class="auth-trend-title font-display">{{ __('Reset Access') }}</h1>
        <p class="auth-trend-subtitle">{{ __('Enter your email and we will send you a secure link so you can get back into your account.') }}</p>
    </div>

    <div class="auth-trend-status mb-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="auth-trend-status mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-trend-form">
        @csrf

        <div class="auth-trend-field">
            <x-input-label for="email" :value="__('Email')" class="auth-trend-label" />
            <x-text-input id="email" class="auth-trend-input block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <x-primary-button class="auth-trend-submit">
            {{ __('Email Password Reset Link') }}
        </x-primary-button>
    </form>

    <div class="auth-trend-divider"></div>

    <div class="auth-trend-foot">
        <a class="auth-trend-link" href="{{ route('login') }}">
            {{ __('Back to login') }}
        </a>
        <a class="auth-trend-link" href="{{ route('register') }}">
            {{ __('Create account') }}
        </a>
    </div>
</x-guest-layout>