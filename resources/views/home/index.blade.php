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
                    <span style="color:var(--accent);font-size:1.1rem;line-height:1;">V</span>
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

{{-- MAIN HERO SECTION --}}
<section style="position:relative;width:100%;min-height:85vh;background:var(--dark);overflow:hidden;display:flex;align-items:flex-end;">
    <!-- Hero Background Slider -->
    <div id="heroSlider" style="position:absolute;inset:0;width:100%;height:100%;">
        @php $heroImages = $featured->take(4); @endphp
        @foreach($heroImages as $i => $product)
        <div class="hero-slide" style="position:absolute;inset:0;opacity:{{ $i === 0 ? '1' : '0' }};transition:opacity 1s ease-in-out;">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center;">
            @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a1a,#0D0D0D);display:flex;align-items:center;justify-content:center;">
                    <span class="font-display" style="font-size:30rem;color:#ffffff05;opacity:0.3;">{{ substr($product->name,0,1) }}</span>
                </div>
            @endif
            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.98) 0%,rgba(0,0,0,0.5) 40%,rgba(0,0,0,0.08) 100%);"></div>
        </div>
        @endforeach
    </div>

    <!-- Navigation Dots -->
    <div style="position:absolute;top:50%;right:2rem;transform:translateY(-50%);display:flex;flex-direction:column;gap:0.8rem;z-index:10;">
        @foreach($heroImages as $i => $product)
        <button class="hero-dot" onclick="goToSlide({{ $i }})" style="width:3px;height:{{ $i === 0 ? '40px' : '12px' }};background:{{ $i === 0 ? 'var(--accent)' : '#ffffff33' }};border:none;border-radius:999px;cursor:pointer;transition:all 0.3s ease;padding:0;" title="Slide {{ $i + 1 }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>

    <!-- Hero Content -->
    <div style="position:relative;z-index:5;width:100%;max-width:1200px;margin:0 auto;padding:0 1.5rem 5rem;">
        <div id="heroLabel" style="display:inline-flex;align-items:center;gap:0.5rem;background:#C8FF0015;border:1px solid #C8FF0035;border-radius:999px;padding:0.4rem 1.2rem;margin-bottom:1.5rem;animation:fadeIn 0.6s ease;">
            <span style="width:6px;height:6px;background:var(--accent);border-radius:50%;display:inline-block;"></span>
            <span style="color:var(--accent);font-size:0.75rem;letter-spacing:0.2em;font-weight:700;text-transform:uppercase;">
                NEW COLLECTION
            </span>
        </div>
        <h1 id="heroTitle" class="font-display" style="font-size:clamp(2.5rem,9vw,7rem);line-height:0.9;color:#fff;margin:0 0 1.5rem;max-width:900px;animation:slideUp 0.8s ease;">
            {{ strtoupper($featured->first()?->name ?? 'SHOWCASE YOUR STYLE') }}
        </h1>
        <div style="display:flex;align-items:center;gap:2rem;margin-bottom:2.5rem;flex-wrap:wrap;">
            <div>
                <p style="color:#aaa;font-size:0.9rem;margin:0 0 0.5rem;text-transform:uppercase;font-weight:600;letter-spacing:0.1em;">FOR ONLY</p>
                <p id="heroPrice" class="font-display" style="color:var(--accent);font-size:2.5rem;font-weight:700;margin:0;">
                    @if($featured->first())
                        ₱{{ number_format($featured->first()->price, 0) }}
                    @else
                        SHOP NOW
                    @endif
                </p>
            </div>
            @if($featured->first())
            <div>
                <p style="color:#aaa;font-size:0.9rem;margin:0 0 0.5rem;text-transform:uppercase;font-weight:600;letter-spacing:0.1em;">By</p>
                <p class="font-display" style="color:#fff;font-size:1.8rem;margin:0;">{{ strtoupper($featured->first()->brand->name ?? 'VIREON') }}</p>
            </div>
            @endif
        </div>
        <div style="display:flex;gap:1.2rem;flex-wrap:wrap;">
            <a id="heroBtn" href="{{ $featured->first() ? route('products.show', $featured->first()) : route('products.index') }}" class="btn-accent" style="font-size:1rem;padding:1rem 3rem;border-radius:6px;text-transform:uppercase;font-weight:700;letter-spacing:0.1em;">
                Shop This
            </a>
            <a href="{{ route('products.index') }}" class="btn-outline" style="font-size:1rem;padding:1rem 3rem;border-radius:6px;text-transform:uppercase;font-weight:700;letter-spacing:0.1em;">
                Explore All
            </a>
        </div>
    </div>
</section>

