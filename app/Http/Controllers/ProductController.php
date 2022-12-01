<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // get products and product related categories
        $products = DB::table('products')
            ->select('products.id as id',
                'products.name as name',
                'products.description as description',
                'products.image as image',
                'categories.name as category',
                'subcategories.name as subcategory',
                'subsubcategories.name as subsubcategory')
            ->leftJoin('categories','products.category_id','=','categories.id')
            ->leftJoin('subcategories','products.subcategory_id','=','subcategories.id')
            ->leftJoin('subsubcategories','products.subsubcategory_id','=','subsubcategories.id')
            ->get();

        return response()->json($products);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name'=> 'required',
            'description' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'subsubcategory' => 'required',
            'image' => 'required|image',
        ]);

        // create unique image name
        $imageName = time().'.'.$request->image->extension();

        // save uploaded image to public folder
        $request->image->move(public_path('images'), $imageName);

        // save uploaded product to the products table
        Product::create(['name' => $request->name,
                         'description' => $request->description,
                         'category_id' => $request->category,
                         'subcategory_id' => $request->subcategory,
                         'subsubcategory_id' => $request->subsubcategory,
                         'image' => $imageName]
        );

        return to_route('product');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editPath(Request $request)
    {
        // validate request
        $request->validate([
            'id' =>'required|numeric',
            'category' => 'required',
            'subcategory' => 'required',
            'subsubcategory' => 'required',
        ]);


        // get the product
        $product = Product::find($request->id);

        // edit product path
        $product->category_id = $request->category;
        $product->subcategory_id = $request->subcategory;
        $product->subsubcategory_id = $request->subsubcategory;

        // save the record
        $product->save();

        return to_route('product');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        //validate request
        $request->validate([
            'id'=>'required|numeric',
        ]);

        //get the product by id
        $product = Product::find($request->id);

        //delete product
        if ($product) {
            $product->delete();
        }

        // delete image from public folder
        if (file_exists(public_path('/images/' . $request->image))) {
            unlink(public_path('/images/' . $request->image));
        }

        return to_route('product');
    }
}
