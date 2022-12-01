<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * get categories that are not empty.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $categories = DB::table('products')
            ->select('categories.name as name','categories.id as id')
            ->leftJoin('categories','products.category_id','=','categories.id')
            ->distinct()->get();

        return response()->json($categories);
    }

    /**
     * get subcategories that are not empty
     *
     * @param $name
     * @return JsonResponse
     */
    public function show($name)
    {
        $subcategories = DB::table('products')
            ->select('subcategories.name as name','subcategories.id as id')
            ->leftJoin('categories','products.category_id','=','categories.id')
            ->leftJoin('subcategories','products.subcategory_id','=','subcategories.id')
            ->where('categories.name','=',$name)
            ->distinct()->get();

        return response()->json($subcategories);
    }

    /**
     * get the last layer of subcategories that are not empty
     *
     * @param $category
     * @param $subcategory
     * @return JsonResponse
     */
    public function lastLayer($category, $subcategory)
    {
        $subcategories = DB::table('products')
            ->select('subsubcategories.name as name','subsubcategories.id as id')
            ->leftJoin('categories','products.category_id','=','categories.id')
            ->leftJoin('subcategories','products.subcategory_id','=','subcategories.id')
            ->leftJoin('subsubcategories','products.subsubcategory_id','=','subsubcategories.id')
            ->where('categories.name','=',$category)
            ->where('subcategories.name','=',$subcategory)
            ->distinct()->get();

        return response()->json($subcategories);
    }

    /**
     * get products from specified categories
     *
     * @param $main_category
     * @param $category
     * @param $subcategory
     * @return JsonResponse
     */
    public function products($main_category, $category, $subcategory)
    {
        $products = DB::table('products')
            ->select('products.name','products.id','products.image')
            ->leftJoin('categories','products.category_id','=','categories.id')
            ->leftJoin('subcategories','products.subcategory_id','=','subcategories.id')
            ->leftJoin('subsubcategories','products.subsubcategory_id','=','subsubcategories.id')
            ->where('categories.name','=',$main_category)
            ->where('subcategories.name','=',$category)
            ->where('subsubcategories.name','=',$subcategory)
            ->get();

        return response()->json($products);
    }

    /**
     * get product images
     *
     * @param $id
     * @return JsonResponse
     */
    public function gallery($id)
    {
        $images = Image::Where('product_id',$id)->get();

        return response()->json($images);
    }
}