{{-- FEATURED PRODUCTS GRID (IMAGE SHOWCASE) --}}
<section style="background:var(--dark);padding:5rem 1.5rem;border-top:1px solid var(--border);">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="margin-bottom:4rem;text-align:center;">
            <p style="color:var(--accent);font-size:0.75rem;letter-spacing:0.2em;font-weight:700;margin:0 0 1rem;text-transform:uppercase;">Featured Selection</p>
            <h2 class="font-display" style="font-size:clamp(2rem,5vw,3.5rem);color:#fff;margin:0;line-height:1;">LATEST DROPS</h2>
        </div>

        {{-- Featured Products Grid --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(340px, 1fr));gap:1.5rem;margin-bottom:4rem;">
            @forelse($featured as $product)
            <a href="{{ route('products.show', $product) }}" style="text-decoration:none;display:block;position:relative;group;">
                <div style="position:relative;overflow:hidden;border-radius:12px;aspect-ratio:1;background:linear-gradient(135deg,#111,#1a1a1a);border:1px solid var(--border);transition:all 0.4s ease;cursor:pointer;" class="product-card" onmouseover="this.style.transform='scale(1.02)';this.style.borderColor='var(--accent)'" onmouseout="this.style.transform='scale(1)';this.style.borderColor='var(--border)'">
                    {{-- Product Image --}}
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center;transition:transform 0.4s ease;" class="product-img" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a1a1a,#111);">
                            <span class="font-display" style="font-size:6rem;color:#ffffff08;">{{ substr($product->name,0,1) }}</span>
                        </div>
                    @endif
                    
                    {{-- Badge --}}
                    <div style="position:absolute;top:1rem;right:1rem;background:var(--accent);color:#0D0D0D;padding:0.5rem 1rem;border-radius:999px;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;z-index:2;">New</div>

                    {{-- Product Info Overlay --}}
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.9) 0%,rgba(0,0,0,0.4) 50%,rgba(0,0,0,0.08) 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:2rem;opacity:0;transition:opacity 0.3s ease;" class="product-overlay" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                        <p style="color:var(--accent);font-size:0.85rem;margin:0 0 0.5rem;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;">{{ strtoupper($product->brand->name) }}</p>
                        <h3 class="font-display" style="color:#fff;font-size:1.8rem;margin:0 0 1rem;line-height:1;">{{ strtoupper($product->name) }}</h3>
                        <div style="display:flex;justify-content:space-between;align-items:flex-end;">
                            <p class="font-display" style="color:var(--accent);font-size:1.5rem;margin:0;font-weight:700;">₱{{ number_format($product->price, 0) }}</p>
                            <p style="color:#aaa;font-size:0.9rem;margin:0;">{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</p>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column:1/-1;padding:3rem;border:1px solid var(--border);border-radius:12px;text-align:center;color:#666;background:linear-gradient(135deg,#111,#1a1a1a);">
                <p style="font-size:1.1rem;margin:0;">No featured products available yet.</p>
            </div>
            @endforelse
        </div>

        {{-- View All Featured --}}
        <div style="text-align:center;">
            <a href="{{ route('products.index') }}" class="btn-accent" style="font-size:1rem;padding:1rem 3rem;text-transform:uppercase;font-weight:700;letter-spacing:0.1em;">View All Products</a>
        </div>
    </div>
</section>

{{-- BRANDS SHOWCASE SECTION --}}
<section style="padding:5rem 1.5rem;background:linear-gradient(135deg,#0D0D0D,#111);border-top:1px solid var(--border);">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="margin-bottom:4rem;">
            <p style="color:var(--accent);font-size:0.75rem;letter-spacing:0.2em;font-weight:700;margin:0 0 1rem;text-transform:uppercase;">Official Partners</p>
            <h2 class="font-display" style="font-size:clamp(2rem,5vw,3.5rem);color:#fff;margin:0;line-height:1;">SHOP BY BRAND</h2>
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;">
            @forelse($brands as $brand)
            <a href="{{ route('products.index', ['brand_id' => $brand->id]) }}" style="text-decoration:none;display:block;">
                <div style="position:relative;aspect-ratio:1;border-radius:12px;overflow:hidden;border:1px solid var(--border);background:linear-gradient(135deg,#161616,#1a1a1a);transition:all 0.3s;cursor:pointer;group;" onmouseover="this.style.transform='translateY(-8px)';this.style.borderColor='var(--accent)'" onmouseout="this.style.transform='translateY(0)';this.style.borderColor='var(--border)'">
                    {{-- Brand Background --}}
                    <div style="position:absolute;inset:0;background:linear-gradient(135deg,{{ $brand->accent_color ?? 'var(--accent)' }}22,{{ $brand->accent_color ?? 'var(--accent)' }}08);filter:blur(20px);"></div>
                    
                    {{-- Brand Logo/Content --}}
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:2;">
                        @if($brand->logo)
                            <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" style="width:120px;max-height:120px;height:auto;object-fit:contain;opacity:0.95;transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div style="text-align:center;">
                                <div class="font-display" style="color:{{ $brand->accent_color ?? 'var(--accent)' }};font-size:3rem;font-weight:700;line-height:1;margin-bottom:0.5rem;">{{ substr($brand->name,0,3) }}</div>
                                <p class="font-display" style="color:#fff;font-size:0.9rem;margin:0;font-weight:600;letter-spacing:0.1em;">{{ $brand->name }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Hover Info --}}
                    <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.95),transparent);padding:1.5rem;transform:translateY(20px);opacity:0;transition:all 0.3s;z-index:3;" class="brand-hover" onmouseover="this.style.transform='translateY(0)';this.style.opacity='1'" onmouseout="this.style.transform='translateY(20px)';this.style.opacity='0'">
                        <p class="font-display" style="color:#fff;font-size:1.3rem;margin:0 0 0.5rem;font-weight:700;">{{ strtoupper($brand->name) }}</p>
                        <p style="color:var(--accent);font-size:0.9rem;margin:0;font-weight:600;">{{ $brand->products_count }} {{ $brand->products_count === 1 ? 'Product' : 'Products' }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column:1/-1;padding:3rem;border:1px solid var(--border);border-radius:12px;text-align:center;color:#666;">
                <p style="font-size:1.1rem;margin:0;">No brands available yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ALL PRODUCTS SHOWCASE --}}
