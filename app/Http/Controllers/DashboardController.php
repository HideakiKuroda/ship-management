<?php

namespace App\Http\Controllers;

use App\Models\Model_has_roles;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Model_has_roles $model_has_roles)
    {
        dd($model_has_roles);
      
        $user = Auth::user();
        if ($user->hasRole('admin')) {
        // dd( $navigationAreas,$operatSections,$users);
            return Inertia::render('dashboard');
        } else {
            // 権限がない場合の処理
            return response()->json(['error' => '管理者権限が必要です。'], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Model_has_roles $model_has_roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Model_has_roles $model_has_roles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Model_has_roles $model_has_roles)
    {
        //
    }
}
