<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Validator;

use App\Models\Product;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::all();
        return response([
            'products' => ProductResource::collection($products)
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'sku' => 'required|max:255',
            'price' => 'required|max:255',
        ]);

        if($validator->fails()){
            return response([
                'error' => $validator->errors(), 
                'Validation Error'
            ], 422);
        }

        $product = Product::create($data);

        return response([
            'product' => new ProductResource($product), 
            'message' => 'Product created successfully'
        ], 201);
    }

    public function show(Product $product)
    {
        return response([
            'product' => new ProductResource($product)
        ], 200);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'sku' => 'required|max:255',
            'upc' => 'required|max:255',
        ]);

        if($validator->fails()){
            return response([
                'error' => $validator->errors(), 
                'Validation Error'
            ], 422);
        }

        $product->update($data);

        return response([
            'product' => new ProductResource($product), 
            'message' => 'Product updated successfully'
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response([
            'message' => 'Product deleted successfully'
        ], 200);
    }
    
}