<section style="padding:5rem 1.5rem;background:var(--dark);border-top:1px solid var(--border);">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="margin-bottom:4rem;">
            <p style="color:var(--accent);font-size:0.75rem;letter-spacing:0.2em;font-weight:700;margin:0 0 1rem;text-transform:uppercase;">Complete Collection</p>
            <h2 class="font-display" style="font-size:clamp(2rem,5vw,3.5rem);color:#fff;margin:0;line-height:1;">EXPLORE OUR CATALOG</h2>
        </div>

        {{-- Products Grid (4 columns on desktop, responsive) --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.2rem;">
            @forelse($allProducts as $product)
            <a href="{{ route('products.show', $product) }}" style="text-decoration:none;display:block;">
                <div style="position:relative;overflow:hidden;border-radius:12px;aspect-ratio:1;background:linear-gradient(135deg,#111,#1a1a1a);border:1px solid var(--border);transition:all 0.3s ease;cursor:pointer;" onmouseover="this.style.transform='scale(1.02)';this.style.borderColor='var(--accent)'" onmouseout="this.style.transform='scale(1)';this.style.borderColor='var(--border)'">
                    {{-- Product Image --}}
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center;transition:transform 0.4s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a1a1a,#111);">
                            <span class="font-display" style="font-size:5rem;color:#ffffff08;">{{ substr($product->name,0,1) }}</span>
                        </div>
                    @endif
                    
                    {{-- Info Overlay --}}
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.9) 0%,transparent);padding:1.5rem;display:flex;flex-direction:column;justify-content:flex-end;opacity:0;transition:opacity 0.3s ease;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                        <p style="color:var(--accent);font-size:0.75rem;margin:0 0 0.3rem;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;">{{ strtoupper($product->brand->name) }}</p>
                        <p class="font-display" style="color:#fff;font-size:1.3rem;margin:0 0 0.5rem;line-height:1;max-height:2.6rem;overflow:hidden;">{{ strtoupper($product->name) }}</p>
                        <p class="font-display" style="color:var(--accent);font-size:1.2rem;margin:0;font-weight:700;">₱{{ number_format($product->price, 0) }}</p>
                    </div>

                    {{-- Stock Badge --}}
                    @if($product->stock === 0)
                    <div style="position:absolute;top:1rem;left:1rem;background:#FF6B6B;color:#fff;padding:0.4rem 0.8rem;border-radius:4px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;z-index:2;">Out of Stock</div>
                    @endif
                </div>
            </a>
            @empty
            <div style="grid-column:1/-1;padding:3rem;border:1px solid var(--border);border-radius:12px;text-align:center;color:#666;background:linear-gradient(135deg,#111,#1a1a1a);">
                <p style="font-size:1.1rem;margin:0;">No products available yet.</p>
            </div>
            @endforelse
        </div>

        {{-- Browse All --}}
        <div style="text-align:center;margin-top:3rem;">
            <a href="{{ route('products.index') }}" class="btn-outline" style="font-size:1rem;padding:1rem 3rem;text-transform:uppercase;font-weight:700;letter-spacing:0.1em;">Browse All Products</a>
        </div>
    </div>
</section>

