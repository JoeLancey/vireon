<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller {
    public function index() {
        $brands = Brand::withCount('products')->latest()->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name'         => 'required|string|max:255|unique:brands,name',
            'accent_color' => 'required|string|max:7',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand added successfully!');
    }

    public function edit(Brand $brand) {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand) {
        $validated = $request->validate([
            'name'         => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'accent_color' => 'required|string|max:7',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($brand->logo) Storage::disk('public')->delete($brand->logo);
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand) {
        if ($brand->logo) Storage::disk('public')->delete($brand->logo);
        $name = $brand->name;
        $brand->delete();
        return redirect()->route('admin.brands.index')
            ->with('success', '"' . $name . '" deleted successfully!');
    }
}