<?php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $this->middleware('auth');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        // Check if user already reviewed this product
        $existing = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        // Check if user has purchased this product
        $isPurchased = auth()->user()->orders()
            ->whereHas('items', fn($query) => $query->where('product_id', $product->id))
            ->exists();

        Review::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_verified_purchase' => $isPurchased,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Review submitted! Awaiting admin approval.');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $product = $review->product;
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
