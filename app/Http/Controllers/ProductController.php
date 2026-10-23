<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $datum = Product::latest()->paginate(5);
        return view('products.index', compact('datum'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'detail'=>'required',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();
    
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
      
        Product::create($input);
        return  redirect()->route('products.index')->with('success', "created successfully");
    }

    public function show(Product $product){
        return view('products.show', compact('product'));
    }
    public function edit(Product $product){
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product){
            $request->validate([
                'name' => 'required',
                'detail' => 'required'
            ]);
        
            $input = $request->all();
        
            if ($image = $request->file('image')) {
                $destinationPath = 'images/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $input['image'] = "$profileImage";
            }else{
                unset($input['image']);
            }
                
            $product->update($input);
          
            return redirect()->route('products.index')
                            ->with('success','Product has been updated successfully.');
        }

        public function destroy(Products $product){
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product has been deleted successfully');
        }
}
