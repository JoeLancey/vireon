<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            $validated['logo'] = $this->storePublicImage($request->file('logo'), 'uploads/brands');
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
            $this->deleteImage($brand->logo);
            $validated['logo'] = $this->storePublicImage($request->file('logo'), 'uploads/brands');
        }

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand) {
        $this->deleteImage($brand->logo);
        $name = $brand->name;
        $brand->delete();
        return redirect()->route('admin.brands.index')
            ->with('success', '"' . $name . '" deleted successfully!');
    }

    private function storePublicImage(UploadedFile $file, string $directory): string
    {
        $directoryPath = public_path($directory);

        if (! File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($directoryPath, $fileName);

        return trim($directory, '/') . '/' . $fileName;
    }

    private function deleteImage(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $normalizedPath = ltrim($path, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, strlen('storage/'));
            \Illuminate\Support\Facades\Storage::disk('public')->delete($normalizedPath);
            return;
        }

        $fullPath = public_path($normalizedPath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}