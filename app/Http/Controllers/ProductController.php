<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpsertProductRequest;

class ProductController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index() : View
    {
        return view('Products.index',[
            'products' => Product::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create() : View
    {
        return view('Products.create',[
            'categories' => ProductCategory::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UpsertProductRequest  $request
     * @return RedirectResponse
     */
    public function store(UpsertProductRequest $request) : RedirectResponse
    {
        $product = new Product($request->validated());
        if($request->hasFile('image'))
        {
            $product->image_path = $request->file('image')->store('products');
        }
        $product->save();
        return redirect(route('products.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return View
     */
    public function show(Product $product) : View
    {
        return view('Products.show',[
            'product' => $product,
            'categories' => ProductCategory::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $product
     * @return View
     */
    public function edit(Product $product) : View
    {
        return view('Products.edit',[
            'product' => $product,
            'categories' => ProductCategory::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function update(UpsertProductRequest $request, Product $product) : RedirectResponse
    {
        $product->fill($request->validated());
        if($request->hasFile('image'))
        {
            $product->image_path = $request->file('image')->store('products');
        }
        $product->save();
        return redirect(route('products.index'));
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     * @throws /Exception
     */
    public function destroy(Product $product) : JsonResponse
    {
        try
        {
            $product->delete();
            return response() -> json([
                'status' => 'success'
            ]);
        }
        catch(Exception $e)
        {
            return response() -> json([
                'status' => 'error',
                'message' => 'Error occured!'
            ])->setStatusCode(500);
        }
        $product->delete();
    }
}
