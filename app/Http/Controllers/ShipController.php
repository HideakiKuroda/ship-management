<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipRequest;
use App\Http\Requests\UpdateShipRequest;
use App\Models\Ship;
use App\Models\Ship_owner;
use App\Models\Summary;
use App\Models\Summary2;
use App\Models\User;
use App\Models\Navigation_area;
use App\Models\Operat_section;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class ShipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with('ships')->has('ships')->get();
        $ships = Ship::select('id', 'name', 'yard', 'ship_no')
        ->get();

        return Inertia::render('Ships/Index', [
            'users' => $users,
            'ships' => $ships,
        ]);

        // dd( $users, $ships,);
        
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
    public function store(StoreShipRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ship $ship)
    {
        try {
            $ship->load('summaries', 'summary2s', 'concerneds','ship_owners','operat_sections','navigation_areas');
            // dd($ship);
            return Inertia::render('Ships/Show',['ship' => $ship]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            dd($e->getMessage());
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ship $ship)
    {
        try {
            $navigationAreas = Navigation_area::select('id', 'name')->get();
            $operatSections = Operat_section::select('id', 'section')->get();
            $departmentId = 4;
            $users = User::whereHas('user_descriptions.departments', function ($query) use ($departmentId) {
                $query->where('departments.id', $departmentId);
            })
            ->select('id','name')
            ->get();
            
            $ship->load('summaries', 'summary2s', 'concerneds','ship_owners','operat_sections','navigation_areas','users');
            
            return Inertia::render('Ships/Edit',[
                'ship' => $ship, 
                'navigationAreas'=>$navigationAreas,
                'operatSections'=>$operatSections,
                'users'=>$users,
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            dd($e->getMessage());
        }
    }
    public function assignUser(Request $request, $id)
    {
       
        $ship = Ship::findOrFail($id);
        $userId = $request->input('user_id');
        $roleId = $request->input('role_id');

        $ship->users()->attach($userId, ['role_id' => $roleId]);

        return redirect()->route('ships.edit', $ship->id); 
    }

    public function unassignUser($id, $user_id)
    {
        $ship = Ship::findOrFail($id);
        $ship->users()->detach($user_id);

        return redirect()->route('ships.edit', $ship->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipRequest $request, Ship $ship)
    {
        
        $ship->save();
        
        return to_route('ships.index')
        ->with([
            'message' => '更新しました。',
            'status' => 'success'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ship $ship)
    {
        //
    }
}
