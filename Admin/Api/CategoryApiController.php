<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use Illuminate\Support\Facades\Auth;

class CategoryApiController extends Controller
{
    public function list()
    {
        $categories = CategoryModel::getRecord();
        return response()->json(['categories' => $categories, 'header_title' => 'Category']);
    }

    public function add()
    {
        return response()->json(['header_title' => 'Add New Category']);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:category'
        ]);

        $category = new CategoryModel;
        $category->name = trim($request->name);
        $category->slug = trim($request->slug);
        $category->status = trim($request->status);
        $category->meta_title = trim($request->meta_title);
        $category->meta_description = trim($request->meta_description);
        $category->meta_keywords = trim($request->meta_keywords);
        $category->created_by = Auth::user()->id;
        $category->save();

        return response()->json(['message' => 'Category Successfully Created']);
    }

    public function edit($id)
    {
        $category = CategoryModel::getSingle($id);
        return response()->json(['category' => $category, 'header_title' => 'Edit Category']);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:category,slug,'.$id,
        ]);

        $category = CategoryModel::getSingle($id);
        $category->name = trim($request->name);
        $category->slug = trim($request->slug);
        $category->status = trim($request->status);
        $category->meta_title = trim($request->meta_title);
        $category->meta_description = trim($request->meta_description);
        $category->meta_keywords = trim($request->meta_keywords);
        $category->save();

        return response()->json(['message' => 'Category Successfully Updated']);
    }

    public function delete($id)
    {
        $category = CategoryModel::getSingle($id);
        $category->is_delete = 1;
        $category->save();

        return response()->json(['message' => 'Category Successfully Deleted']);
    }
}
