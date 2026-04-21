<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VIREON') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-page">
        <div class="auth-shell auth-facebook-shell">
            <div class="auth-shell__backdrop"></div>
            <div class="auth-shell__content auth-facebook-layout">
                <section class="auth-facebook-brand">
                    <a href="{{ route('home') }}" class="auth-brand-mark" style="display:inline-block;margin-bottom:1.25rem;">
                        <span class="font-display">VIR<span>E</span>ON</span>
                    </a>

                    <p class="auth-facebook-kicker">Premium wearable marketplace</p>
                    <h1 class="auth-facebook-title">Shop faster. Checkout cleaner. Keep your cart in sync.</h1>
                    <p class="auth-facebook-copy">
                        Sign in to manage your order flow, revisit saved items, and move through checkout without losing momentum.
                    </p>

                    <div class="auth-facebook-stats">
                        <div class="auth-facebook-stat">
                            <strong>Instant access</strong>
                            <span>Get back to your cart and continue where you left off.</span>
                        </div>
                        <div class="auth-facebook-stat">
                            <strong>Storewide theme</strong>
                            <span>The auth screens stay aligned with the home page palette.</span>
                        </div>
                    </div>
                </section>

                <section class="auth-card-wrap auth-facebook-card-wrap">
                    <div class="auth-card auth-facebook-card" style="padding: 1.5rem;">
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>