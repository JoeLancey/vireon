@extends('layouts.app')
@section('title', 'VIREON — Premium Wearable Brands')

@section('content')

{{-- ADMIN PANEL --}}
@auth
@if(auth()->user()->isAdmin())
<section style="max-width:1200px;margin:2rem auto;padding:0 1.5rem;">
    <div style="background:linear-gradient(135deg,#161616,#111);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
        <div style="padding:1.75rem 2rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div style="width:40px;height:40px;background:var(--accent)15;border:1px solid var(--accent)40;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <span style="font-size:1.1rem;">⚡</span>
                </div>
                <div>
                    <p style="color:var(--accent);font-size:0.72rem;letter-spacing:0.2em;font-weight:600;margin:0 0 0.15rem;">ADMINISTRATOR</p>
                    <h2 class="font-display" style="font-size:1.5rem;color:#fff;margin:0;line-height:1;">STORE OVERVIEW</h2>
                </div>
            </div>
            <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
                <a href="{{ route('admin.products.create') }}" class="btn-accent" style="font-size:0.78rem;padding:0.5rem 1rem;">+ Add Product</a>
                <a href="{{ route('admin.brands.create') }}" class="btn-outline" style="font-size:0.78rem;padding:0.5rem 1rem;">+ Add Brand</a>
                <a href="{{ route('admin.products.index') }}" class="btn-outline" style="font-size:0.78rem;padding:0.5rem 1rem;">Manage All</a>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);">
            <div style="padding:1.75rem 2rem;border-right:1px solid var(--border);">
                <p style="color:#555;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;margin:0 0 0.5rem;">Total Products</p>
                <p class="font-display" style="font-size:2.75rem;color:#fff;margin:0;line-height:1;">{{ $stats['total_products'] }}</p>
                <p style="color:#444;font-size:0.72rem;margin:0.4rem 0 0;">across all brands</p>
            </div>
            <div style="padding:1.75rem 2rem;border-right:1px solid var(--border);position:relative;">
                <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#4ADE80,transparent);"></div>
                <p style="color:#555;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;margin:0 0 0.5rem;">In Stock</p>
                <p class="font-display" style="font-size:2.75rem;color:#4ADE80;margin:0;line-height:1;">{{ $stats['in_stock'] }}</p>
                <p style="color:#444;font-size:0.72rem;margin:0.4rem 0 0;">available now</p>
            </div>
            <div style="padding:1.75rem 2rem;border-right:1px solid var(--border);position:relative;">
                <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#FF6B6B,transparent);"></div>
                <p style="color:#555;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;margin:0 0 0.5rem;">Out of Stock</p>
                <p class="font-display" style="font-size:2.75rem;color:#FF6B6B;margin:0;line-height:1;">{{ $stats['out_of_stock'] }}</p>
                <p style="color:#444;font-size:0.72rem;margin:0.4rem 0 0;">needs restocking</p>
            </div>
            <div style="padding:1.75rem 2rem;position:relative;">
                <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#60A5FA,transparent);"></div>
                <p style="color:#555;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;margin:0 0 0.5rem;">Brands</p>
                <p class="font-display" style="font-size:2.75rem;color:#60A5FA;margin:0;line-height:1;">{{ $stats['total_brands'] }}</p>
                <p style="color:#444;font-size:0.72rem;margin:0.4rem 0 0;">partner brands</p>
            </div>
        </div>
        <div style="padding:1.25rem 2rem;border-top:1px solid var(--border);display:flex;align-items:center;gap:2rem;flex-wrap:wrap;">
            <p style="color:#444;font-size:0.75rem;margin:0;">QUICK LINKS:</p>
            <a href="{{ route('admin.products.index') }}" style="color:#666;font-size:0.8rem;text-decoration:none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#666'">All Products →</a>
            <a href="{{ route('admin.brands.index') }}" style="color:#666;font-size:0.8rem;text-decoration:none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#666'">All Brands →</a>
            <a href="{{ route('admin.products.create') }}" style="color:#666;font-size:0.8rem;text-decoration:none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#666'">New Product →</a>
            <a href="{{ route('admin.brands.create') }}" style="color:#666;font-size:0.8rem;text-decoration:none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#666'">New Brand →</a>
        </div>
    </div>
