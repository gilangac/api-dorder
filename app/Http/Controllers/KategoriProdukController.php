<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\KategoriProduk;
use App\Http\Resources\KategoriProdukResource;

class KategoriProdukController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->nama_kategori	 == null){
            $data = KategoriProduk::orderBy('id', 'ASC')->get();
            $respon = [
                'status' => 'success',
                'msg' => 'Fetched all data',
                'errors' => null,
                'content' => KategoriProdukResource::collection($data)
            ];
            return response()->json($respon,200);
        } 
        else{
            $data = KategoriProduk::orderBy('id', 'ASC')->where('nama_kategori','=', $request->nama_kategori)->get();
            $respon = [
                'status' => 'success',
                'msg' => "Fetched kategori produk {$request->nama_kategori}",
                'errors' => null,
                'content' => KategoriProdukResource::collection($data)
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
            'nama_kategori' => 'required|string',
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

        $kategoriProduk = KategoriProduk::create([
            'nama_kategori' => $request->nama_kategori,
         ]);
         $respon = [
            'status' => 'success',
            'msg' => 'Kategori Produk created successfully.',
            'errors' => null,
            'content' => new KategoriProdukResource($kategoriProduk),
        ];
        return response()->json($respon, 200);
        // return response()->json(['Kategori created successfully.', new KategoriProdukResource($kategoriProduk)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kategoriProduk = KategoriProduk::find($id);
        if (is_null($kategoriProduk)) {
            $respon = [
                'status' => 'success',
                'msg' => 'Kategori Produk showed.',
                'errors' => null,
                'content' => [],
            ];
            return response()->json($respon, 200); 
        }
        $respon = [
            'status' => 'success',
            'msg' => 'Kategori Produk showed.',
            'errors' => null,
            'content' => new KategoriProdukResource($kategoriProduk),
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
    public function update(Request $request, KategoriProduk $kategoriProduk)
    {
        $validator = Validator::make($request->all(),[
            'nama_kategori' => 'string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $request->nama_kategori == null ? null : $kategoriProduk->nama_kategori = $request->nama_kategori;
        $kategoriProduk->save();

        $respon = [
            'status' => 'success',
            'msg' => 'Kategori Produk updated successfully..',
            'errors' => null,
            'content' => new KategoriProdukResource($kategoriProduk),
        ];
        
        return response()->json($respon,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriProduk $kategoriProduk)
    {
        $kategoriProduk->delete();

        return response()->json(['status' => 'success', 'msg' => "Kategori Produk deleted successfully"]);
    }
}
