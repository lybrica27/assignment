<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::get();
        $data['products'] = $products;

        return view('admin.product.list', $data);
    }

    public function create(){
        $data = [];
        $categories =  Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;

        return view('admin.product.create', $data);
    }

    public function store(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category' => 'required',
                'price' => 'required|numeric',
                'condition' => 'required|in:new,used,good-secondhand',
                'type' => 'required|in:sell,buy,exchange',
                'publish' => 'required|in:yes,no',
            ]
        );

        if ($validator->passes()){

            $product = new Product();
            $product->name = $request->name;
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->condition = $request->condition;
            $product->type = $request->type;
            $product->publish = $request->publish;
            $product->owner_name = $request->owner_name;
            $product->owner_contact = $request->owner_contact;
            $product->owner_address = $request->owner_address;
            $product->save();

            
            $request->session()->flash('success','Added successfully');
            return response()->json([
                'status' => true,
                'message' => 'New item is added successfully',
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){
        $products = Product::find($id);
        if (empty($products)){
            return redirect()->route('products.index')->with('error','Product not found');
        }

        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['products'] = $products; 

        return view('admin.product.edit', $data);
    }

    public function update($id, Request $request){
        $product = Product::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category' => 'required',
                'price' => 'required|numeric',
                'condition' => 'required|in:new,used,good-secondhand',
                'type' => 'required|in:sell,buy,exchange',
                'publish' => 'required|in:yes,no',
            ]
        );

        if ($validator->passes()){

            $product->name = $request->name;
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->condition = $request->condition;
            $product->type = $request->type;
            $product->publish = $request->publish;
            $product->owner_name = $request->owner_name;
            $product->owner_contact = $request->owner_contact;
            $product->owner_address = $request->owner_address;
            $product->save();

            
            $request->session()->flash('success','Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'New item updated successfully',
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request){
        $products = Product::find($id);

        if(empty($products)){
            $request->session()->flash('error','product not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }
        $products->delete();

        return response()->json([
            'status' => true,
            'message' => 'Products deleted successfully!',
        ]);
        
    }  
}
