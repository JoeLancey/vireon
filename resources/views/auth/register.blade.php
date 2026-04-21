<x-guest-layout>
    <div class="auth-facebook-head">
        <p class="auth-panel-kicker">{{ __('Create Account') }}</p>
        <h1 class="auth-panel-title">{{ __('Register') }}</h1>
        <p class="auth-panel-text">{{ __('Create your account to save items, keep your cart ready, and check out faster.') }}</p>
    </div>

    <div class="auth-facebook-meta">
        <span>{{ __('One account for shopping and checkout') }}</span>
        <a class="auth-facebook-link" href="{{ route('login') }}">{{ __('Already have one? Log in') }}</a>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-facebook-form">
        @csrf

        <div class="auth-facebook-field">
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <div class="auth-facebook-field">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <div class="auth-facebook-field">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <div class="auth-facebook-field">
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <x-primary-button class="auth-facebook-cta">
            {{ __('Register') }}
        </x-primary-button>
    </form>

    <div class="auth-facebook-divider"></div>

    <div class="auth-facebook-foot">
        <span>{{ __('Already have an account?') }}</span>
        <a class="auth-facebook-link" href="{{ route('login') }}">
            {{ __('Sign in instead') }}
        </a>
    </div>
</x-guest-layout>