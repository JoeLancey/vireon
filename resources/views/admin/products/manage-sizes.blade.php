@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto max-w-2xl px-4">
        <div class="mb-6">
            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:underline">← Back to Product</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-8">Manage Sizes - {{ $product->name }}</h1>

            <form action="{{ route('admin.products.update-sizes', $product) }}" method="POST">
                @csrf
                @method('PATCH')

                @foreach($allSizes as $category => $sizes)
                    <div class="mb-8 pb-8 border-b">
                        <h2 class="text-xl font-bold mb-4 capitalize">{{ $category }}</h2>
                        <div class="space-y-3">
                            @foreach($sizes as $size)
                                <label class="flex items-center">
                                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}" 
                                        @checked($product->sizes->contains($size))
                                        class="w-4 h-4 mr-2">
                                    <span>{{ $size->name }} ({{ $size->value }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Update Sizes</button>
                    <a href="{{ route('admin.products.edit', $product) }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
