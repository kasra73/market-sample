<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductSearchRequest;
use App\Product;
use App\Services\Search\SearchServiceInterface;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        $categories = Category::all();
        return response()->json(compact('categories'));
    }

    public function search(ProductSearchRequest $request, SearchServiceInterface $searchService)
    {
        $query = $request->input('query', null);
        $per_page = $request->input('per_page', 15);
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 15);
        $category = $request->input('category', null);
        $products = $searchService->search($query, $category, $per_page, $page);
        return response()->json(compact('products'));
    }
}
