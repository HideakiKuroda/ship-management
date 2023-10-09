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
        ->EndOrNoProject($EndOrNo) //0:未完了　1:完了
        ->ShipProject($shipId)
        ->DateCreateProject($crtDate,$crtAddDate)
        ->DateEndProject($endDate,$endAddDate)
        ->UserProject($userId);
        // Log::info($queryAll->toSql()); 
        
        $projects = $queryAll->paginate(20)
        ->withQueryString();

        
        return Inertia::render('Projects/Index', [
            'ships' => $ships,
            'currentUser' => $userId, 
            'users' => $users,
            'projects' => $projects,
        ]);
    }
    public function indexfilter(Request $request) {
        $userId =  $request->userId;
        $EndOrNo = $request->EndOrNo;
        $shipId = $request->shipId;
        $crtDate = $request->crtDate;
        $crtAddDate = $request->crtAddDate;
        $endDate = $request->endDate;
        $endAddDate = $request->endAddDate;
        $query = Project::query()->with(['ships:id,name','pro_categories:id,name','users:id,name'])
        ->UserProject($userId)
        ->ShipProject($shipId)
        ->EndOrNoProject($EndOrNo)
        ->DateCreateProject($crtDate,$crtAddDate)
        ->DateEndProject($endDate,$endAddDate);
        // Log::info('crtAddDate value:', ['value' => $crtAddDate]);
        // Log::info('endAddDate value:', ['value' => $endAddDate]);
        // Log::info($query->toSql()); 
        $filtered = $query->Paginate(20);    
       
        // ->withQueryString();
        return response()->json($filtered);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $userId = (object)[
            'id' => $user->id,
            'name' => $user->name
        ];
        $ships = Ship::select('id','name','yard','ship_no')->get();
        $categories = Pro_category::select('id','name')->get();
        $departmentIds = [4, 16];  // 抽出したいdepartmentsのIDを配列で指定
        $users = User::whereHas('user_descriptions.departments', function ($query) use ($departmentIds) {
            $query->whereIn('departments.id', $departmentIds);
        })
        ->select('id','name')
        ->get();

        // dd($users);
        return Inertia::render('Projects/Create', [
            'ships' => $ships,
            'currentUser' => $userId, 
            'categories' => $categories,
            'users'=>$users,
        ]);

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        /** @var Project|null */
        $project = null;
        try {
            LaravelRedis::transaction(function () use ($request, &$project) {
                // dd($request);
            $project = Project::Create([
                'ship_id' => $request->input('shipId'),
                'name' => $request->input('name'),
                'pro_category_id' => $request->input('pro_category_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);
            
            // assignedUsersList から user_id を取り出して、$userIds に配列として格納
            $userIds = collect($request->input('assignedUsersList'))->pluck('id')->all();
            // syncメソッドは、多対多リレーションシップを同期するためのメソッドです。
            //これにより、与えられたIDのリスト$userIdsに基づいて中間テーブルのレコードが更新されます。            
            $project->users()->sync($userIds);
        });
        return redirect()->route('projects.show', $project->id)->with([
            'message' => '新しいproject「' . $project->name . '」を登録しました',
            'status' => 'success'
        ]);   
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with([
                'message' => 'project「' . $project->name . '」の登録に失敗しました',
                'status' => 'error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load('users','pro_attachments.user','tasks','pro_categories','ships','pro_descriptions');
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
