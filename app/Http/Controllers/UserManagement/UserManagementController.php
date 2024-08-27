<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
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
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (User::find(Auth::user()->id)->hasRole(['super-admin', 'pemerintah'])) {
                return $next($request);
            }
            return redirect('/');
        });
    }
    public function index()
    {
        return view('user_management.index');
    }

    public function dataTable(Request $request)
    {
        $user = User::with('roles')->whereNull('deleted_at')->get();
        if ($request->ajax()) {
            $data_tables = DataTables::of($user)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn_action = '<a href="' . route('user-management.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary my-1" title="Detail"><i class="fas fa-eye"></i></a>';

                    if (User::find(Auth::user()->id)->hasRole('super-admin')) {
                        $btn_action .= '<a href="' . route('user-management.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning my-1 ml-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';

                        /**
                         * Validation User Logged In Equals with User Record id
                         */
                        if (Auth::user()->id != $data->id) {
                            $btn_action .= '<button class="btn btn-lg btn-danger my-1 ml-1" onclick="destroy(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                        }
                    }

                    return $btn_action;
                })
                ->addColumn('role', function ($data) {
                    $roles = $data->roles->pluck('name')->toArray();
                    return ucwords(implode(' ', explode('-', $roles[0])));
                })
                ->rawColumns(['action'])
                ->make(true);
            return $data_tables;
        }
    }

    public function create()
    {
        $roles = Role::get();
        return view('user_management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|exists:roles,id',
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
                return redirect()->route('user-management.index')->with('success', 'User Berhasil Disimpan');
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'User Gagal Disimpan']);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
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
                    'message' => 'user gagal di hapus',
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

                return view('user_management.detail', $data);
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
                    ->with(['failed' => 'Invalid Request!']);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|min:8',
                'phone' => 'required|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $request->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
                'password' => 'nullable|min:8|confirmed',
            ]);

            // Cek jika password diisi, jika tidak unset dari $data
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            DB::beginTransaction();

            // Ambil user berdasarkan ID
            $user = User::findOrFail($request->id);

            // Update user dengan data yang sudah divalidasi
            $user_update = $user->update($data);

            /**
             * Validation Submit
             */
            if ($user_update) {
                // Perbarui role pengguna
                $roleName = Role::find($request->role)->name;
                $user->syncRoles([$roleName]); // Menghapus role lama dan menambahkan role baru

                DB::commit();
                return redirect()->route('user-management.index')->with('success', 'User Berhasil Disimpan');
            } else {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'User Gagal Disimpan']);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }
}
