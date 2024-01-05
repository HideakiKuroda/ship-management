<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ship;
use App\Models\User;
use App\Models\Navigation_area;
use App\Models\Operat_section;
use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
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
            //船を担当している人だけを抽出
            $users = User::with('ships')->has('ships')->get();
            $operatSections = Operat_section::select('id', 'section')->get();
            $ships = Ship::query()->select('id', 'name', 'delivered', 'issueInspCert','expiry_date','navigation_area_id','operat_section_id')
            ->with(['users:id,name','navigation_areas:id,name',
            'schedules' => function ($query) {
                $query->select('ship_id', 'interim_start1', 'interim_dline1', 'Periodic_start1', 'Periodic_dline1', 'interim_start2', 'interim_dline2', 'Periodic_start2', 'Periodic_dline2')
                      ->withDefault(); // ここでデフォルト値を設定
            }])->get();
            
            $projects = Project::whereHas('pro_categories', function ($query) {
                $query->where('pro_categories.dock', 1);    
            })->with(['users:id'])->get();
            $loginUser = Auth::user('id','name'); 
            $hasRole = Auth::user()->hasRole('admin');
                // dd($role);
            return inertia::render('Schedules/DockGant',[
                'users' => $users,
                'ships' => $ships,
                'operatSections' => $operatSections,
                'projects' => $projects,
                'loginUser' => $loginUser,
                'hasRole' => $hasRole,
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            dd($e->getMessage());
        }
        
    }
   
    public function shipfilter(Request $request) {
        $userId =  $request->userId;
        $filtered = Ship::query()->select('id', 'name', 'delivered', 'issueInspCert','expiry_date','navigation_area_id')
        ->with(['users:id,name','navigation_areas:id,name',
        'schedules' => function ($query) {
            $query->select('ship_id', 'interim_start1', 'interim_dline1', 'Periodic_start1', 'Periodic_dline1', 'interim_start2', 'interim_dline2', 'Periodic_start2', 'Periodic_dline2')
                  ->withDefault(); // ここでデフォルト値を設定
        }])
        ->UserShip($userId)->get();
        
        return response()->json($filtered);
    }

    public function update(Request $request, Ship $ship)
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