</section>
@endif
@endauth

{{-- HERO BANNER --}}
<section style="position:relative;width:100%;height:92vh;min-height:600px;overflow:hidden;display:flex;align-items:flex-end;">

    {{-- Slideshow Background --}}
    <div id="heroSlider" style="position:absolute;inset:0;">
        @php $heroImages = $featured->take(4); @endphp
        @foreach($heroImages as $i => $product)
        <div class="hero-slide" style="position:absolute;inset:0;opacity:{{ $i === 0 ? '1' : '0' }};transition:opacity 1s ease;">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center;">
            @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#111,#1a1a1a);display:flex;align-items:center;justify-content:center;">
                    <span class="font-display" style="font-size:20rem;color:#ffffff05;">{{ substr($product->name,0,1) }}</span>
                </div>
            @endif
            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.95) 0%,rgba(0,0,0,0.4) 50%,rgba(0,0,0,0.1) 100%);"></div>
        </div>
        @endforeach
    </div>

    {{-- Slide Dots --}}
    <div style="position:absolute;top:2rem;right:2rem;display:flex;flex-direction:column;gap:0.5rem;z-index:10;">
        @foreach($heroImages as $i => $product)
        <div class="hero-dot" role="button" tabindex="0" title="Show slide {{ $i + 1 }}" aria-label="Show slide {{ $i + 1 }}" onclick="goToSlide({{ $i }})" onkeydown="if(event.key==='Enter' || event.key===' ') goToSlide({{ $i }});" style="width:3px;height:{{ $i === 0 ? '30px' : '12px' }};background:{{ $i === 0 ? 'var(--accent)' : '#ffffff40' }};border-radius:999px;cursor:pointer;transition:all 0.3s;"></div>
        @endforeach
    </div>

    {{-- Hero Content --}}
    <div style="position:relative;z-index:2;width:100%;max-width:1200px;margin:0 auto;padding:0 1.5rem 4rem;">
        <div id="heroLabel" style="display:inline-flex;align-items:center;gap:0.5rem;background:#C8FF0015;border:1px solid #C8FF0035;border-radius:999px;padding:0.35rem 1rem;margin-bottom:1.25rem;">
            <span style="width:5px;height:5px;background:var(--accent);border-radius:50%;display:inline-block;"></span>
            <span style="color:var(--accent);font-size:0.72rem;letter-spacing:0.2em;font-weight:600;">
                {{ $featured->first()?->brand->name ?? 'VIREON' }} — NEW DROP
            </span>
        </div>
        <h1 id="heroTitle" class="font-display" style="font-size:clamp(3.5rem,8vw,7rem);line-height:0.92;color:#fff;margin-bottom:1.25rem;max-width:700px;">
            {{ strtoupper($featured->first()?->name ?? 'GEAR UP. STAND OUT.') }}
        </h1>
        <p id="heroPrice" style="color:var(--accent);font-size:1.5rem;font-weight:700;margin-bottom:2rem;" class="font-display">
            @if($featured->first())
                ₱{{ number_format($featured->first()->price, 2) }}
            @endif
        </p>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
            <a id="heroBtn" href="{{ $featured->first() ? route('products.show', $featured->first()) : route('products.index') }}" class="btn-accent" style="font-size:1rem;padding:0.875rem 2.5rem;border-radius:6px;">
                Shop Now
            </a>
            <a href="{{ route('products.index') }}" class="btn-outline" style="font-size:1rem;padding:0.875rem 2.5rem;">
                View All
            </a>
        </div>
    </div>
</section>

