<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\SubCategoryModel;
use Illuminate\Support\Facades\Auth;

class SubCategoryApiController extends Controller
{
    public function list()
    {
        $data['getRecord'] = SubCategoryModel::getRecord();
        return response()->json($data);
    }

    public function add()
    {
        $data['getCategory'] = CategoryModel::getRecord();
        return response()->json($data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:sub_category'
        ]);

        $subcategory = new SubCategoryModel;
        $subcategory->category_id = trim($request->category_id);
        $subcategory->name = trim($request->name);
        $subcategory->slug = trim($request->slug);
        $subcategory->status = trim($request->status);
        $subcategory->meta_title = trim($request->meta_title);
        $subcategory->meta_description = trim($request->meta_description);
        $subcategory->meta_keywords = trim($request->meta_keywords);
        $subcategory->created_by = Auth::user()->id;
        $subcategory->save();

        return response()->json(['message' => 'Sub Category Successfully Created']);
    }

    public function edit($id)
    {
        $data['getCategory'] = CategoryModel::getRecord();
        $data['getRecord'] = SubCategoryModel::getSingle($id);
        return response()->json($data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:sub_category,slug,'.$id,
        ]);

        $subcategory = SubCategoryModel::getSingle($id);
        $subcategory->category_id = trim($request->category_id);
        $subcategory->name = trim($request->name);
        $subcategory->slug = trim($request->slug);
        $subcategory->status = trim($request->status);
        $subcategory->meta_title = trim($request->meta_title);
        $subcategory->meta_description = trim($request->meta_description);
        $subcategory->meta_keywords = trim($request->meta_keywords);
        $subcategory->save();

        return response()->json(['message' => 'Sub Category Successfully Updated']);
    }

    public function delete($id)
    {
        $category = SubCategoryModel::getSingle($id);
        $category->is_delete = 1;
        $category->save();

        return response()->json(['message' => 'Sub Category Successfully Deleted']);
    }

    public function get_sub_category(Request $request)
    {
        $category_id = $request->id;
        $get_sub_category = SubCategoryModel::getRecordSubCategory($category_id);
        $html = '';
        $html .= '<option value="">Select</option>';
        foreach ($get_sub_category as $value)
        {
            $html .= '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

        $json['html']  = $html;
        return response()->json($json);
    }

}
