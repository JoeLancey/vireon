<x-guest-layout>
    <div class="auth-trend-head">
        <p class="auth-trend-kicker">{{ __('Verify Your Email') }}</p>
        <h1 class="auth-trend-title font-display">{{ __('Almost There') }}</h1>
        <p class="auth-trend-subtitle">{{ __('Confirm your email address to finish setting up your VIREON account and unlock full access.') }}</p>
    </div>

    <div class="auth-trend-status mb-4">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-trend-status mb-4">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="auth-trend-form">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="auth-trend-submit">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf

            <button type="submit" class="auth-trend-link" style="background:none;border:none;padding:0;cursor:pointer;">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>