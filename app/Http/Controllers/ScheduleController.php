<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ship;
use App\Models\User;
use App\Models\Navigation_area;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
    
    }

    public function show()
    {
        try {
        //部門　18　の人だけを抽出
            $users = User::whereHas('user_descriptions.departments', function ($query) {
                $query->where('departments.id', 18);
            })->select('id', 'name')->get();
            $ships = Ship::query()->select('id', 'name', 'delivered', 'issueInspCert','expiry_date','navigation_area_id')
            ->with(['users:id,name','navigation_areas:id,name',
            'schedules' => function ($query) {
                $query->select('ship_id', 'interim_start1', 'interim_dline1', 'Periodic_start1', 'Periodic_dline1', 'interim_start2', 'interim_dline2', 'Periodic_start2', 'Periodic_dline2')
                      ->withDefault(); // ここでデフォルト値を設定
            }])
            ->get();
            // dd($ships);
            return Inertia::render('Schedules/GantTaiw',[
                'users' => $users,
                'ships' => $ships,
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            dd($e->getMessage());
        }
        
    }

    public function update(UpdateShipRequest $request, Ship $ship)
    {
        try {
            DB::transaction(function () use ($request, &$ship) {


            });
        
            return redirect()->back()->with([
            'message' => '更新しました。',
            'status' => 'success'
            ]);
            } catch (\Exception $e) {
            // トランザクション失敗時のリダイレクト
            return redirect()->back()->with([
                'message' => '更新に失敗しました',
                'status' => 'error'
            ]);
        }
    }
}
