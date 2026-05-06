<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('images:migrate-public', function () {
    $migratePath = function (string $path, string $targetDirectory): ?string {
        $path = ltrim($path, '/');

        if ($path === '' || str_starts_with($path, 'uploads/')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        $targetDirectoryPath = public_path($targetDirectory);

        if (! File::exists($targetDirectoryPath)) {
            File::makeDirectory($targetDirectoryPath, 0755, true);
        }

        $fileName = basename($path);
        $targetPath = $targetDirectoryPath . DIRECTORY_SEPARATOR . $fileName;

        if (! File::exists($targetPath)) {
            File::copy(Storage::disk('public')->path($path), $targetPath);
        }

        return trim($targetDirectory, '/') . '/' . $fileName;
    };

    $brandsUpdated = 0;
    Brand::whereNotNull('logo')->chunkById(100, function ($brands) use (&$brandsUpdated, $migratePath) {
        foreach ($brands as $brand) {
            $newPath = $migratePath($brand->logo, 'uploads/brands');

            if ($newPath && $newPath !== $brand->logo) {
                $brand->update(['logo' => $newPath]);
                $brandsUpdated++;
            }
        }
    });

    $productsUpdated = 0;
    Product::whereNotNull('image')->chunkById(100, function ($products) use (&$productsUpdated, $migratePath) {
        foreach ($products as $product) {
            $newPath = $migratePath($product->image, 'uploads/products');

            if ($newPath && $newPath !== $product->image) {
                $product->update(['image' => $newPath]);
                $productsUpdated++;
            }
        }
    });

    $additionalUpdated = 0;
    ProductImage::whereNotNull('image_path')->chunkById(100, function ($images) use (&$additionalUpdated, $migratePath) {
        foreach ($images as $image) {
            $newPath = $migratePath($image->image_path, 'uploads/products');

            if ($newPath && $newPath !== $image->image_path) {
                $image->update(['image_path' => $newPath]);
                $additionalUpdated++;
            }
        }
    });

    $this->info('Migrated ' . $brandsUpdated . ' brand logos, ' . $productsUpdated . ' product images, and ' . $additionalUpdated . ' additional images to public/uploads.');
})->purpose('Copy stored brand and product images into public/uploads and update database paths');