{{-- CTA BANNER WITH IMAGE --}}
@guest
<section style="position:relative;width:100%;height:60vh;min-height:500px;overflow:hidden;background:var(--dark);border-top:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
    <!-- Background with gradient -->
    <div style="position:absolute;inset:0;">
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#0D0D0D,#1a1a1a);"></div>
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 50% 50%,rgba(200, 255, 0, 0.1) 0%,transparent 70%);"></div>
        <div style="position:absolute;top:10%;left:5%;width:400px;height:400px;background:var(--accent);opacity:0.04;border-radius:50%;filter:blur(80px);animation:float 6s ease-in-out infinite;"></div>
        <div style="position:absolute;bottom:10%;right:5%;width:350px;height:350px;background:var(--accent);opacity:0.03;border-radius:50%;filter:blur(80px);animation:float 8s ease-in-out infinite reverse;"></div>
    </div>

    <!-- Content -->
    <div style="position:relative;z-index:2;padding:3rem;text-align:center;max-width:700px;">
        <p style="color:var(--accent);font-size:0.8rem;letter-spacing:0.2em;font-weight:700;margin:0 0 1rem;text-transform:uppercase;animation:slideUp 0.6s ease;">Ready to Stand Out?</p>
        <h2 class="font-display" style="font-size:clamp(2.5rem,6vw,4.5rem);color:#fff;margin:0 0 1.5rem;line-height:0.95;animation:slideUp 0.8s ease 0.1s backwards;">JOIN THE VIREON COMMUNITY</h2>
        <p style="color:#aaa;font-size:1.05rem;margin:0 0 2.5rem;line-height:1.7;animation:slideUp 1s ease 0.2s backwards;">Create an account to access exclusive drops, member-only deals, and the latest from your favorite brands.</p>
        <div style="display:flex;gap:1.2rem;justify-content:center;flex-wrap:wrap;animation:slideUp 1.2s ease 0.3s backwards;">
            <a href="{{ route('register') }}" class="btn-accent" style="font-size:1rem;padding:1rem 2.5rem;text-transform:uppercase;font-weight:700;letter-spacing:0.1em;">Create Account</a>
            <a href="{{ route('login') }}" class="btn-outline" style="font-size:1rem;padding:1rem 2.5rem;text-transform:uppercase;font-weight:700;letter-spacing:0.1em;">Sign In</a>
        </div>
    </div>
</section>
@endguest

{{-- FOOTER CTA --}}
<section style="padding:4rem 1.5rem;background:linear-gradient(135deg,#0D0D0D,#111);border-top:1px solid var(--border);text-align:center;">
    <div style="max-width:1200px;margin:0 auto;">
        <div class="font-display" style="font-size:2.5rem;color:#fff;margin:0 0 1rem;line-height:1;">THOUSANDS OF STYLES. ONE DESTINATION.</div>
        <p style="color:#aaa;font-size:1.1rem;max-width:600px;margin:0 auto;line-height:1.7;">Discover premium wearable brands and exclusive collections. Your style, amplified.</p>
    </div>
</section>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(30px); }
    }

    .hero-slide { animation: fadeIn 0.8s ease; }
    .hero-dot { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .hero-dot:hover { background: var(--accent) !important; height: 30px !important; }
    
    .product-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .product-overlay { transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    
    @media (max-width: 768px) {
        .hero-dot { display: none; }
        .product-overlay { opacity: 1; }
    }
</style>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const heroData = @json($heroData);
    const autoSlideInterval = 5000;
    let slideTimer;

    function updateSlide() {
        slides.forEach((slide, i) => {
            slide.style.opacity = i === currentSlide ? '1' : '0';
        });
        
        dots.forEach((dot, i) => {
            if (i === currentSlide) {
                dot.style.height = '40px';
                dot.style.background = 'var(--accent)';
            } else {
                dot.style.height = '12px';
                dot.style.background = '#ffffff33';
            }
        });

        if (heroData[currentSlide]) {
            document.getElementById('heroTitle').textContent = heroData[currentSlide].name;
            document.getElementById('heroPrice').textContent = heroData[currentSlide].price;
            document.getElementById('heroLabel').querySelector('span:last-child').textContent = heroData[currentSlide].brand + ' — NEW DROP';
            document.getElementById('heroBtn').href = heroData[currentSlide].url;
        }
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlide();
        clearTimeout(slideTimer);
        startAutoSlide();
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        updateSlide();
    }

    function startAutoSlide() {
        slideTimer = setInterval(() => {
            nextSlide();
        }, autoSlideInterval);
    }

    // Initialize
    if (slides.length > 0) {
        updateSlide();
        startAutoSlide();
    }

    // Pause on hover
    document.getElementById('heroSlider')?.addEventListener('mouseenter', () => clearTimeout(slideTimer));
    document.getElementById('heroSlider')?.addEventListener('mouseleave', () => startAutoSlide());
</script>

@endsection
