<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\Ship;
use App\Models\User;
use App\Models\Task_attachment;
use App\Models\Task_description;
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
    public function index(Task $task)
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
        // $projectId = $task->query('project_id');
        // $project = Project::select('id', 'name','ship_id')->with(['ships:id,name','users:id,name'])->find($projectId);


        $queryAll = Task::query()->with(['projects.ships:id,name','projects.users:id,name'])
        ->EndOrNoTask($EndOrNo) //0:未完了　1:完了
        ->ShipTask($shipId)
        ->DateCreateTask($crtDate,$crtAddDate)
        ->DateEndTask($endDate,$endAddDate)
        ->UserTask($userId);
        // Log::info($queryAll->toSql()); 
        
        $tasks = $queryAll->paginate(12)
        ->withQueryString();

        
        return Inertia::render('Tasks/Index', [
            'ships' => $ships,
            'currentUser' => $userId, 
            'users' => $users,
            'tasks' => $tasks
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
        $query = Task::query()->with(['projects.ships:id,name','projects.users:id,name'])
        ->UserTask($userId)
        ->ShipTask($shipId)
        ->EndOrNoTask($EndOrNo)
        ->DateCreateTask($crtDate,$crtAddDate)
        ->DateEndTask($endDate,$endAddDate);
        //  Log::info('crtDate value:', ['value' => $crtDate]);
        //  Log::info('crtAddDate value:', ['value' => $crtAddDate]);
        //  Log::info('endDate value:', ['value' => $endDate]);
        //  Log::info('endAddDate value:', ['value' => $endAddDate]);
        //  Log::info($query->toSql()); 
        $filtered = $query->Paginate(12);    
       
        // ->withQueryString();
        return response()->json($filtered);
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
            return Inertia::render('Tasks/Create', [
                'project' => $project, 
            ]);
            } catch (\Throwable $e) {
            Log::error($e->getMessage());
           // dd($e->getMessage());
        }
    }

    public function subCreate(Request $request)
    {
        try {
            $projectId = $request->query('project_id');
            $project = Project::select('id', 'name','ship_id')->with(['ships:id,name','users:id,name'])->find($projectId);
            
            $loginUser = Auth::user('id','name'); 
            // dd($project);
            return Inertia::render('Tasks/Create', [
                'project' => $project, 
            ]);
            } catch (\Throwable $e) {
            Log::error($e->getMessage());
           // dd($e->getMessage());
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        /** @var Task|null */
        $task = null;
        try {
            DB::transaction(function () use ($request,&$task) {
                // dd($request);
            $task = Task::Create([
                'project_id' => $request->input('projectId'),
                'name' => $request->input('name'),
                'color_id'=> $request->input('color'),
                'end_date' => $request->input('end_date'),
                'deadline' => $request->input('deadline'),
                'priority' => $request->input('priority'),
            ]);
         });
        //  dd($task);
        return redirect()->route('tasks.edit', $task->id)->with([
            'message' => '新しいtask「' . $task->name . '」を登録しました',
            'status' => 'success'
        ]);   
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with([
                'message' => 'task の登録に失敗しました',
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
        // dd($task);
        try {
            $task->load('subtasks','task_attachments.users','task_descriptions.users','projects');
            $project = Project::select('id', 'name','ship_id')->with(['ships:id,name','users:id,name'])->find($task->project_id);
            $loginUser = Auth::user('id','name'); 
            return Inertia::render('Tasks/Edit',[
                'task'=>$task,
                'loginUser'=>$loginUser,
                'project'=>$project
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
           dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        
    try {
        $task = Task::findOrFail($task->id);
        DB::transaction(function () use ($request, &$task) {
        $task->update([
            'name' => $request->input('name'),
            'color_id'=> $request->input('color'),
            'end_date' => $request->input('end_date'),
            'deadline' => $request->input('deadline'),
            'priority' => $request->input('priority'),
        ]);    
        foreach ($request->attachments ?? [] as $attachData) {
        Task_attachment::where('id', $attachData['id'])
            ->where('task_id', $task->id)
            ->update(['title' => $attachData['title']]);
            // dd($attachData['title']);
        }
        // メモの同期
        if ($request->has('deletedMessageIds')) {
            $deletedMessageIds = $request->input('deletedMessageIds');
            foreach ($deletedMessageIds as $id) {
                Task_description::where('id', $id)->delete();
            }
        }
        if ($request->has('assignedMassagesList')) {
            $assignedMassagesList = $request->input('assignedMassagesList');
            foreach ($assignedMassagesList as $messageData) {
                // メモの更新または作成 
                $task->task_descriptions()->updateOrCreate(
                    ['id' => $messageData['id'] ?? null],
                    [
                    'task_id' => $messageData['task_id'],
                    'memo' => $messageData['memo'],
                    'user_id' => $messageData['users']['id'],
                    ]
                );
            }
        }
        
    });
    return redirect()->back()->withInput()->with([
        'message' => '更新しました。',
        'status' => 'success'
    ]);

    } catch (\Exception $e) {
        Log::error($e);
        return redirect()->back()->withInput()->with([
            'message' => '更新に失敗しました',
            'status' => 'error'
    ]);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    //ダウンロードファイルの取得
    public function upload(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $files = $request->file('files');
        $uploadedFiles = [];
        $today=now();
        //  dd($files);
        foreach ($files as $file) {
        $originalName = $file->getClientOriginalName();
        $filetype = pathinfo($originalName, PATHINFO_EXTENSION);
        // dd($filetype);
        $icon = '';
        if ($filetype === 'xls'){$icon='/images/excel_xls_spreadsheet.png';}
        elseif ($filetype === 'xlsx'){$icon='/images/excel_xls_spreadsheet.png';}
        elseif ($filetype === 'xlsm'){$icon='/images/excel_xls_spreadsheet.png';}
        elseif ($filetype === 'doc'){$icon='/images/word_document.png';}
        elseif ($filetype === 'docx'){$icon='/images/word_document.png';}
        elseif ($filetype === 'pdf'){$icon='/images/adobe_acrobat_pdf.png';}
        elseif ($filetype === 'jpg'){$icon='/images/picture_image_photo_file.png';}
        elseif ($filetype === 'zip'){$icon='/images/zip_file_winzip.png';}
        elseif ($filetype === 'pptx'){$icon='/images/powerpoint_document.png';}
        elseif ($filetype === 'pptm'){$icon='/images/powerpoint_document.png';}
        else{$icon='/images/text_document.png';};
        // dd($filename);
        $filename = Storage::put("tasks/{$id}", $file);
        $users =  Auth::user('id','name');
        // データベースに記録
        $newFile = Task_attachment::create([
            'task_id' => $id,
            'filename' => $filename,
            'originname' => $originalName,
            'user_id' =>  $users->id,
            'icon' => $icon,
        ]);
        // $uploadedFiles 配列に追加する前に user_name と created_at を追加
        $newFile->users->name = $users->name;
        $newFile->created_at = $today->toDateTimeString(); 
        $newFile->title = '';
        $uploadedFiles[] = $newFile; // データベースに保存された新しいファイルの情報を配列に追加
        }
        return response()->json([
            'message' => 'ファイルをアップロードしました。',
            'status' => 'success',
            'uploadedFiles' => $uploadedFiles ,
        ]);
    }
    public function deleteFile(Request $request, $id)
    {
        $attachData = $request->input('attachmentId');
        $attachment = Task_attachment::where('id', $attachData)->first();
        //dd($attachment);
        if ($attachment) {
            // データベースからレコードを削除
            Task_attachment::where('id', $attachData)->delete();
            // ストレージからファイルを削除
            Storage::delete($attachment->filename);
            // return redirect()->route('tasks.edit', $id)->with([
            return response()->json([
                'message' => 'ファイルを削除しました。',
                'status' => 'success'
            ]);
        }
        // return redirect()->route('tasks.edit', $id)->with([
            return response()->json([
                'message' => 'ファイルの削除に失敗しました。',
                'status' => 'error'
            ]);
    }
    //アップロード＆ダウンロードファイルの操作
    public function downloadFile(Request $request, $id)
    {
        $attachData = $request->input('attachmentId');
        $attachment = Task_attachment::where('id', $attachData)->first();
        // dd($attachment);
        if ($attachment) {
            $filePath = Storage::path($attachment->filename);
            $headers = [
                'Content-Disposition' => 'inline; filename="' . $attachment->originname . '"'
            ];
            return response()->file($filePath, $headers);
        }
        return response()->json(['message' => 'File not found'], 404);
    }
    //ダウンロードファイルの名前の取得
    public function getFileName(Request $request, $id)
    {
        $attachData = $request->input('attachmentId');
        $attachment = Task_attachment::where('id', $attachData)->first();

        if ($attachment) {
            return response()->json(['filename' => $attachment->originname]);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
}
