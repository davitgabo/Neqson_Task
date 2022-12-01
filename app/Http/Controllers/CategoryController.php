<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    /**
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($page)
    {
        // check which category layer is being requested
        switch($page) {
            case 'category':
                $categories = Category::all();
                break;
            case 'subcategory':
                $categories = Subcategory::all();
                break;
            case 'subsubcategory':
                $categories = Subsubcategory::all();
                break;
            default:
                return response()->json(['no records']);
        }

        return response()->json($categories);
    }

    /**
     * @param Request $request
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $page)
    {
        $table = substr($page,0,-1).'ies';
        // validate the request
        $request->validate([
            'name'=> "required|unique:$table",
        ]);

        // check which category layer is stored and create a record in the relevant table.
        switch($page) {
            case 'category':
                return response()->json(
                    Category::create([
                    'name' => $request->name
                ])
                );

            case 'subcategory':
                return response()->json(
                    Subcategory::create([
                    'name' => $request->name
                ]));

            case 'subsubcategory':
                return response()->json(
                 Subsubcategory::create([
                    'name' => $request->name
                ]));

            default:
                return response()->json('wrong uri');
        }


    }

    /**
     * update the category name
     *
     * @param Request $request
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $page)
    {
        // get the table name from the requested page
        $db = substr($page,0,-1).'ies';

        // validate the request
        $request->validate([
            "id" => "required|numeric",
            "name" => "required|unique:$db"
        ]);

        if (DB::table($db)->where('id', $request->id)->update(['name'=>$request->name])){
            return response()->json(["$page updated successfully"]);
        } else {
            return response()->json(["$page update failed"]);
        }


    }

    /**
     * delete the category record
     *
     * @param Request $request
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($page, $id)
    {
        if (DB::table($page)->where('id', $id)->delete()){
            return response()->json(["$page deleted successfully"]);
        } else {
            return response()->json(["$page delete failed"]);
        }
    }
}
