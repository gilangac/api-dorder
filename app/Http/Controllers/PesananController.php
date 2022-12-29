<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Http\Resources\PesananResource;
use App\Http\Resources\DetailPesananResource;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->id == null){
            $data = Pesanan::orderBy('id', 'ASC')->get();
            $respon = [
                'status' => 'success',
                'msg' => 'Fetched all data',
                'errors' => null,
                'content' => PesananResource::collection($data)
            ];
            return response()->json($respon,200);
        } 
        else{
            $data = Pesanan::orderBy('id', 'ASC')->where('id','=', $request->id)->get();
            $respon = [
                'status' => 'success',
                'msg' => "Fetched {$request->id}",
                'errors' => null,
                'content' => PesananResource::collection($data)
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

        $json = $request->json()->all();
        $validator = Validator::make($request->all(),[
            'waktu_pesan' => 'string',
            'waktu_proses' => 'string',
            'waktu_kirim' => 'string',
            'waktu_selesai' => 'string',
            'total_harga' => 'integer',
            'ongkir' => 'integer',
            'total_produk' => 'integer',
            'alamat_antar' => 'string',
            'latitude_antar' => 'string',
            'longitude_antar' => 'string',
            'rating' => 'integer',
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

        // $pesanan = Pesanan::create([
        //     'status' => 'waiting',
        //     'waktu_pesan' => $json['waktu_pesan'],
        //     'waktu_proses' => $request->waktu_proses,
        //     'waktu_selesai' => $request->waktu_selesai,
        //     'harga_produk_total' => $request->harga_produk_total,
        //     'jumlah_produk' => $request->jumlah_produk,
        //  ]);
        $jumlah = 0;
        $harga = 0;
        for ($i=0; $i < count($json["produk"]); $i++) { 
             $jumlah += $json["produk"][$i]["jumlah"];
             $harga += $json["produk"][$i]["jumlah"] * $json["produk"][$i]["harga"];
        }
         $pesanan = new Pesanan;
         $pesanan->id_user = auth()->user()['id'];
         $pesanan->waktu_pesan = $json['waktu_pesan'];
         $pesanan->waktu_proses = $json['waktu_proses'];
         $pesanan->waktu_kirim = $json['waktu_kirim'];
         $pesanan->waktu_selesai = $json['waktu_selesai'];
         $pesanan->total_harga = $harga;
         $pesanan->waktu_selesai = $json['ongkir'];
         $pesanan->alamat_antar = $json['alamat_antar'];
         $pesanan->latitude_antar = $json['latitude_antar'];
         $pesanan->longitude_antar = $json['longitude_antar'];
         $pesanan->rating = $json['rating'];
        $pesanan->total_produk = $jumlah;
        $pesanan->save();
        for ($i=0; $i < count($json["produk"]); $i++) { 
             $detailPesanan = DetailPesanan::create([
                'id_produk' => $json["produk"][$i]["id"],
                'id_pesanan' => $pesanan->id,
                'jumlah' => $json["produk"][$i]["jumlah"],
                'sub_total' => $json["produk"][$i]["jumlah"] * $json["produk"][$i]["harga"],
             ]);  
        }
        
         $respon = [
            'status' => 'success',
            'msg' => 'Pesanan created successfully.',
            'errors' => null,
            'content' => new PesananResource($pesanan),
        ];
        return response()->json($respon, 200);
        // return response()->json(['Pesanan created successfully.', new PesananResource($pesanan)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pesanan = Pesanan::find($id);
        if (is_null($pesanan)) {
            return response()->json('Data not foundw', 404); 
        }
        $respon = [
            'status' => 'success',
            'msg' => 'Pesanan showed.',
            'errors' => null,
            'content' => new PesananResource($pesanan),
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
    public function update(Request $request, Pesanan $pesanan)
    {
        $validator = Validator::make($request->all(),[
            'waktu_pesan' => 'dateTime',
            'waktu_proses' => 'dateTime',
            'waktu_kirim' => 'dateTime',
            'waktu_selesai' => 'dateTime',
            'total_harga' => 'integer',
            'ongkir' => 'integer',
            'total_produk' => 'integer',
            'alamat_antar' => 'string',
            'latitude_antar' => 'string',
            'longitude_antar' => 'string',
            'rating' => 'integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $request->waktu_pesan == null ? null : $pesanan->waktu_pesan = $request->waktu_pesan;        
        $request->waktu_proses == null ? null : $pesanan->waktu_proses = $request->waktu_proses;
        $request->waktu_kirim == null ? null : $pesanan->waktu_kirim = $request->waktu_kirim;
        $request->waktu_selesai == null ? null : $pesanan->waktu_selesai = $request->waktu_selesai;
        $request->total_harga == null ? null : $pesanan->total_harga = $request->total_harga;
        $request->ongkir == null ? null : $pesanan->ongkir = $request->ongkir;
        $request->total_produk == null ? null : $pesanan->total_produk = $request->total_produk;
        $request->alamat_antar == null ? null : $pesanan->alamat_antar = $request->alamat_antar;
        $request->latitude_antar == null ? null : $pesanan->latitude_antar = $request->latitude_antar;
        $request->longitude_antar == null ? null : $pesanan->longitude_antar = $request->longitude_antar;
        $request->rating == null ? null : $pesanan->rating = $request->rating;
        $pesanan->save();

        $respon = [
            'status' => 'success',
            'msg' => 'Pesanan updated successfully..',
            'errors' => null,
            'content' => new PesananResource($pesanan),
        ];
        
        return response()->json($respon,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();

        return response()->json(['status' => 'success', 'msg' => "Pesanan deleted successfully"]);
    }
}