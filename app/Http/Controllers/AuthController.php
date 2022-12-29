<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Arr;
use App\Models\User;


use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'nama' => 'required|string|max:60',
            'email' => 'required|string|email|max:60|unique:users',
            'password' => 'required|string|min:6',
            // 'password' => \Hash::make('12345678'),
            'jenis_user' => 'required|string',
            'telp' => 'string',
            'alamat' => 'string',
            'foto' => 'image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }
         $uploadFolder = 'images/user';
         $image = $request->file('foto');
         if($request->foto != null){
            $image_uploaded_path = $image->store($uploadFolder, 'public');
         }else{
            $image_uploaded_path = "";
         }
        //  $uploadedImageResponse = array(
        //     "image_name" => basename($image_uploaded_path),
        //     "image_url" => Storage::disk('public')->url($image_uploaded_path),
        //     "mime" => $image->getClientMimeType()
        //  );
        //  return sendCustomResponse('File Uploaded Successfully', 'success',   200, $uploadedImageResponse);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'jenis_user' => $request->jenis_user,
            'telp' => $request->telp ?? "",
            'alamat' => $request->alamat ?? "",
            'foto' => $request->foto != null ? url($image_uploaded_path) : "",
            'latitude' => $request->latitude ?? "",
            'longitude' => $request->longitude ?? "",
            'latitude_favorite' => $request->latitude ?? "",
            'longitude_favorite' => $request->longitude ?? "",
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['content' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function login(Request $request) {
        $validate = \Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null,
            ];
            return response()->json($respon, 200);
        } 
        else {
            $credentials = request(['email', 'password']);
            $credentials = Arr::add($credentials, 'email', request(['email']));
            if (!Auth::attempt($credentials)) {
                $respon = [
                    'status' => 'success',
                    'msg' => 'Unathorized',
                    'errors' => null,
                    'content' => null,
                ];
                return response()->json($respon, 200);
            }

            $user = User::where('email', $request->email)->first();
            if (! \Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            $tokenResult = $user->createToken('token-auth')->plainTextToken;
            $respon = [
                'status' => 'success',
                'msg' => 'Login successfully',
                'errors' => null,
                'content' => [
                    'status_code' => 200,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ],
                'data' =>  auth()->user()
            ];
            return response()->json($respon, 200);
        }
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $respon = [
            'status' => 'success',
            'msg' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }

    public function logoutall(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();
        $respon = [
            'status' => 'success',
            'msg' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }

    public function sendPasswordResetLinkEmail(Request $request) {
		$request->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		if($status === Password::RESET_LINK_SENT) {
			return response()->json([ 'status' => 'success','message' => __($status)], 200);
		} else {
			return response()->json([ 'status' => 'failed','message' => __($status)], 200);
			// throw ValidationException::withMessages([
			// 	'email' => __($status)
			// ]);
		}
	}

	public function resetPassword(Request $request) {
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:6|confirmed',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) use ($request) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		if($status == Password::PASSWORD_RESET) {
			// return response()->json(['message' => __($status)], 200);
            return view('success');
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}

    public function resetPasswordView($token){
        return view('auth\forgetPasswordLink', ['token' => $token]);
    }
}