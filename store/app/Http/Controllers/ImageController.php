<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller

{

    public function index()
    {
        $images = Image::all();
        return response()->json($images);
    }

    public function store(Request $request)
    {
        // Validation logic here
        $request->validate([
            'product_id' => 'required|exists:products,id', // Validate that the product exists
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // File upload logic here
        $uploadedFile = $request->file('image');
        $uploadedFileName = time().'.'.$uploadedFile->getClientOriginalExtension();
        $path = $uploadedFile->storeAs('images', $uploadedFileName, 'public');

        // Create Image
        $image = Image::create([
            'image' => $uploadedFileName,
        ]);

        // Associate Image with Product
        $product = Product::find($request->input('product_id'));
        
        if (!$product) {
            // Handle product not found
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->image()->associate($image);
        $product->save();

        return response()->json(['message' => 'Image created and associated with the product', 'image_id' => $image->id]);
    }
    public function update(Request $request, $imageId)
    {
        // Validation logic here

        $image = Image::find($imageId);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // File upload logic here

        $image->update([
            'image' => $uploadedFileName,
        ]);

        return response()->json(['message' => 'Image updated successfully', 'image_id' => $imageId]);
    }

    public function destroy($imageId)
    {
        $image = Image::find($imageId);

        if ($image) {
            $image->delete();
            return response()->json(['message' => 'Image deleted successfully', 'image_id' => $imageId]);
        } else {
            return response()->json(['error' => 'Image not found'], 404);
        }
    }
}
