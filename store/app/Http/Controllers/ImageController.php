<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use Storage;
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
        $product_id = $request->input('product_id');
        
        $uploadedFile = $request->file('image');
        $originalFileName = $uploadedFile->getClientOriginalName();
        $uploadedFileName = $originalFileName;
        $uploadedFile->move(public_path('images'), $uploadedFileName);
        // Create Image
        $image = Image::create([
            'product_id' => $product_id,
            'image' => $uploadedFileName,
        ]);

        // Associate Image with Product
       
        
       

        return response()->json(['message' => 'Image created and associated with the product', 'image_id' => $image->id]);
    }
     public function update(Request $request, $imageId)
    {
        // Validation logic here
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product_id = $request->input('product_id');
        $uploadedFile = $request->file('image');

        // Retrieve the existing image record
        $image = Image::findOrFail($imageId);

        // If a new image is provided, update the file and original name
        if ($uploadedFile) {
            $originalFileName = $uploadedFile->getClientOriginalName();
            $uploadedFileName = $originalFileName;
            $uploadedFile->move(public_path('images'), $uploadedFileName);

            // Update Image record
            $image->update([
                'product_id' => $product_id,
                'image' => $uploadedFileName
                
            ]);
        } else {
            // If no new image is provided, only update the product_id
            $image->update([
                'product_id' => $product_id,
            ]);
        }

        return response()->json(['message' => 'Image updated successfully', 'image_id' => $image->id]);
    }

    public function destory($imageId)
    {
        // Retrieve the existing image record
        $image = Image::findOrFail($imageId);

        // Delete the file from the public/images directory
        Storage::delete('images/' . $image->image);

        // Delete the image record from the database
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully', 'image_id' => $imageId]);
    }
}
