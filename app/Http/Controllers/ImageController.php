<?php

namespace App\Http\Controllers;


use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'product_id' => 'required|numeric|exists:products,id',
            'image' => 'required|image'
        ]);

        // create unique image name
        $imageName = time().'.'.$request->image->extension();

        // save uploaded image to public folder
        $request->image->move(public_path('images'), $imageName);

        // save image name to the products table
        return response()->json(Image::create([
                'source' => $imageName,
                'product_id' => $request->product_id
        ]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        //get the image by id
        $image = Image::find($id);

        // delete the image from table
        if ($image) {
            if (file_exists(public_path('/images/' . $image->image))) {
                unlink(public_path('/images/' . $image->image));
            }
            return response()->json($image->delete());
        } else {
            return response()->json('couldn\'t find image');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change(Request $request)
    {
        // validate request
        $request->validate([
            'id' => 'required|numeric',
            'product_id'=>'required|exists:products,id',
        ]);

        // get the product by id
        $product = Product::find($request->product_id);

        // get the image by id
        $image = Image::find($request->id);

        if ($product && $image) {
            // swap the image and product image sources
            $temp = $product->image;
            $product->image = $image->source;
            $image->source = $temp;

            // save records to table
            $product->save();
            $image->save();
            return response()->json('image changed');
        } else {
            return response()->json('image change failed');
        }
    }
}
