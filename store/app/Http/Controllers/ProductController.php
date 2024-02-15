<?php

namespace App\Http\Controllers;
use App\Notifications\ProductCreatedNotification;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Item;
use App\Models\Image;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use App\Events\ProductCreated;
use Illuminate\Support\Facades\Notification;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['items', 'images'])->get();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['items', 'images'])->find($id);

        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function store(Request $request)
    {
        // Validation logic here

        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        foreach ($request->input('items') as $itemData) {
            $product->items()->create($itemData);
        }
       Mail::to('najus777@gmail.com')->send(new SendMail());
 
        

        return response()->json(['message' => 'Product added successfully', 'id' => $product->id]);
    }

    public function update(Request $request, $id)
    {
        // Validation logic here

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        $itemsData = $request->input('items', []);

        foreach ($itemsData as $item) {
            // Assuming each item has 'size', 'color', 'status', 'sku', and 'price' attributes

            // Check for required item properties
            if (empty($item['size']) || empty($item['color']) || empty($item['status']) || empty($item['sku']) || empty($item['price'])) {
                return response()->json(['error' => 'Invalid item properties'], 400);
            }

            // Update or create item based on 'id' presence
            if (isset($item['id'])) {
                $existingItem = Item::find($item['id']);
    
                if ($existingItem) {
                    $existingItem->update([
                        'size' => $item['size'],
                        'color' => $item['color'],
                        'status' => $item['status'],
                        'sku' => $item['sku'],
                        'price' => $item['price'],
                    ]);
                } else {
                    return response()->json(['error' => 'Item not found'], 404);
                }
            } else {
                // Create a new item for the product if 'id' is not provided
                Item::create([
                    'product_id' => $product->id,
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'status' => $item['status'],
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                ]);
            }

        }

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully', 'id' => $id]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
}
