@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto max-w-2xl px-4">
        <div class="mb-6">
            <a href="{{ route('admin.sizes.index') }}" class="text-blue-500 hover:underline">← Back to Sizes</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-8">Edit Size</h1>

            <form action="{{ route('admin.sizes.update', $size) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Size Name</label>
                    <input type="text" name="name" value="{{ old('name', $size->name) }}" class="w-full border rounded px-4 py-2 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Size Value</label>
                    <input type="text" name="value" value="{{ old('value', $size->value) }}" class="w-full border rounded px-4 py-2 @error('value') border-red-500 @enderror" required>
                    @error('value') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Category</label>
                    <select name="category" class="w-full border rounded px-4 py-2" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('category', $size->category) === $cat)>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Update Size</button>
                    <a href="{{ route('admin.sizes.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
