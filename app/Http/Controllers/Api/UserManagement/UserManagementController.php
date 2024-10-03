<?php

namespace App\Http\Controllers\Api\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\User;
use App\Models\UserManagement\Role;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    // public function __construct()
    // {
    //     /**
    //      * Super Admin Access
    //      */
    //     $this->middleware('role:super-admin', ['except' => [
    //         'index', 'show', 'dataTable'
    //     ]]);

    //     /**
    //      * Super Admin and Pemerintah Access
    //      */
    //     $this->middleware('role:super-admin|pemerintah', ['except' => [
    //         'create', 'store', 'edit', 'update', 'destroy'
    //     ]]);
    // }

    public function __construct()
    {
        $this->middleware('role:posko-utama|posko');
    }

    public function index()
    {
        $user = User::with('roles')->whereNull('deleted_at')->get();
        return ApiResponse::success($user);
    }

    public function create()
    {
        $roles = Role::get();
        return ApiResponse::success($roles);

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

    public function destory(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::where('id', $request->id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            /**
             * Validation Submit
             */
            if ($user) {
                DB::commit();
                return response()->json([
                    'success' => true,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'User Gagal Dihapus',
                ]);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
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
    public function edit($id)
    {
        try {
            /**
             * Get User Record from id
             */
            $user = User::with(['roles'])->find($id);
            $roles = Role::get();

            /**
             * Validation User id
             */
            if (!is_null($user)) {
                $data['user'] = $user;

                /**
                 * User Role Configuration
                 */
                $data['user_role'] = $user->roles->first()->id;
                $data['roles'] = $roles;

                return view('user_management.edit', $data);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Data Tidak Ditemukan']);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
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

            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }
}
