@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto max-w-6xl px-4">
        <a href="{{ route('cart.index') }}" class="text-blue-500 hover:underline mb-6 inline-block">← Back to Cart</a>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-8">My Wishlist</h1>

            @if($wishlist->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wishlist as $product)
                        <div class="bg-gray-100 rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-bold text-lg">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $product->brand?->name ?? 'No Brand' }}</p>
                                <p class="text-lg font-bold text-green-600 mb-4">₱{{ number_format($product->price, 2) }}</p>
                                <div class="flex gap-2">
                                    <form action="{{ route('cart.store', $product) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Add to Cart</button>
                                    </form>
                                    <form action="{{ route('wishlist.remove', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $wishlist->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg mb-4">Your wishlist is empty.</p>
                    <a href="{{ route('products.index') }}" class="text-blue-500 hover:underline">Continue Shopping</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
