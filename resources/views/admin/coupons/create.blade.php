@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto max-w-2xl px-4">
        <div class="mb-6">
            <a href="{{ route('admin.coupons.index') }}" class="text-blue-500 hover:underline">← Back to Coupons</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-8">Create Coupon</h1>

            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="w-full border rounded px-4 py-2 @error('code') border-red-500 @enderror" placeholder="SUMMER20" required>
                    @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="w-full border rounded px-4 py-2" placeholder="Summer Sale 20% Off">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Discount Type</label>
                        <select name="discount_type" class="w-full border rounded px-4 py-2" required>
                            <option value="percentage" @selected(old('discount_type') === 'percentage')>Percentage (%)</option>
                            <option value="fixed" @selected(old('discount_type') === 'fixed')>Fixed Amount (₱)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Discount Value</label>
                        <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}" class="w-full border rounded px-4 py-2 @error('discount_value') border-red-500 @enderror" required>
                        @error('discount_value') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Minimum Order Amount (₱)</label>
                    <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" class="w-full border rounded px-4 py-2">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Max Uses</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', 1) }}" class="w-full border rounded px-4 py-2 @error('max_uses') border-red-500 @enderror" required>
                    @error('max_uses') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Expires At</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full border rounded px-4 py-2">
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Create Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
