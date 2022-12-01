<?php

namespace App\Http\Controllers;


use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return view('admin.images',['title'=>Product::find($id)->name,
                                         'images'=>Image::where('product_id',$id)->get(),
                                         'id'=>$id]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'id' => 'required|numeric',
            'image' => 'required|image'
        ]);

        // create unique image name
        $imageName = time().'.'.$request->image->extension();

        // save uploaded image to public folder
        $request->image->move(public_path('images'), $imageName);

        // save image name to the products table
        Image::create([
                'source' => $imageName,
                'product_id' => $request->id
            ]);

        return to_route('images',['id'=>$request->id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        // validate request
        $request->validate([
            'id' => 'required|numeric',
            'image' => 'required'
        ]);

        //get the image by id
        $image = Image::find($request->id);

        // delete the image from table
        if ($image) {
            $image->delete();
        }

        // delete the image from public folder
        if (file_exists(public_path('/images/' . $request->image))) {
            unlink(public_path('/images/' . $request->image));
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(Request $request)
    {
        // validate request
        $request->validate([
            'id' => 'required|numeric',
            'product_id'=>'required',
        ]);

        // get the product by id
        $product = Product::find($request->product_id);

        // get the image by id
        $image = Image::find($request->id);

        // swap the image and product image sources
        $temp = $product->image;
        $product->image = $image->source;
        $image->source = $temp;

        // save records to table
        $product->save();
        $image->save();

        return redirect()->back();
    }
}
