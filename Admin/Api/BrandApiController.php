<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandModel;
use Illuminate\Support\Facades\Auth;

class BrandApiController extends Controller
{
    public function list()
    {
        $brands = BrandModel::getRecord();
        return response()->json(['brands' => $brands, 'header_title' => 'Brand']);
    }

    public function add()
    {
        return response()->json(['header_title' => 'Add New Brand']);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:brand'
        ]);

        $brand = new BrandModel;
        $brand->name = trim($request->name);
        $brand->slug = trim($request->slug);
        $brand->status = trim($request->status);
        $brand->meta_title = trim($request->meta_title);
        $brand->meta_description = trim($request->meta_description);
        $brand->meta_keywords = trim($request->meta_keywords);
        $brand->created_by = Auth::user()->id;
        $brand->save();

        return response()->json(['message' => 'Brand Successfully Created']);
    }

    public function edit($id)
    {
        $brand = BrandModel::getSingle($id);
        return response()->json(['brand' => $brand, 'header_title' => 'Edit Brand']);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:brand,slug,'.$id,
        ]);

        $brand = BrandModel::getSingle($id);
        $brand->name = trim($request->name);
        $brand->slug = trim($request->slug);
        $brand->status = trim($request->status);
        $brand->meta_title = trim($request->meta_title);
        $brand->meta_description = trim($request->meta_description);
        $brand->meta_keywords = trim($request->meta_keywords);
        $brand->save();

        return response()->json(['message' => 'Brand Successfully Updated']);
    }

    public function delete($id)
    {
        $brand = BrandModel::getSingle($id);
        $brand->is_delete = 1;
        $brand->save();

        return response()->json(['message' => 'Brand Successfully Deleted']);
    }
}
