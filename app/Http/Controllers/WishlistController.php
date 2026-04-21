<?php
namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = auth()->user()->wishedProducts()->paginate(12);
        return view('wishlist.index', compact('wishlist'));
    }

    public function add(Product $product)
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Already in your wishlist.');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Added to wishlist!');
    }

    public function remove(Product $product)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Removed from wishlist.');
    }

    public function toggle(Product $product)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            if (request()->expectsJson()) {
                return response()->json(['status' => 'removed']);
            }

            return back()->with('success', 'Removed from wishlist.');
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);

            if (request()->expectsJson()) {
                return response()->json(['status' => 'added']);
            }

            return back()->with('success', 'Added to wishlist!');
        }
    }
}
