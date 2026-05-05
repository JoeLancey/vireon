@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="page-container product-show-page" style="max-width:1100px;margin:2rem auto;padding:0 1.5rem;">
    <a href="{{ route('products.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back to Products</a>

    <div class="product-show-layout" style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;margin-top:2rem;align-items:start;">

        {{-- Image Gallery --}}
        <div class="product-show-gallery" style="display:flex;gap:12px;align-items:flex-start;">

            {{-- Main Image --}}
            <div class="product-show-main" style="background:#161616;border:1px solid var(--border);border-radius:12px;overflow:hidden;flex:1;height:450px;">
                @if($product->image)
                    <img id="mainImage" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                        <span class="font-display" style="color:#2A2A2A;font-size:5rem;">{{ substr($product->name,0,2) }}</span>
                    </div>
                @endif
            </div>

            {{-- Thumbnails (Right Side) --}}
            @if($product->image || $product->images->count())
            <div class="product-show-thumbs" style="display:flex;flex-direction:column;gap:10px;">
                @if($product->image)
                <img src="{{ Storage::url($product->image) }}" onclick="changeImage(this)"
                     style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--accent);cursor:pointer;">
                @endif
                @foreach($product->images as $img)
                <img src="{{ Storage::url($img->image_path) }}" onclick="changeImage(this)"
                     style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--border);cursor:pointer;">
                @endforeach
            </div>
            @endif

        </div>

        {{-- Product Video (if available) --}}
        @if($product->video)
        <div class="product-show-video" style="grid-column:1/-1;margin-top:2rem;">
            <h2 style="color:#fff;font-size:1.25rem;margin-bottom:1rem;font-weight:600;">Product Video</h2>
            <div style="background:#161616;border:1px solid var(--border);border-radius:12px;overflow:hidden;aspect-ratio:16/9;">
                <video width="100%" height="100%" style="width:100%;height:100%;object-fit:cover;" controls>
                    <source src="{{ Storage::url($product->video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        @endif

        {{-- Details --}}
        <div class="product-show-details">
            @php
                $brandAccent = $product->brand?->accent_color ?? '#C8FF00';
                $brandName = $product->brand?->name ?? 'VIREON';
            @endphp
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;flex-wrap:wrap;">
                <span style="background:{{ $brandAccent }}22;color:{{ $brandAccent }};border:1px solid {{ $brandAccent }}44;padding:0.25rem 0.75rem;border-radius:4px;font-size:0.8rem;font-weight:700;text-transform:uppercase;">
                    {{ $brandName }}
                </span>
                <span style="color:var(--muted);font-size:0.8rem;text-transform:capitalize;">{{ $product->category }}</span>
                @if($product->is_featured)
                <span style="background:var(--accent)22;color:var(--accent);border:1px solid var(--accent)44;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:600;">FEATURED</span>
                @endif
            </div>

            <h1 style="font-size:2rem;font-weight:700;color:#fff;margin-bottom:1rem;line-height:1.2;">{{ $product->name }}</h1>
            <p class="font-display" style="font-size:2.5rem;color:var(--accent);margin-bottom:1rem;">₱{{ number_format($product->price,2) }}</p>
            <p style="color:{{ $product->stock > 0 ? '#4ADE80' : '#FF6B6B' }};font-size:0.875rem;font-weight:600;margin-bottom:1.5rem;">
                {{ $product->stock > 0 ? '✓ In Stock (' . $product->stock . ' available)' : '✗ Out of Stock' }}
            </p>

            @if($product->description)
            <p style="color:#aaa;line-height:1.7;margin-bottom:2rem;">{{ $product->description }}</p>
            @endif

            @auth
                @if(!auth()->user()->isAdmin())
                <form method="POST" action="{{ route('cart.store', $product) }}" style="display:grid;gap:1.25rem;" onsubmit="return validateCart(event)">
                    @csrf

                    {{-- Size Selection --}}
                    @if($product->sizes->count())
                    <div class="product-show-sizes" style="padding:1rem;background:#1A1A1A;border:1px solid var(--border);border-radius:10px;">
                        <label style="color:#fff;display:block;margin-bottom:0.75rem;font-weight:600;font-size:0.95rem;">Select Size *</label>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            @foreach($product->sizes as $size)
                                @php
                                    $sizeStock = (int) ($size->pivot->stock ?? 0);
                                    $isSizeAvailable = $sizeStock > 0;
                                @endphp
                                <label style="display:flex;align-items:center;gap:0.4rem;cursor:{{ $isSizeAvailable ? 'pointer' : 'not-allowed' }};padding:0.5rem 0.75rem;background:{{ $isSizeAvailable ? '#141414' : '#101010' }};border:1px solid {{ $isSizeAvailable ? 'var(--border)' : '#2b2b2b' }};border-radius:8px;transition:all 0.2s;opacity:{{ $isSizeAvailable ? '1' : '0.45' }};" onmouseover="{{ $isSizeAvailable ? "this.style.borderColor='var(--accent)'" : '' }}" onmouseout="{{ $isSizeAvailable ? "if(!this.querySelector('input').checked) this.style.borderColor='var(--border)'" : '' }}">
                                    <input type="radio" name="size_id" value="{{ $size->id }}" data-stock="{{ $sizeStock }}" {{ $isSizeAvailable ? '' : 'disabled' }} style="width:auto;accent-color:var(--accent);" onchange="onSizeSelected(this)">
                                    <span style="color:#aaa;font-size:0.9rem;">{{ $size->name }} <span style="color:{{ $isSizeAvailable ? 'var(--muted)' : '#FF6B6B' }};font-size:0.78rem;">({{ $isSizeAvailable ? $sizeStock . ' left' : 'Out of stock' }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Quantity & Action Buttons --}}
                    <div class="product-show-actions" style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;">
                        <div style="flex:0 0 120px;">
                            <label for="quantity" style="margin-bottom:0.45rem;color:var(--muted);font-size:0.8rem;display:block;">Quantity</label>
                            <input id="quantity" type="number" name="quantity" min="1" max="{{ max($product->stock, 1) }}" value="1" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        </div>

                        <button type="submit" class="btn-accent" style="border:none;cursor:pointer;font-size:1rem;padding:0.875rem 2.5rem;flex:1;{{ $product->stock < 1 ? 'opacity:0.4;cursor:not-allowed;' : '' }};align-self:flex-end;" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            Add to Cart
                        </button>

                        @php
                            $inWishlist = auth()->check() ? auth()->user()->wishedProducts()->where('product_id', $product->id)->exists() : false;
                        @endphp
                        <button id="wishlist-toggle" type="button" class="btn-outline" data-toggle-url="{{ route('wishlist.toggle', $product) }}" data-in-wishlist="{{ $inWishlist ? '1' : '0' }}" style="padding:0.875rem 1.5rem;cursor:pointer;border:none;{{ $inWishlist ? 'background:rgba(255,107,107,0.15);color:#FF6B6B;' : '' }}">
                            {{ $inWishlist ? '❤ Wishlisted' : '🤍 Wishlist' }}
                        </button>
                    </div>
                </form>

                <script>
                    function validateCart(event) {
                        if (event.submitter && event.submitter.dataset.skipSizeValidation === '1') {
                            return true;
                        }

                        const hasSizes = {{ $product->sizes->count() ? 'true' : 'false' }};
                        if (hasSizes) {
                            const selectedSize = document.querySelector('input[name="size_id"]:checked');
                            if (!selectedSize) {
                                event.preventDefault();
                                alert('Please select a size before adding to cart');
                                return false;
                            }
                        }
                        return true;
                    }

                    function onSizeSelected(input) {
                        const quantityInput = document.getElementById('quantity');
                        if (!quantityInput) {
                            return;
                        }

                        const stock = parseInt(input.dataset.stock || '0', 10);
                        if (stock > 0) {
                            quantityInput.max = stock;
                            if (parseInt(quantityInput.value || '1', 10) > stock) {
                                quantityInput.value = stock;
                            }
                        }
                    }

                    (function () {
                        const button = document.getElementById('wishlist-toggle');
                        if (!button) {
                            console.log('Wishlist button not found');
                            return;
                        }

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (!csrfToken) {
                            console.error('CSRF token not found');
                            button.disabled = true;
                            return;
                        }

                        function setButtonState(isWishlisted) {
                            button.dataset.inWishlist = isWishlisted ? '1' : '0';
                            button.textContent = isWishlisted ? '❤ Wishlisted' : '🤍 Wishlist';
                            if (isWishlisted) {
                                button.style.background = 'rgba(255,107,107,0.15)';
                                button.style.border = 'none';
                                button.style.color = '#FF6B6B';
                            } else {
                                button.style.background = 'transparent';
                                button.style.border = 'none';
                                button.style.color = '#fff';
                            }
                        }

                        button.addEventListener('click', async function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            const url = button.dataset.toggleUrl;
                            if (!url) {
                                alert('Wishlist URL not set');
                                return;
                            }

                            const previousState = button.dataset.inWishlist === '1';
                            button.disabled = true;

                            try {
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                    },
                                });

                                if (!response.ok) {
                                    throw new Error('HTTP ' + response.status);
                                }

                                const data = await response.json();
                                setButtonState(data.status === 'added');
                            } catch (error) {
                                console.error('Wishlist error:', error);
                                setButtonState(previousState);
                                alert('Could not update wishlist. Please try again.');
                            } finally {
                                button.disabled = false;
                            }
                        });
                    })();

                </script>
                @else
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-accent" style="display:block;text-align:center;padding:0.875rem;">Edit Product</a>
                @endif
            @else
            <a href="{{ route('login') }}" class="btn-accent" style="display:block;text-align:center;padding:0.875rem;font-size:1rem;">Login to Purchase</a>
            @endauth
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->count())
    <div style="margin-top:4rem;">
        <h2 class="font-display" style="font-size:1.75rem;color:#fff;margin-bottom:1.5rem;">MORE FROM {{ strtoupper($brandName) }}</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
            @foreach($related as $rel)
            <a href="{{ route('products.show', $rel) }}" style="text-decoration:none;">
                <div class="card" style="overflow:hidden;transition:all 0.2s;"
                     onmouseover="this.style.borderColor='var(--accent)'"
                     onmouseout="this.style.borderColor='var(--border)'">
                    <div style="background:#1A1A1A;height:140px;display:flex;align-items:center;justify-content:center;">
                        @if($rel->image)
                            <img src="{{ Storage::url($rel->image) }}" alt="{{ $rel->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <span class="font-display" style="color:#333;font-size:2rem;">{{ substr($rel->name,0,2) }}</span>
                        @endif
                    </div>
                    <div style="padding:0.75rem;">
                        <p style="color:#fff;font-size:0.875rem;font-weight:500;margin-bottom:0.3rem;">{{ $rel->name }}</p>
                        <p style="color:var(--accent);font-weight:700;">₱{{ number_format($rel->price,2) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Reviews Section --}}
    <div style="margin-top:4rem;padding:2rem;background:#1A1A1A;border:1px solid var(--border);border-radius:14px;">
        <h2 style="color:#fff;font-size:1.5rem;margin-bottom:1.5rem;font-weight:600;">Customer Reviews</h2>

        @auth
            @if(!auth()->user()->isAdmin())
                @if($canReview)
                    @if(!$hasReviewed)
                        <div style="margin-bottom:2rem;padding:1.5rem;background:#141414;border-radius:12px;border:1px solid var(--border);">
                            <h3 style="color:#fff;margin-bottom:1rem;font-size:1.1rem;">✓ Share Your Experience</h3>
                            <form method="POST" action="{{ route('reviews.store', $product) }}" style="display:grid;gap:1rem;">
                                @csrf

                                {{-- Star Rating --}}
                                <div>
                                    <label style="color:#fff;display:block;margin-bottom:0.75rem;font-weight:600;font-size:0.9rem;">How would you rate this product? *</label>
                                    <div style="display:flex;gap:0.75rem;align-items:center;">
                                        <div id="starContainer" style="display:flex;gap:0.3rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label style="cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                                                    <input type="radio" name="rating" value="{{ $i }}" style="display:none;" onchange="updateStars()">
                                                    <span id="star{{ $i }}" style="color:#444;font-size:2rem;transition:color 0.2s;display:block;width:2rem;text-align:center;">★</span>
                                                </label>
                                            @endfor
                                        </div>
                                        <span id="ratingText" style="color:var(--muted);font-size:0.9rem;"></span>
                                    </div>
                                </div>

                                {{-- Review Comment --}}
                                <div>
                                    <label for="review_comment" style="color:#fff;display:block;margin-bottom:0.5rem;font-weight:600;font-size:0.9rem;">Your Review *</label>
                                    <textarea id="review_comment" name="comment" placeholder="Share your thoughts about this product..." style="width:100%;height:120px;resize:vertical;" required>{{ old('comment') }}</textarea>
                                </div>

                                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;padding:0.75rem 1.5rem;align-self:flex-start;">Submit Review</button>
                            </form>

                            <script>
                                function updateStars() {
                                    const checked = document.querySelector('input[name="rating"]:checked');
                                    const rating = checked ? parseInt(checked.value) : 0;
                                    const labels = ['Poor', 'Fair', 'Good', 'Excellent', 'Perfect'];
                                    
                                    for (let i = 1; i <= 5; i++) {
                                        const star = document.getElementById('star' + i);
                                        if (i <= rating) {
                                            star.style.color = 'var(--accent)';
                                        } else {
                                            star.style.color = '#444';
                                        }
                                    }
                                    
                                    const ratingText = document.getElementById('ratingText');
                                    ratingText.textContent = rating > 0 ? labels[rating - 1] : '';
                                }
                                
                                // Initialize stars on page load
                                document.querySelectorAll('input[name="rating"]').forEach(input => {
                                    input.addEventListener('change', updateStars);
                                    if (input.checked) {
                                        updateStars();
                                    }
                                });
                            </script>
                        </div>
                    @else
                        <div style="margin-bottom:2rem;padding:1rem;background:#141414;border-radius:12px;border:1px solid #4ADE8044;border-left:3px solid #4ADE80;">
                            <p style="color:#4ADE80;margin:0;font-weight:600;">✓ You have already reviewed this product.</p>
                        </div>
                    @endif
                @else
                    <div style="margin-bottom:2rem;padding:1rem;background:#141414;border-radius:12px;border:1px solid var(--border);">
                        <p style="color:#aaa;margin:0;">Only customers who have purchased this product can leave reviews.</p>
                    </div>
                @endif
            @endif
        @endauth

        {{-- Display Reviews --}}
        @if($reviews->count() > 0)
            <div style="display:grid;gap:1rem;">
                @foreach($reviews as $review)
                    <div style="padding:1.25rem;background:#141414;border-radius:10px;border:1px solid var(--border);">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;flex-wrap:wrap;gap:0.5rem;">
                            <div>
                                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.25rem;">
                                    <p style="color:var(--accent);font-weight:600;margin:0;font-size:0.95rem;">{{ $review->user?->name ?? 'Customer' }}</p>
                                    @if($review->is_verified_purchase)
                                        <span style="color:#4ADE80;font-size:0.8rem;font-weight:600;">✓ VERIFIED</span>
                                    @endif
                                </div>
                                <p style="color:#888;font-size:0.8rem;margin:0;">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                            <div style="color:var(--accent);font-size:1.25rem;letter-spacing:0.1em;">
                                @for($i = 0; $i < $review->rating; $i++)★@endfor
                            </div>
                        </div>
                        <p style="color:#aaa;margin:0;line-height:1.6;">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div style="padding:2rem;text-align:center;background:#141414;border-radius:10px;border:1px dashed var(--border);">
                <p style="color:#666;margin:0;font-size:0.95rem;">No reviews yet. Be the first to share your experience!</p>
            </div>
        @endif
    </div>

</div>

<script>
    function changeImage(thumb) {
        document.getElementById('mainImage').src = thumb.src;
        document.querySelectorAll('[onclick="changeImage(this)"]').forEach(img => {
            img.style.borderColor = 'var(--border)';
        });
        thumb.style.borderColor = 'var(--accent)';
    }
</script>

@endsection