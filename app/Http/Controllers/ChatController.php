<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Chat;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
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
            $data = Chat::orderBy('id', 'ASC')->get();
            $respon = [
                'status' => 'success',
                'msg' => 'Fetched all data',
                'errors' => null,
                'content' => ChatResource::collection($data)
            ];
            return response()->json($respon,200);
        } 
        else{
            $data = Chat::orderBy('id', 'ASC')->where('id_pesanan','=', $request->id_pesanan)->get();
            $respon = [
                'status' => 'success',
                'msg' => "Fetched Chat {$request->id_pesanan}",
                'errors' => null,
                'content' => ChatResource::collection($data)
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
            'id_user' => 'integer',
            'id_pesanan' => 'integer',
            'chat' => 'string',
            'waktu' => 'datetime',
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

        $chat = Chat::create([
            'id_user' => $request->id_user,
            'id_pesanan' => $request->id_pesanan,
            'chat' => $request->chat,
            'waktu' => $request->waktu,
         ]);
         $respon = [
            'status' => 'success',
            'msg' => 'Chat created successfully.',
            'errors' => null,
            'content' => new ChatResource($chat),
        ];
        return response()->json($respon, 200);
        // return response()->json(['Pesanan created successfully.', new ChatResource($chat)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chat = Chat::find($id);
        if (is_null($chat)) {
            $respon = [
                'status' => 'success',
                'msg' => 'Chat showed.',
                'errors' => null,
                'content' => [],
            ];
            return response()->json($respon, 200); 
        }
        $respon = [
            'status' => 'success',
            'msg' => 'Chat showed.',
            'errors' => null,
            'content' => new ChatResource($chat),
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
    public function update(Request $request, Chat $chat)
    {
        $validator = Validator::make($request->all(),[
            'id_user' => 'integer',
            'id_pesanan' => 'integer',
            'chat' => 'string',
            'waktu' => 'datetime',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $request->id_user == null ? null : $chat->id_user = $request->id_user;
        $request->id_pesanan == null ? null : $chat->id_pesanan = $request->id_pesanan;
        $request->chat == null ? null : $chat->chat = $request->chat;
        $request->waktu == null ? null : $chat->waktu = $request->waktu;
        $chat->save();

        $respon = [
            'status' => 'success',
            'msg' => 'Chat updated successfully..',
            'errors' => null,
            'content' => new ChatResource($chat),
        ];
        
        return response()->json($respon,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        $chat->delete();

        return response()->json(['status' => 'success', 'msg' => "Chat deleted successfully"]);
    }
}
