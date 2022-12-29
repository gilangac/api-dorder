<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Produk;
use App\Http\Resources\ProdukResource;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->nama_produk == null && $request->id_user == null){
            $data = Produk::orderBy('id', 'ASC')->get();
            $respon = [
                'status' => 'success',
                'msg' => 'Fetched all data',
                'errors' => null,
                'content' => ProdukResource::collection($data)
            ];
            return response()->json($respon,200);
        } 
        else if ($request->nama_produk != null) {
            if($request->id_user == null){
                $data = Produk::orderBy('id', 'ASC')->where('nama_produk','like', "%{$request->nama_produk}%")->get();
            } else {
                $data = Produk::orderBy('id', 'ASC')->where('nama_produk','like', "%{$request->nama_produk}%")->where('id_user','=', $request->id_user)->get();
            }
            $respon = [
                'status' => 'success',
                'msg' => "Fetched {$request->nama_produk}",
                'errors' => null,
                'content' => ProdukResource::collection($data)
            ];
            return response()->json($respon,200);
        }  
        else if ($request->id_user != null) {
            $data = Produk::orderBy('id', 'ASC')->where('id_user','=', $request->id_user)->get();
            $respon = [
                'status' => 'success',
                'msg' => "Fetched produk user {$request->id_user}",
                'errors' => null,
                'content' => ProdukResource::collection($data)
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
            'nama_produk' => 'required|string|max:60',
            'id_kategori_produk' => 'integer',
            'harga' => 'integer',
            'stok' => 'integer',
            'deskripsi' => 'string',
            'foto' => 'string|max:30',
            'id_user' => 'integer',
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

        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'foto' => $request->foto,
            'id_user' => auth()->user()['id'],
            'id_kategori_produk' => $request->id_kategori_produk,
         ]);
         $respon = [
            'status' => 'success',
            'msg' => 'Produk created successfully.',
            'errors' => null,
            'content' => new ProdukResource($produk),
        ];
        return response()->json($respon, 200);
        // return response()->json(['Produk created successfully.', new ProdukResource($produk)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::find($id);
        if (is_null($produk)) {
            return response()->json('Data not foundw', 404); 
        }
        $respon = [
            'status' => 'success',
            'msg' => 'Produk showed.',
            'errors' => null,
            'content' => new ProdukResource($produk),
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
    public function update(Request $request, Produk $produk)
    {
        $validator = Validator::make($request->all(),[
            'nama_produk' => 'required|string|max:60',
            'id_kategori_produk' => 'integer',
            'harga' => 'integer',
            'stok' => 'integer',
            'deskripsi' => 'string',
            'foto' => 'string|max:30',
            'id_user' => 'integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $request->nama_produk == null ? null : $produk->nama_produk = $request->nama_produk;
        $request->harga == null ? null : $produk->harga = $request->harga;        
        $request->stok == null ? null : $produk->stok = $request->stok;
        $request->deskripsi == null ? null : $produk->deskripsi = $request->deskripsi;
        $request->foto == null ? null : $produk->foto = $request->foto;
        $request->id_user == null ? null : $produk->id_user = $request->id_user;
        $request->id_kategori_produk == null ? null : $produk->id_kategori_produk = $request->id_kategori_produk;
        $produk->save();

        $respon = [
            'status' => 'success',
            'msg' => 'Produk updated successfully..',
            'errors' => null,
            'content' => new ProdukResource($produk),
        ];
        
        return response()->json($respon,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return response()->json(['status' => 'success', 'msg' => "Produk deleted successfully"]);
    }
}