{{-- BRANDS --}}
<section style="max-width:1200px;margin:4rem auto;padding:0 1.5rem;">
    <div style="margin-bottom:3rem;">
        <p style="color:var(--accent);font-size:0.75rem;letter-spacing:0.2em;font-weight:700;margin:0 0 0.75rem;text-transform:uppercase;">Official Partners</p>
        <h2 class="font-display" style="font-size:3rem;color:#fff;margin:0;">SHOP BY BRAND</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1.5rem;">
        @forelse($brands as $brand)
        <a href="{{ route('products.index', ['brand_id' => $brand->id]) }}" style="text-decoration:none;">
            <div style="background:#111;border:1px solid var(--border);border-radius:14px;padding:2rem 1.5rem;text-align:center;transition:all 0.3s;cursor:pointer;"
                 onmouseover="this.style.borderColor='{{ $brand->accent_color }}';this.style.transform='translateY(-3px)'"
                 onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                <div style="width:70px;height:70px;border-radius:50%;background:{{ $brand->accent_color }}15;border:1.5px solid {{ $brand->accent_color }}35;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;overflow:hidden;">
                    @if($brand->logo)
                        <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" style="width:90%;height:90%;object-fit:contain;">
                    @else
                        <span class="font-display" style="color:{{ $brand->accent_color }};font-size:1.5rem;font-weight:700;">{{ substr($brand->name,0,2) }}</span>
                    @endif
                </div>
                <h3 class="font-display" style="color:#fff;font-size:1.2rem;margin:0 0 0.4rem;font-weight:700;">{{ $brand->name }}</h3>
                <p style="color:#777;font-size:0.9rem;margin:0;">{{ $brand->products_count }} items</p>
            </div>
        </a>
        @empty
        <div style="grid-column:1/-1;padding:2rem;border:1px solid var(--border);border-radius:14px;text-align:center;color:#888;">
            No brands available.
        </div>
        @endforelse
    </div>
</section>

{{-- CTA BANNER --}}
@guest
<section style="max-width:1200px;margin:0 auto 5rem;padding:0 1.5rem;">
    <div style="position:relative;border-radius:16px;overflow:hidden;min-height:300px;display:flex;align-items:center;justify-content:center;text-align:center;">
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#0D0D0D,#1a1a1a);"></div>
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 50% 50%,#C8FF0018,transparent 70%);"></div>
        <div style="position:absolute;top:-50px;left:-50px;width:300px;height:300px;background:var(--accent);opacity:0.04;border-radius:50%;filter:blur(60px);"></div>
        <div style="position:absolute;bottom:-50px;right:-50px;width:300px;height:300px;background:var(--accent);opacity:0.04;border-radius:50%;filter:blur(60px);"></div>
        <div style="position:relative;z-index:1;padding:3rem 2rem;">
            <p style="color:var(--accent);font-size:0.72rem;letter-spacing:0.25em;font-weight:600;margin:0 0 1rem;">EXCLUSIVE ACCESS</p>
            <h2 class="font-display" style="font-size:clamp(2.5rem,5vw,4.5rem);color:#fff;margin:0 0 1rem;line-height:0.95;">JOIN VIREON.<br>SHOP BETTER.</h2>
            <p style="color:#555;max-width:420px;margin:0 auto 2rem;line-height:1.7;">Create a free account and unlock access to exclusive drops, deals, and the latest from your favorite brands.</p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('register') }}" class="btn-accent" style="font-size:1rem;padding:0.875rem 2.5rem;">Create Account</a>
                <a href="{{ route('login') }}" class="btn-outline" style="font-size:1rem;padding:0.875rem 2.5rem;">Sign In</a>
            </div>
        </div>
    </div>
</section>
@endguest

<style>
.hero-dot {
    cursor: pointer;
}
.hero-dot:hover {
    opacity: 0.9;
}
</style>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const heroData = @json($heroData);

    function goToSlide(index) {
        slides[currentSlide].style.opacity = '0';
        dots[currentSlide].style.height = '12px';
        dots[currentSlide].style.background = '#ffffff40';
        currentSlide = index;
        slides[currentSlide].style.opacity = '1';
        dots[currentSlide].style.height = '30px';
        dots[currentSlide].style.background = 'var(--accent)';

        if (heroData[currentSlide]) {
            document.getElementById('heroTitle').textContent = heroData[currentSlide].name;
            document.getElementById('heroPrice').textContent = heroData[currentSlide].price;
            document.getElementById('heroLabel').querySelector('span:last-child').textContent = heroData[currentSlide].brand + ' — NEW DROP';
            document.getElementById('heroBtn').href = heroData[currentSlide].url;
        }
    }

    setInterval(() => {
        goToSlide((currentSlide + 1) % slides.length);
    }, 4000);
</script>

@endsection