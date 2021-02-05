<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
	{
		try {
			$request->validate([
				'email' => 'email|required',
				'password' => 'required'
			]);
			$credentials = request(['email', 'password']);
			
			if (!Auth::attempt($credentials)) {
				return response()->json([
					'status_code' => 404,
					'message' => 'Unauthorized'
				], 404);
			}
			
			$user = User::where('email', $request->email)->first();
			if ( ! Hash::check($request->password, $user->password, [])) {
				throw new \Exception('Error in Login');
			}
			$tokenResult = $user->createToken('authToken')->plainTextToken;
			return response()->json([
				'status_code' => 200,
				'access_token' => $tokenResult,
				'token_type' => 'Bearer',
			], 200);
		}
		catch (Exception $error) {
			return response()->json([
				'status_code' => 404,
				'message' => 'Error in Login',
				'error' => $error,
			], 404);
		}
	}
}
