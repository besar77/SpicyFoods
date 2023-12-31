<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => 'required|max:255', 'price' => 'required|numeric', 'product_id' => 'required|integer'
            ],
            [
                'name.required' => 'Product option name is required',
                'name.max' => 'Product option max length is 255',
                'price.required' => 'Product option price is required',
                'price.numeric' => 'Product option price should be numeric',
            ]
        );

        $option = new ProductOption();
        $option->name = $request->input('name');
        $option->price = $request->input('price');
        $option->product_id = $request->input('product_id');
        $option->save();
        toastr()->success('Created Successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $option = ProductOption::findOrFail($id);
            $option->delete();
            return response()->json(['status' => 'success', 'message' => 'Slider deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
