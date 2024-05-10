<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->busqueda;
        $producto1= Product::where('title','LIKE','%'.$busqueda.'%')
        ->paginate(10);
        $data= ['title'=> $producto1, ];



        $products = Product::orderBy('id', 'desc')->get();
        $total = Product::count();
       $products= Product::paginate(10);
        return view('admin.product.home', compact('products'));
    }

    public function create()
    {
        return view('admin.product.create');
    }

    public function save(Request $request)
    {   
        $validation = $request->validate([
            'title' => 'required',
            'category' => 'required',
            'price' => 'required',
        ]);
        $data = Product::create($validation);
        if ($data) {
            session()->flash('success', 'Product Add Succesfully');
            return redirect(route('admin/products'));
        } else{
            session()->flash('error', 'Some problem occure');
            return redirect(route('admin.products/create'));
        }
    }

    public function edit($id)
    {
        $products = Product::findOrFail($id);
        return view('admin.product.update', compact('products'));
    }

    public function delete($id)
    {
        $products = Product::findOrFail($id)->delete();
        if ($products) {
            session()->flash('success', 'Product Deleted Succesfully');
            return redirect(route('admin/products'));
        } else{
            session()->flash('error', 'Product Not Delete Succesfully');
            return redirect(route('admin.products'));
        }
    }

    public function update(Request $request, $id)
    {
        $products = Product::findOrFail($id);
        $title = $request->title;
        $category = $request->category;
        $price = $request->price;

        $products->title = $title;
        $products->category = $category;
        $products->price = $price;
        $data = $products->save();
        if ($data) {
            session()->flash('success', 'Product Update Succesfully');
            return redirect(route('admin/products'));
        } else{
            session()->flash('error', 'Some problem occure');
            return redirect(route('admin.products/update'));
        }
    }

    /*public function buscar(Request $request)
    {
        $busqueda = $request->input('query');
        $resultados = Product::where('title', 'LIKE', "%$busqueda%")->paginate(10);
        return view('admin/product/home', compact('resultados'));
    }*/
}
