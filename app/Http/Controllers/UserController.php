<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'name','email','email_verified_at')->get();
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // 編集処理
        } else {

            return redirect()->back()->with('error', '編集権限がありません。');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function showBoard()
    {
        $user = Auth::user();
        dd($user);
        if ($user->hasRole('admin')) {
        // dd( $navigationAreas,$operatSections,$users);
            return Inertia::render('adminboards');
        } else {
            // 権限がない場合の処理
            return response()->json(['error' => '管理者権限が必要です。'], 403);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getRolesWithPermissions()
    {
        // dd('role');
        $roles = Role::with('permissions')->get();
        // dd(role);
        return response()->json($roles);
    }
}
