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
use Illuminate\Support\Facades\Redis as LaravelRedis;
use Illuminate\Support\Facades;



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
        $navigationAreas = Navigation_area::select('id', 'name')->get();
        $operatSections = Operat_section::select('id', 'section')->get();
        $departmentId = 4;
        $users = User::whereHas('user_descriptions.departments', function ($query) use ($departmentId) {
            $query->where('departments.id', $departmentId);
        })
        ->select('id','name')
        ->get();

        // dd( $navigationAreas,$operatSections,$users);
        return Inertia::render('Ships/Create',[
            'navigationAreas'=>$navigationAreas,
            'operatSections'=>$operatSections,
            'users'=>$users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShipRequest $request)
    {
        /** @var Ship|null */
        $ship = null;
        LaravelRedis::transaction(function () use ($request, &$ship) {
            $ship = Ship::Create([
                'delivered' => $request->input('delivered'),
                'ex_name' => $request->input('ex_name'),
                'former_name' => $request->input('former_name'),
                'gross_tonn' => $request->input('gross_tonn'),
                'name' => $request->input('name'),
                'navigation_area_id' => $request->input('selectedNavigationArea'),
                'operat_section_id' => $request->input('slectedOperatSection'),
                'ship_no' => $request->input('ship_no'),
                'yard' => $request->input('yard'),
            ]);
            
            $ship->summaries()->create();
            $ship->summary2s()->create();
            $ship->concerneds()->create();
            $userIds = collect($request->input('assignedUsersList'))->pluck('id')->all();
            $ship->users()->sync($userIds);
        });
        
        return redirect()->route('ships.show', $ship->id)->with([
            'message' => '新しい船「' . $ship->name . '」を登録しました',
            'status' => 'success'
        ]);    
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
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipRequest $request, Ship $ship)
    {
        $ship = Ship::findOrFail($ship->id);
        LaravelRedis::transaction(function () use ($request, &$ship) {
            // shipsテーブルの更新
            $ship->update([
                'delivered' => $request->input('delivered'),
                'ex_name' => $request->input('ex_name'),
                'former_name' => $request->input('former_name'),
                'gross_tonn' => $request->input('gross_tonn'),
                'name' => $request->input('name'),
                'navigation_area_id' => $request->input('selectedNavigationArea'),
                'operat_section_id' => $request->input('slectedOperatSection'),
                'ship_no' => $request->input('ship_no'),
                'yard' => $request->input('yard'),
            ]);
        
            // summariesテーブルの更新
            $ship->summaries->update([
                'aux_engine' => $request->input('aux_engine'),
                'beam_depth' => $request->input('beam_depth'),
                'breadth' => $request->input('breadth'),
                'dk_machine_pp' => $request->input('dk_machine_pp'),
                'draft_m' => $request->input('draft_m'),
                'draft_mark_A' => $request->input('draft_mark_A'),
                'draft_mark_F' => $request->input('draft_mark_F'),
                'engine_kw' => $request->input('engine_kw'),
                'exhaust' => $request->input('exhaust'),
                'fire_extinguish' => $request->input('fire_extinguish'),
                'fm_bl' => $request->input('fm_bl'),
                'full_length' => $request->input('full_length'),
                'harbor_gen' => $request->input('harbor_gen'),
                'intake' => $request->input('intake'),
                'layer_2or3' => $request->input('layer_2or3'),
                'lpp' => $request->input('lpp'),
                'me_model' => $request->input('me_model'),
                'me_sno' => $request->input('me_sno'),
                'mold_draft' => $request->input('mold_draft'),
                'official_number' => $request->input('official_number'),
                'pera_sno' => $request->input('pera_sno'),
                'pera_spec' => $request->input('pera_spec'),
                'signal_code' => $request->input('signal_code'),
                'stern_towboat' => $request->input('stern_towboat'),
                'winch_tension' => $request->input('winch_tension'),
            ]);

            // summary2sテーブルの更新
            $ship->summary2s->update([
                'insurance_type' => $request->input('insurance_type'),
                'international_ton' => $request->input('international_ton'),
                'passenger_capacity' => $request->input('passenger_capacity'),
                'rpm_peller100' => $request->input('rpm_peller100'),
                'rpm_peller50' => $request->input('rpm_peller50'),
                'slip_rate100' => $request->input('slip_rate100'),
                'slip_rate50' => $request->input('slip_rate50'),
                'speed100' => $request->input('speed100'),
                'speed50' => $request->input('speed50'),
                'tug_force100' => $request->input('tug_force100'),
                'tug_force50' => $request->input('tug_force50'),
            ]);

            // concernedsテーブルの更新
            $ship->concerneds->update([
                'borrower' => $request->input('borrower'),
                'crew_arrange' => $request->input('crew_arrange'),
                'manager' => $request->input('manager'),
                'operator' => $request->input('operator'),
            ]);
            $userIds = collect($request->input('assignedUsersList'))->pluck('id')->all();
            $ship->users()->sync($userIds);
            //リクエストにないオーナーを削除
            $existingOwnerIds = collect($request->owners)->pluck('id')->filter()->all();
            Ship_owner::where('ship_id', $ship->id)
                ->whereNotIn('id', $existingOwnerIds)
                ->delete();
            // すでに存在するオーナー情報を更新または追加
            foreach ($request->owners as $ownerData) {
                if (isset($ownerData['id'])) {
                    // IDが存在する場合、更新
                    Ship_owner::where('id', $ownerData['id'])
                        ->where('ship_id', $ship->id)
                        ->update($ownerData);
                } else {
                    // IDが存在しない場合、新しいオーナーとして追加
                    $ship->ship_owners()->create($ownerData);
                }
            }
        });
        // dd($ownerData);
        return redirect()->route('ships.show', $ship->id)->with([
            'message' => '更新しました。',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Ship $ship)
    {
        $ship -> delete();

        return to_route('ships.index')
         ->with([
             'message' => '削除しました。',
             'status' => 'denger'
         ]);
    }
}
