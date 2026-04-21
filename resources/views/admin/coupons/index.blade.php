@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto max-w-6xl px-4">
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">← Admin Dashboard</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Manage Coupons</h1>
                <a href="{{ route('admin.coupons.create') }}" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">+ New Coupon</a>
            </div>

            @if($coupons->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3">Code</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Value</th>
                                <th class="px-4 py-3">Used</th>
                                <th class="px-4 py-3">Max Uses</th>
                                <th class="px-4 py-3">Expires</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono font-bold">{{ $coupon->code }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($coupon->discount_type) }}</td>
                                    <td class="px-4 py-3">
                                        @if($coupon->discount_type === 'percentage')
                                            {{ $coupon->discount_value }}%
                                        @else
                                            ₱{{ number_format($coupon->discount_value, 2) }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $coupon->current_uses }}/{{ $coupon->max_uses }}</td>
                                    <td class="px-4 py-3">{{ $coupon->max_uses }}</td>
                                    <td class="px-4 py-3">{{ optional($coupon->expires_at)->format('M d, Y') ?? 'Never' }}</td>
                                    <td class="px-4 py-3">
                                        @if($coupon->is_active)
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">Active</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onclick="return confirm('Delete this coupon?');">
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
                    {{ $coupons->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No coupons yet. <a href="{{ route('admin.coupons.create') }}" class="text-blue-500 hover:underline">Create one</a></p>
            @endif
        </div>
    </div>
</div>
@endsection
