<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distribution;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DistributionDetail;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class distributionController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->ajax()) {
            $data = Distribution::with('barista'); 
            
            return DataTables::eloquent($data)
                ->editColumn('created_at', function (Distribution $distribution) {
                     return date('d/m/Y', strtotime($distribution->created_at)); 
                })
                ->addColumn('barista', function (Distribution $distribution) {
                    return $distribution->barista->name ?? $distribution->barista_id;
                })
                ->editColumn('estimated_result', function (Distribution $distribution) {
                     return 'Rp ' . number_format($distribution->estimated_result, 0, ',', '.');
                })  
                ->addColumn('actions', function (Distribution $distribution) {
                    return '<button class="btn btn-sm btn-info text-white btn-detail" data-id="'.$distribution->id.'" data-bs-toggle="modal" data-bs-target="#detailModal">Detail</button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="'.$distribution->id.'">Hapus</button>';
                })
                ->rawColumns(['actions']) 
                ->toJson();
        }

        return view('distributions.index'); 
    }

    public function create()
    {
        return view('distributions.create');
    }

    public function store(Request $request) 
    {

       $adminName = $request->input('created_by');
       $adminUser = User::where('name', $adminName)->where('role', 'admin')->first();

       if (!$adminUser) {
        return response()->json([
            "status" => "failed",
            "message" => "Nama admin salah"
        ], 400);
       }

       $request->merge(['created_by' => $adminUser->id]);
    
       $validator = Validator::make($request->all(), [
            "barista_id" => "required|string|exists:users,id",
            "total_qty" => "required|integer|min:1",
            "estimated_result" => "required|numeric|min:0",
            "notes" => "nullable|string",
            "created_by" => "required|string|exists:users,id",
            
            "details" => "required|array|min:1", 
            "details.*.product_id" => "required|string|exists:products,id",
            "details.*.qty" => "required|integer|min:1",
            "details.*.price" => "required|numeric|min:0",
            "details.*.total" => "required|numeric|min:0"
       ]);

       if ($validator->fails()) {
           return response()->json([
               "status" => "failed",
               "message" => "Validasi Gagal.",
               "errors" => $validator->errors()
           ], 400);
       }

       try {
           $distribution = DB::transaction(function () use ($request) {
               
               $distribution = Distribution::create([
                    "id" => \Illuminate\Support\Str::uuid()->toString(),
                    "barista_id" => $request->barista_id,
                    "total_qty" => $request->total_qty,
                    "estimated_result"=>$request->estimated_result,
                    "notes" => $request->notes,
                    "created_by" => $request->created_by
               ]);

               $createdBy = $request->created_by; 

               $details = collect($request->details)->map(function ($detail) use ($distribution, $createdBy) {
                    return array_merge($detail, [
                        'id' => \Illuminate\Support\Str::uuid()->toString(), 
                        'distribution_id' => $distribution->id, 
                        'created_by' => $createdBy, 
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
               })->toArray();
               
               DistributionDetail::insert($details); 

               return $distribution;
           });
           
           return response()->json([
                "status" => "success",
                "message" => "Distribusi dan Detail berhasil ditambahkan secara transactional.",
                "data" => $distribution
           ], 201);
           
       } catch (\Exception $e) {
           return response()->json([
                "status" => "failed",
                "message" => "Gagal menyimpan distribusi. Transaksi dibatalkan: " . $e->getMessage()
           ], 500);
       }
    }

    public function destroy($id) {
        $distribution = Distribution::find($id);
        
        if (!$distribution) {
            return response()->json([
                "status" => "failed",
                "message" => "distribusi dengan id tersebut tidak ditemukan"
            ], 404);
        }

        try {
            DB::transaction(function () use ($distribution) {
                DistributionDetail::where("distribution_id", $distribution->id)->delete();
                $distribution->delete();
            });
        }catch(\Exception $err) {
              return response()->json([
                "status" => "failed",
                "message" => "Gagal menghapus distribusi. Transaksi dibatalkan: " . $err->getMessage()
            ], 500);
        }
    }
}

