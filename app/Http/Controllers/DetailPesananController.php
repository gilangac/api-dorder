<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use App\Http\Controllers\Controller;
use Validator;
use App\Models\DetailPesanan;
use App\Http\Resources\DetailPesananResource;

class DetailPesananController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->id_pesanan == null){
            $data = DetailPesanan::orderBy('id', 'ASC')->get();
            $respon = [
                'status' => 'success',
                'msg' => 'Fetched all data',
                'errors' => null,
                'content' => DetailPesananResource::collection($data)
            ];
            return response()->json($respon,200);
        } 
        else{
            $data = DetailPesanan::orderBy('id', 'ASC')->where('id_pesanan','=', $request->id_pesanan)->get();
            $respon = [
                'status' => 'success',
                'msg' => "Fetched Detail pesanan {$request->id_pesanan}",
                'errors' => null,
                'content' => DetailPesananResource::collection($data)
            ];
            return response()->json($respon,200);
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_produk' => 'integer',
            'id_pesanan' => 'integer',
            'jumlah' => 'integer',
            'sub_total' => 'integer',
        ]);

        if($validator->fails()){
            $respon = [
                'status' => 'success',
                'msg' => 'error',
                'errors' => $validator->errors(),
                'content' => [],
            ];
            return response()->json($respon, 200);       
        }

        $detailPesanan = DetailPesanan::create([
            'id_produk' => $request->id_produk,
            'id_pesanan' => $request->id_pesanan,
            'jumlah' => $request->jumlah,
            'sub_total' => $request->sub_total,
         ]);
         $respon = [
            'status' => 'success',
            'msg' => 'Detail Pesanan created successfully.',
            'errors' => null,
            'content' => new DetailPesananResource($detailPesanan),
        ];
        return response()->json($respon, 200);
        // return response()->json(['Pesanan created successfully.', new DetailPesananResource($detailPesanan)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detailPesanan = DetailPesanan::find($id);
        if (is_null($detailPesanan)) {
            $respon = [
                'status' => 'success',
                'msg' => 'Detail Pesanan showed.',
                'errors' => null,
                'content' => [],
            ];
            return response()->json($respon, 200); 
        }
        $respon = [
            'status' => 'success',
            'msg' => 'Detail Pesanan showed.',
            'errors' => null,
            'content' => new DetailPesananResource($detailPesanan),
        ];
        return response()->json($respon, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailPesanan $detailPesanan)
    {
        $validator = Validator::make($request->all(),[
            'id_produk' => 'integer',
            'id_pesanan' => 'integer',
            'jumlah' => 'integer',
            'sub_total' => 'integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $request->id_produk == null ? null : $detailPesanan->id_produk = $request->id_produk;
        $request->id_pesanan == null ? null : $detailPesanan->id_pesanan = $request->id_pesanan;
        $request->jumlah == null ? null : $detailPesanan->jumlah = $request->jumlah;
        $request->sub_total == null ? null : $detailPesanan->sub_total = $request->sub_total;
        $detailPesanan->save();

        $respon = [
            'status' => 'success',
            'msg' => 'Detail Pesanan updated successfully..',
            'errors' => null,
            'content' => new DetailPesananResource($detailPesanan),
        ];
        
        return response()->json($respon,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailPesanan $detailPesanan)
    {
        $detailPesanan->delete();

        return response()->json(['status' => 'success', 'msg' => "Detail Pesanan deleted successfully"]);
    }
}
