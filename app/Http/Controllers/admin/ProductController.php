<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


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

            if ( !empty($request->image_id)){
                $productImageInfo = TempImage::find($request->image_id);
                $extArray = explode('.', $productImageInfo->name);
                $ext = last($extArray);

                $productImage = new ProductImage();
                $productImage->product_id = $product->id;
                $productImage->image = 'NULL';
                $productImage->save();

                $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                $productImage->image = $imageName;
                $productImage->save();

                $itemPath = public_path(). '/temp/item/' . $imageName;
                File::move(public_path('/temp') . '/' . $productImageInfo->name, $itemPath);
                $productImageInfo->delete();

            }

            
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


            if ( !empty($request->image_id)){
                $productImageInfo = TempImage::find($request->image_id);
                $extArray = explode('.', $productImageInfo->name);
                $ext = last($extArray);

                $productImage = new ProductImage();
                $productImage->product_id = $product->id;
                $productImage->image = 'NULL';
                $productImage->save();

                $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                $productImage->image = $imageName;
                $productImage->save();

                $itemPath = public_path(). '/temp/item/' . $imageName;
                File::move(public_path('/temp') . '/' . $productImageInfo->name, $itemPath);
                $productImageInfo->delete();

                $product->images()->save($productImage);
                $product->images->except($productImage->id)->each->delete();

            }
        
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

        $productImage = ProductImage::where('product_id',$id)->first();
        if(!empty($productImage)){  
            File::delete(public_path() . '/temp/item/' . $productImage->image);

            $productImage->delete();
        }

        $products->delete();

        return response()->json([
            'status' => true,
            'message' => 'Products deleted successfully!',
        ]);
        
    }  
}
