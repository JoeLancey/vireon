<x-guest-layout>
    <div class="auth-trend-head">
        <p class="auth-trend-kicker">{{ __('Create a New Password') }}</p>
        <h1 class="auth-trend-title font-display">{{ __('Reset Password') }}</h1>
        <p class="auth-trend-subtitle">{{ __('Choose a strong new password to secure your VIREON account and continue shopping.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="auth-trend-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-trend-field">
            <x-input-label for="email" :value="__('Email')" class="auth-trend-label" />
            <x-text-input id="email" class="auth-trend-input block mt-2 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <div class="auth-trend-field">
            <x-input-label for="password" :value="__('Password')" class="auth-trend-label" />
            <x-text-input id="password" class="auth-trend-input block mt-2 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <div class="auth-trend-field">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="auth-trend-label" />
            <x-text-input id="password_confirmation" class="auth-trend-input block mt-2 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <x-primary-button class="auth-trend-submit">
            {{ __('Reset Password') }}
        </x-primary-button>
    </form>
</x-guest-layout>