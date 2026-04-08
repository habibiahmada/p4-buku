<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $totalAnggota = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->trim();

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->string('role'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.users.index', compact('totalAnggota', 'totalAdmin', 'totalSiswa', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|in:admin,siswa',
            ],[
                'name.required' => 'Nama harus diisi.',
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password harus diisi.',
                'password.min' => 'Password harus minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'role.required' => 'Role harus diisi.',
                'role.in' => 'Role harus berupa admin atau siswa.',
            ]);
        $user = new User();
        $user->name = $request->string('name');
        $user->email = $request->string('email');
        $user->password = bcrypt($request->string('password'));
        $user->role = $request->string('role');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('pages.admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,siswa',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus diisi.',
            'role.in' => 'Role harus berupa admin atau siswa.',
        ]);

        $user->name = $request->string('name');
        $user->email = $request->string('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->string('password'));
        }
        $user->role = $request->string('role');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        User::where('id', $id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
