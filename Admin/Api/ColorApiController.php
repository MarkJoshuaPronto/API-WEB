<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ColorModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ColorApiController extends Controller
{
    public function index()
    {
        $colors = ColorModel::all();
        return response()->json(['data' => $colors]);
    }

    public function show($id)
    {
        $color = ColorModel::find($id);
        if (!$color) {
            return response()->json(['error' => 'Color not found'], 404);
        }
        return response()->json(['data' => $color]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $color = ColorModel::create([
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(['data' => $color, 'message' => 'Color Successfully Created'], 201);
    }

    public function update(Request $request, $id)
    {
        $color = ColorModel::find($id);
        if (!$color) {
            return response()->json(['error' => 'Color not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $color->name = $request->name;
        $color->code = $request->code;
        $color->status = $request->status;
        $color->save();

        return response()->json(['data' => $color, 'message' => 'Color Successfully Updated']);
    }

    public function destroy($id)
    {
        $color = ColorModel::find($id);
        if (!$color) {
            return response()->json(['error' => 'Color not found'], 404);
        }

        $color->delete();

        return response()->json(['message' => 'Color Successfully Deleted']);
    }
}
