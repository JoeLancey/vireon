@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 admin-sizes-page">
    <div class="container mx-auto max-w-6xl px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">← Admin Dashboard</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 admin-sizes-card">
            <div class="flex justify-between items-center mb-6 admin-sizes-header">
                <h1 class="text-3xl font-bold">Manage Sizes</h1>
                <a href="{{ route('admin.sizes.create') }}" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">+ New Size</a>
            </div>

            @if($sizes->count() > 0)
                <div class="overflow-x-auto admin-sizes-table-wrap">
                    <table class="w-full text-left admin-sizes-table">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Value</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sizes as $size)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-bold">{{ $size->name }}</td>
                                    <td class="px-4 py-3 font-mono">{{ $size->value }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $size->category }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.sizes.edit', $size) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
                                        <form action="{{ route('admin.sizes.destroy', $size) }}" method="POST" class="inline" onclick="return confirm('Delete this size?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $sizes->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No sizes yet. <a href="{{ route('admin.sizes.create') }}" class="text-blue-500 hover:underline">Create one</a></p>
            @endif
        </div>
    </div>
</div>
@endsection
