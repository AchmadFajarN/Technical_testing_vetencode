<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistributionDetail;
use Illuminate\Support\Facades\Validator;

class distributionProductController extends Controller
{
    public function getTemporaryProduct() {
        $temporaryProduct = DistributionDetail::get();
        return response()->json([
            "status" => "success",
            "data" => $temporaryProduct
        ]);
    }

    public function addTemporaryProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            "distribution_id" => "nullable|string",
            "product_id" => "required|string",
            "qty" => "required|integer",
            "price" => "required|numeric",
            "total" => "required|numeric",
            "created_by" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => $validator->errors()
            ]);
        }

        $temporaryProduct = DistributionDetail::create([
            "id" => \Illuminate\Support\Str::uuid()->toString(),
            "distribution_id" => $request->distribution_id,
            "product_id" => $request->product_id,
            "qty" => $request->qty,
            "price" => $request->price,
            "total" => $request->total,
            "created_by" => $request->created_by
        ]);

        return response()->json([
            "status" => "success",
            "data" => $temporaryProduct
        ]);
    }

    public function deleteTemporaryProduct($id) {
        $temporaryProduct = DistributionDetail::find($id);

        if(!$temporaryProduct) {
            return response()->json([
                "status"=> "failed",
                "message" => "barang sementara dengan id tersebut tidak ditemukan"
            ]);
        }
        
        $temporaryProduct->delete();

        return response() -> json([
            "status" => "success",
            "message" => "barang sementara dengan id tersebut berhasil dihapus"
        ]);
    }

    public function getDistributionDetail($distributionId) {
        $distributionDetail = DistributionDetail::where('distribution_id', $distributionId)->get();
        if ($distributionDetail->isEmpty()) {
            return response()->json([
                "status" => "failed",
                "message" => "distribution detail dengan id distribution tersebut tidak ditemukan"
            ]);
        }

        return response() -> json([
            "status" => "success",
            "data" => $distributionDetail->map(function($item) {
                return [
                    "id" => $item->id,
                    "product_name" => $item->product->name,
                    "qty" => $item->qty,
                    "price" => $item->price,
                    "total" => $item->total
                ];
            })
        ], 200);
    }
}
