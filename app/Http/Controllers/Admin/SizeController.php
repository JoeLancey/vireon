<?php
namespace App\Http\Controllers\Admin;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('category')->orderBy('name')->paginate(30);
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        $categories = ['clothing', 'footwear', 'accessories'];
        return view('admin.sizes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'value' => ['required', 'string', 'max:50'],
            'category' => ['required', 'in:clothing,footwear,accessories'],
        ]);

        $exists = Size::where('value', $validated['value'])
            ->where('category', $validated['category'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'This size already exists for this category.');
        }

        Size::create($validated);
        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        $categories = ['clothing', 'footwear', 'accessories'];
        return view('admin.sizes.edit', compact('size', 'categories'));
    }

    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'value' => ['required', 'string', 'max:50'],
            'category' => ['required', 'in:clothing,footwear,accessories'],
        ]);

        $exists = Size::where('value', $validated['value'])
            ->where('category', $validated['category'])
            ->where('id', '!=', $size->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This size already exists for this category.');
        }

        $size->update($validated);
        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return back()->with('success', 'Size deleted successfully.');
    }
}
