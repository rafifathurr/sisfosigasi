<?php

namespace App\Http\Controllers\Api\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\User;
use App\Models\UserManagement\Role;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:posko-utama|posko');
    }

    public function index()
    {
        $user = User::with('roles')->whereNull('deleted_at')->paginate(10);
        return ApiResponse::success($user);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|max:13',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed',
                'role' => 'required',
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

            $roleName = Role::find($request->role)->name;

            $model_has_role = $user->assignRole($roleName);

            /**
             * Validation Submit
             */
            if ($user && $model_has_role) {
                DB::commit();
                return ApiResponse::created($user);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('user gagal disimpan');

            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            /**
             * Get User Record from id
             */
            $user = User::find($id);

            /**
             * Validation User id
             */
            if (!is_null($user)) {
                $data['user'] = $user;

                /**
                 * User Role Configuration
                 */
                $data['user_role'] = ucwords(implode(' ', explode('-', $user->getRoleNames()[0])));

                return ApiResponse::success($data);
            } else {
                return ApiResponse::badRequest('Data Tidak Ditemukan');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|max:13',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|confirmed',
                'address' => 'string',

            ]);

            // Cek jika password diisi, jika tidak unset dari $data
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            DB::beginTransaction();

            // Ambil user berdasarkan ID
            $user = User::findOrFail($id);

            // Update user dengan data yang sudah divalidasi
            $user_update = $user->update($data);

            /**
             * Validation Submit
             */
            if ($user_update) {
                // Perbarui role pengguna
                $roleName = Role::find($request->role)->name;
                $user->syncRoles([$roleName]); // Menghapus role lama dan menambahkan role baru
                $user_data = User::with(['roles'])->find($id);

                DB::commit();
                return ApiResponse::success($user_data);
            } else {
                DB::rollBack();
                return ApiResponse::success('User Gagal Disimpan');

            }
        } catch (Exception $e) {
            return ApiResponse::success($e->getMessage());
        }
    }
}
