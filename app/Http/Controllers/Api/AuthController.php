<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['authenticate', 'register']]);
    // }
    // public function __construct()
    // {
    //     /**
    //      * Super Admin Access
    //      */
    //     $this->middleware('role:posko-utama', ['except' => ['index', 'show']]);

    //     /**
    //      * Super Admin and Pemerintah Access
    //      */
    //     $this->middleware('role:posko-utama|posko', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
    // }

    public function authenticate(Request $request)
    {
        try {
            // Filtering Username or Email
            $email_or_username = $request->input('email_or_username');
            $field = filter_var($email_or_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $request->merge([$field => $email_or_username]);

            $validator = Validator::make($request->all(), [
                $field => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors(),
                    ],
                    422
                );
            }

            $credentials = request([$field, 'password']);

            if (!($token = auth()->guard('api')->attempt($credentials))) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Email atau Password Anda salah',
                    ],
                    401,
                );
            }

            return response()->json(
                [
                    'success' => true,
                    'user' => auth()->guard('api')->user(),
                    'token' => $token,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                400,
            );
        }
    }

    public function refresh()
    {
        return response()->json([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            $model_has_role = $user->assignRole('posko');

            /**
             * Validation Submit
             */
            if ($user && $model_has_role) {
                DB::commit();
                return response()->json(
                    [
                        'success' => true,
                        'user' => $user
                    ],
                    200,
                );
            } else {
                DB::rollBack();
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User Gagal Disimpan',
                    ],
                    400,
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                400,
            );
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil!',
        ]);
    }
}
