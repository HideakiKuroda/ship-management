<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSummaryRequest;
use App\Http\Requests\UpdateSummaryRequest;
use App\Models\Summary;
use Inertia\Inertia;

class SummaryController extends Controller
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
    public function store(StoreSummaryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Summary $summary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Summary $summary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSummaryRequest $request, Summary $summary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Summary $summary)
    {
        //
    }
}
