<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIREON — @yield('title', 'Premium Wearable Brands')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --accent: #C8FF00; --dark: #0D0D0D; --card: #161616; --border: #2A2A2A; --muted: #888; }
        body { font-family: 'Outfit', sans-serif; background: var(--dark); color: #F0F0F0; margin: 0; }
        .font-display { font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.05em; }
        .btn-accent { background: var(--accent); color: #0D0D0D; font-weight: 700; padding: 0.6rem 1.5rem; border-radius: 4px; transition: all 0.2s; display: inline-block; text-decoration: none; }
        .btn-accent:hover { background: #fff; }
        .btn-outline { border: 1px solid var(--border); color: #F0F0F0; padding: 0.6rem 1.5rem; border-radius: 4px; transition: all 0.2s; display: inline-block; text-decoration: none; }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; }
        .nav-link { color: #aaa; transition: color 0.2s; padding: 0.25rem 0; text-decoration: none; }
        .nav-link:hover { color: var(--accent); }
        .nav-link.active { color: var(--accent); border-bottom: 2px solid var(--accent); }
        input, select, textarea { background: #1A1A1A !important; border: 1px solid var(--border) !important; color: #F0F0F0 !important; border-radius: 6px; padding: 0.6rem 1rem; width: 100%; outline: none; box-sizing: border-box; }
        input[type="checkbox"], input[type="radio"] {
            width: 1rem;
            height: 1rem;
            padding: 0;
            margin: 0;
            border: none !important;
            background: transparent !important;
            appearance: auto;
            -webkit-appearance: auto;
            accent-color: var(--accent);
            flex: 0 0 auto;
        }
        input[type="checkbox"] { border-radius: 4px; }
        input[type="radio"] { border-radius: 999px; }
        input:focus, select:focus, textarea:focus { border-color: var(--accent) !important; }
        label { color: #aaa; font-size: 0.85rem; margin-bottom: 0.3rem; display: block; font-weight: 500; }
        .badge-admin { background: #C8FF0022; color: var(--accent); border: 1px solid var(--accent); padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; }
        .badge-user { background: #ffffff11; color: #aaa; border: 1px solid var(--border); padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; }
        .alert-success { background: #C8FF0015; border: 1px solid #C8FF0040; color: var(--accent); padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-error { background: #FF3B3015; border: 1px solid #FF3B3040; color: #FF6B6B; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        * { box-sizing: border-box; }
    </style>
</head>
<body>
@php
    $cartCount = auth()->check() && ! auth()->user()->isAdmin()
        ? (auth()->user()->cart?->totalItems() ?? 0)
        : 0;
@endphp
<nav style="background:#0D0D0D;border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;">
    <div style="max-width:1200px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:64px;">
        <a href="{{ route('home') }}" style="text-decoration:none;">
            <span class="font-display" style="font-size:1.8rem;color:#fff;letter-spacing:0.1em;">VIR<span style="color:var(--accent);">E</span>ON</span>
        </a>
        <div style="display:flex;align-items:center;gap:2rem;">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
            @auth
                @if(! auth()->user()->isAdmin())
                    <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Orders</a>
                    <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}" style="display:inline-flex;align-items:center;gap:0.45rem;">
                        <span>Cart</span>
                        <span style="min-width:1.35rem;height:1.35rem;padding:0 0.35rem;border-radius:999px;background:{{ $cartCount > 0 ? 'var(--accent)' : '#222' }};color:{{ $cartCount > 0 ? '#0D0D0D' : '#aaa' }};display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;">{{ $cartCount }}</span>
                    </a>
                @endif
            @endauth
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color:var(--accent);">Admin</a>
                @endif
            @endauth
        </div>
        <div style="display:flex;align-items:center;gap:1rem;">
            @guest
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
                <a href="{{ route('register') }}" class="btn-accent" style="font-size:0.875rem;">Register</a>
            @endguest
            @auth
                <span style="color:#aaa;font-size:0.875rem;">
                    {{ auth()->user()->name }}
                    <span class="{{ auth()->user()->isAdmin() ? 'badge-admin' : 'badge-user' }}" style="margin-left:0.5rem;">{{ auth()->user()->role }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-outline" style="font-size:0.875rem;cursor:pointer;background:none;border:1px solid var(--border);color:#F0F0F0;padding:0.4rem 1rem;border-radius:4px;">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<div style="max-width:1200px;margin:0 auto;padding:0 1.5rem;">
    @if(session('success'))
        <div class="alert-success" style="margin-top:1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error" style="margin-top:1rem;">{{ session('error') }}</div>
    @endif
</div>

<main>@yield('content')</main>

<footer style="border-top:1px solid var(--border);margin-top:5rem;padding:2rem 1.5rem;text-align:center;color:var(--muted);font-size:0.875rem;">
    <span class="font-display" style="font-size:1.2rem;color:#fff;">VIREON</span>
    <p style="margin-top:0.5rem;">© {{ date('Y') }} VIREON. Your gateway to premium wearable brands.</p>
</footer>
</body>
</html>