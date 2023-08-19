<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipRequest;
use App\Http\Requests\UpdateShipRequest;
use App\Models\Ship;
use App\Models\Ship_owner;
use App\Models\Summary;
use App\Models\Summary2;
use App\Models\User;
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

        dd( $users, $ships,);
        
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipRequest $request, Ship $ship)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ship $ship)
    {
        //
    }
}
