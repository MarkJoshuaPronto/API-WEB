<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\BrandModel;
use App\Models\ColorModel;
use Illuminate\Support\Str;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\ProductSizeModel;
use App\Models\SubCategoryModel;
use App\Models\ProductColorModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductImageModel;

class ProductApiController extends Controller
{
    public function list()
    {
        $products = ProductModel::getRecord();
        return response()->json($products);
    }

    public function add()
    {
        return response()->json(['message' => 'Add New Product']);
    }

    public function insert(Request $request)
    {
        $title = trim($request->title);
        $product = new ProductModel;
        $product->title = $title;
        $product->created_by = Auth::user()->id;
        $product->save();

        $slug = Str::slug($title, "=");

        $checkSlug = ProductModel::checkSlug($slug);
        if (empty($checkSlug)) {
            $product->slug = $slug;
            $product->save();
        } else {
            $new_slug = $slug . '-' . $product->id;
            $product->slug = $new_slug;
            $product->save();
        }

        return response()->json(['message' => 'Product inserted', 'product_id' => $product->id]);
    }

    public function edit($product_id)
    {
        $product = ProductModel::getSingle($product_id);
        if (!empty($product)) {
            $data['getCategory'] = CategoryModel::getRecordActive();
            $data['getBrand'] = BrandModel::getRecordActive();
            $data['getColor'] = ColorModel::getRecordActive();
            $data['product'] = $product;
            $data['getSubCategory'] = SubCategoryModel::getRecordSubCategory($product->category_id);
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function update($product_id, Request $request)
    {
        $product = ProductModel::getSingle($product_id);
        if (!empty($product)) {
            $product->fill($request->all());
            $product->save();

            ProductColorModel::DeleteRecord($product->id);

            if (!empty($request->color_id)) {
                foreach ($request->color_id as $color_id) {
                    $color = new ProductColorModel;
                    $color->color_id = $color_id;
                    $color->product_id = $product->id;
                    $color->save();
                }
            }

            ProductSizeModel::DeleteRecord($product->id);

            if (!empty($request->size)) {
                foreach ($request->size as $size) {
                    if (!empty($size['name'])) {
                        $saveSize = new ProductSizeModel;
                        $saveSize->fill($size);
                        $saveSize->product_id = $product->id;
                        $saveSize->save();
                    }
                }
            }

            if (!empty($request->file('image'))) {
                foreach ($request->file('image') as $value) {
                    if ($value->isValid()) {
                        $ext = $value->getClientOriginalExtension();
                        $randomStr = $product->id . Str::random(20);
                        $filename = strtolower($randomStr) . '.' . $ext;
                        $value->move('upload/product/', $filename);

                        $imageupload = new ProductImageModel;
                        $imageupload->image_name = $filename;
                        $imageupload->image_extension = $ext;
                        $imageupload->product_id = $product->id;
                        $imageupload->save();
                    }
                }
            }

            return response()->json(['success' => true, 'message' => "Product successfully updated"]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function image_delete($id)
    {
        $image = ProductImageModel::getSingle($id);
        if (!empty($image->getImage())) {
            unlink('upload/product/' . $image->image_name);
        }
        $image->delete();

        return response()->json(['success' => true, 'message' => "Product image successfully deleted"]);
    }

    public function product_image_sortable(Request $request)
    {
        if (!empty($request->photo_id)) {
            $i = 1;
            foreach ($request->photo_id as $photo_id) {
                $image = ProductImageModel::getSingle($photo_id);
                $image->order_by = $i;
                $image->save();

                $i++;
            }
        }

        return response()->json(['success' => true]);
    }
}
