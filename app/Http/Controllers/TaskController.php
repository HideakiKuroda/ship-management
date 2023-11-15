<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\Ship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
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
    public function create(Request $request)
    {
       try {
            $projectId = $request->query('project_id');
            $project = Project::select('id', 'name','ship_id')->with(['ships:id,name','users:id,name'])->find($projectId);
            
            $loginUser = Auth::user('id','name'); 
            dd($project);
            return Inertia::render('Tasks/Create', [
                'project' => $project, 
            ]);
            } catch (\Throwable $e) {
            Log::error($e->getMessage());
           // dd($e->getMessage());
        }

        // dd($users);

    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        /** @var Task|null */
        $task = null;
        try {
            DB::transaction(function () use ($request, &$project) {
                // dd($request);
            $task = Task::Create([
                'project_id' => $request->input('projectId'),
                'name' => $request->input('name'),
                'color_id'=> $request->input('color_id'),
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
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
