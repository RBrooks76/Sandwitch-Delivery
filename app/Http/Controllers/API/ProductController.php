<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Products;
use App\ProductsCategory;
use App\ProductsFeature;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
    }

    public function resetProductAvatar(Request $request, $product_id)
    {
        $product = Products::where('id', $product_id)->first();
        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }
        if (!$product->content) {
            return response()->json(['error' => 'Already reset'], 419);
        }
        $filePath = 'upload/avatar/product_' . $product_id . '_avatar.png';
        Storage::disk('upload')->put($filePath, base64_decode($product->content));
        $product->update(['avatar' => $filePath, 'content' => null]);

        return response()->json($product);
    }

    public function deleteProduct(Request $request, $product_id)
    {
        $product = Products::where('id', $product_id)->first();
        if (!$product) {
            return response()->json(['error' => 'Not found']);
        }
        if (file_exists(public_path($product->avatar))) {
            unlink(public_path($product->avatar));
        }
        ProductsFeature::where('products_id', $product->id)->delete();
        ProductsCategory::where('products_id', $product->id)->delete();
        $product->delete();

        return response()->json(['success' => 'Deleted successfully']);
    }
}
