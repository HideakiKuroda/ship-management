<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Inertia\Inertia;
use App\Models\Ship;
use App\Models\User;
use App\Models\Pro_assignment;
use App\Models\Pro_attachment;
use App\Models\Department;
use App\Models\Pro_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis as LaravelRedis;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $userId = auth()->id();
        $EndOrNo = 0;
        $shipId = null;
        $crtDate = null;
        $crtAddDate = null;
        $endDate = null;
        $endAddDate = null;
        //部門　18　の人だけを抽出
        $users = User::whereHas('user_descriptions.departments', function ($query) {
            $query->where('departments.id', 18);
        })->select('id', 'name')->get();
        $ships = Ship::select('id','name','yard','ship_no')->get();
        $queryAll = Project::query()->with(['ships:id,name','pro_categories:id,name','users:id,name'])
        ->EndOrNoProject($EndOrNo)
        ->ShipProject($shipId)
        ->DateCreateProject($crtDate,$crtAddDate)
        ->DateEndProject($endDate,$endAddDate)
        ->UserProject($userId);
        Log::info($queryAll->toSql()); 
        
        $projects = $queryAll->paginate(20);
        // ->withQueryString();

        
        return Inertia::render('Projects/Index', [
            'ships' => $ships,
            'currentUser' => $userId, 
            'users' => $users,
            'projects' => $projects,
        ]);
    }
    public function indexfilter(Request $request) {
        $userId =  $request->userId;
        // dd($userId);
        $EndOrNo = 0;
        $shipId = $request->shipId;
        $crtDate = null;
        $crtAddDate = null;
        $endDate = null;
        $endAddDate = null;
        $filtered = Project::query()->with(['ships:id,name','pro_categories:id,name','users:id,name'])
        ->UserProject($userId)
        ->ShipProject($shipId)
        ->EndOrNoProject($EndOrNo)
        ->DateCreateProject($crtDate,$crtAddDate)
        ->DateEndProject($endDate,$endAddDate)
        ->paginate(20);
        // ->withQueryString();
        // dd($filtered); 
        return response()->json($filtered);
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
    public function store(StoreProjectRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load('ships', 'pro_categories', 'users','pro_attachments.users');
            // dd($ship);
            return Inertia::render('Projects/Show',['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
