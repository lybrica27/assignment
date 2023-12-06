<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;


class CategoryController extends Controller
{
    public function index(){
        $categories = Category::get();
        $data['categories'] = $categories;

        return view('admin.category.list', compact('categories'));
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:categories',
                
            ]
        );

        if ($validator->passes()){

            $category = new Category();
            $category->name = $request->name;
            $category->publish = $request->publish;
            $category->save();

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);   
                $category->image = $tempImage->name;
                $category->save();
            }

            $request->session()->flash('success','Category added successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully!',
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request){
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request){

        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found',
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:categories,name,'.$category->id.',id',
            ]
        );

        if ($validator->passes()){

            $category->name = $request->name;
            $category->publish = $request->publish;
            $category->save();

            $oldImage = $category->image;

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);   
                $category->image = $tempImage->name;
                $category->save();

                File::delete(public_path() . '/temp/' . $oldImage);
            }

            $request->session()->flash('success','Category updated successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully!',
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request){
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        File::delete(public_path() . '/temp/' . $category->image );
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => "Category deleted successfully!",
        ]);
    }

}
