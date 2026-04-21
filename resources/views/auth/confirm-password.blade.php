<x-guest-layout>
    <div class="auth-trend-head">
        <p class="auth-trend-kicker">{{ __('Security Check') }}</p>
        <h1 class="auth-trend-title font-display">{{ __('Confirm Password') }}</h1>
        <p class="auth-trend-subtitle">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>
    </div>

    <div class="auth-trend-status mb-4">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-trend-form">
        @csrf

        <div class="auth-trend-field">
            <x-input-label for="password" :value="__('Password')" class="auth-trend-label" />
            <x-text-input id="password" class="auth-trend-input block mt-2 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 !text-[#ff8f8f]" />
        </div>

        <x-primary-button class="auth-trend-submit">
            {{ __('Confirm') }}
        </x-primary-button>
    </form>
</x-guest-layout>