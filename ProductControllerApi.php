<?php

namespace App\Http\Controllers;
use App\Models\CategoryModel;

use Illuminate\Http\Request;

class ProductControllerApi extends Controller
{
    public function getCategory($slug)
    {
        $getCategory = CategoryModel::getSingleSlug($slug);

        if(!empty($getCategory))
        {
            return response()->json(['getCategory' => $getCategory]);
        }
        else
        {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }
}
