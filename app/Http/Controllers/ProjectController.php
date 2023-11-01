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
use App\Models\Pro_description;
use App\Models\Pro_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis as LaravelRedis;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use app\Exceptions\Handler as Exception;

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
        
        $projects = $queryAll->paginate(12)
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
        try {
            $project->load('users','pro_attachments.users','tasks','pro_categories','ships','pro_descriptions.users');
            $loginUser = Auth::user('id','name'); 
            // dd($project);
            return Inertia::render('Projects/Show',['project' => $project,'loginUser'=>$loginUser]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
           // dd($e->getMessage());
        }
   }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        try {
            $departmentIds = [4, 16];  // 抽出したいdepartmentsのIDを配列で指定
            $users = User::whereHas('user_descriptions.departments', function ($query) use ($departmentIds) {
                $query->whereIn('departments.id', $departmentIds);
            })
            ->select('id','name')
            ->get();
            $ships = Ship::select('id','name')->get();
            $categories = Pro_category::select('id','name')->get();
            $project->load('users','pro_attachments.users','tasks','pro_categories','ships','pro_descriptions.users');
                // dd($project);
            $loginUser = Auth::user('id','name');  
            // dd($loginUser);  
            return Inertia::render('Projects/Edit',[
                'project' => $project,
                'users' => $users,
                'ships' => $ships,
                'categories'=>$categories,
                'loginUser'=>$loginUser
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
           // dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
    //    dd($request);
        $project = Project::findOrFail($project->id);
        LaravelRedis::transaction(function () use ($request, &$project) {
        
        $project->update([
            'name'=>$request->input('name'),
            'pro_category_id'=>$request->input('pro_category_id'),
            'start_date'=>$request->input('start_date'),
            'end_date'=>$request->input('end_date'),
            'completion'=>$request->input('completion'),
            'date_of_issue'=>$request->input('date_of_issue'),  
            
        ]);    
            
        foreach ($request->attachments ?? [] as $attachData) {
        Pro_attachment::where('id', $attachData['id'])
            ->where('project_id', $project->id)
            ->update(['title' => $attachData['title']]);
        }

        $userIds = collect($request->input('assignedUsersList'))->pluck('id')->all();
        $project->users()->sync($userIds);

        // メモの同期
        if ($request->has('deletedMessageIds')) {
            $deletedMessageIds = $request->input('deletedMessageIds');
            foreach ($deletedMessageIds as $id) {
                Pro_description::where('id', $id)->delete();
            }
        }
        if ($request->has('assignedMassagesList')) {
            $assignedMassagesList = $request->input('assignedMassagesList');
            foreach ($assignedMassagesList as $messageData) {
                // メモの更新または作成 
                $project->pro_descriptions()->updateOrCreate(
                    ['id' => $messageData['id'] ?? null],
                    [
                    'project_id' => $messageData['project_id'],
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
    
    // return redirect()->route('projects.edit', $project->id)->with([
    //     'message' => '更新しました。',
    //     'status' => 'success'
    ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }

    public function upload(Request $request, $id)
    {
        $project = Project::findOrFail($id);
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

        // $filename = $file->storeAs("/projects/{$id}", $originalName);
        // dd($filename);
        $filename = Storage::put("projects/{$id}", $file);
        $users =  Auth::user('id','name');
        // データベースに記録
        $newFile = Pro_attachment::create([
            'project_id' => $id,
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
        $attachment = Pro_attachment::where('id', $attachData)->first();
        //dd($attachment);
        if ($attachment) {
            // データベースからレコードを削除
            Pro_attachment::where('id', $attachData)->delete();
            // ストレージからファイルを削除
            Storage::delete($attachment->filename);
            // return redirect()->route('projects.edit', $id)->with([
            return response()->json([
                'message' => 'ファイルを削除しました。',
                'status' => 'success'
            ]);
        }
        // return redirect()->route('projects.edit', $id)->with([
            return response()->json([
                'message' => 'ファイルの削除に失敗しました。',
                'status' => 'error'
            ]);
    }

    //ダウンロードファイルの取得
    public function downloadFile(Request $request, $id)
    {
        $attachData = $request->input('attachmentId');
        $attachment = Pro_attachment::where('id', $attachData)->first();
        // dd($attachment);
        if ($attachment) {
            $filePath = Storage::path($attachment->filename);
            $headers = [
                'Content-Disposition' => 'inline; filename="' . $attachment->originname . '"'
            ];
            return response()->file($filePath, $headers);
            // return response()->download($filePath, $attachment->originname);
            
        }
        
        return response()->json(['message' => 'File not found'], 404);
    }
    //ダウンロードファイルの名前の取得
    public function getFileName(Request $request, $id)
    {
        $attachData = $request->input('attachmentId');
        $attachment = Pro_attachment::where('id', $attachData)->first();

        if ($attachment) {
            return response()->json(['filename' => $attachment->originname]);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
}
