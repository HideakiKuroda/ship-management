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
use App\Models\Ship_attachment;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;



class ShipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:view_ship')->only('index', 'show');
        $this->middleware('permission:create_ship')->only('create', 'store');
        // $this->middleware('checkrole')->only('edit', 'update');
        $this->middleware('permission:create_ship')->only('destroy');
    }

    public function index(Request $request)
    {
        $users = User::with('ships')->has('ships')->get();
        $ships = Ship::query()->with(['users:id,name'])->select('id', 'name', 'yard', 'ship_no')->get();
        // dd($users);
        // Log::info('Ship with users loaded.');
        return Inertia::render('Ships/Index', [
            'users' => $users,
            'ships' => $ships,
        ]);
    }
    public function shipfilter(Request $request) {
        $userId =  $request->userId;
        $filtered = Ship::query()->with(['users:id,name'])->select('id', 'name', 'yard', 'ship_no')
        ->UserShip($userId)->get();

        return response()->json($filtered);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $navigationAreas = Navigation_area::select('id', 'name')->get();
        $operatSections = Operat_section::select('id', 'section')->get();

        $departmentIds = [4, 16];  // 抽出したいdepartmentsのIDを配列で指定
        $users = User::whereHas('user_descriptions.departments', function ($query) use ($departmentIds) {
            $query->whereIn('departments.id', $departmentIds);
        })
        ->select('id','name')
        ->get();
        $user = Auth::user();

        // if ($user->hasAnyRole(['admin', 'developer'])) {
        // dd( $navigationAreas,$operatSections,$users);
            return Inertia::render('Ships/Create',[
                'navigationAreas'=>$navigationAreas,
                'operatSections'=>$operatSections,
                'users'=>$users,
            ]);
        // } else {
        //     // 権限がない場合の処理
        //     return response()->json(['error' => '編集権限がありません。'], 403);
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShipRequest $request)
    {
     try {
        /** @var Ship|null */
        $ship = null;
        DB::transaction(function () use ($request, &$ship) {
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
            $ship->schedules()->create();
            $userIds = collect($request->input('assignedUsersList'))->pluck('id')->all();
            $ship->users()->sync($userIds);
        });

        return redirect()->route('ships.show', $ship->id)->with([
            'message' => '新しい船「' . $ship->name . '」を登録しました',
            'status' => 'success'
        ]);
        } catch (\Exception $e) {
            // トランザクション失敗時のリダイレクト
            return redirect()->route('ships.index')->with([
                'message' => '船の登録に失敗しました',
                'status' => 'error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ship $ship)
    {
        try {
            $ship->load('summaries', 'summary2s', 'concerneds','ship_owners','operat_sections','navigation_areas','ship_attachments.users');
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

            $departmentIds = [4, 16];  // 抽出したいdepartmentsのIDを配列で指定
            $users = User::whereHas('user_descriptions.departments', function ($query) use ($departmentIds) {
                $query->whereIn('departments.id', $departmentIds);
            })
            ->select('id','name')
            ->get();

            $ship->load('summaries', 'summary2s', 'concerneds','ship_owners','operat_sections','navigation_areas','users','ship_attachments.users','schedules');
            //   dd($ship);
            $userAuth = Auth::user();
            $permissions = Auth::user()->getAllPermissions()->pluck('name');
            // ($userAuth->can('edit_ship') && $userAuth->can('assign_ship')
            if ($userAuth->hasAnyRole(['admin', 'developer']) || $ship->users->contains($userAuth)) {
                return Inertia::render('Ships/Edit',[
                    'ship' => $ship,
                    'navigationAreas'=>$navigationAreas,
                    'operatSections'=>$operatSections,
                    'users'=>$users,
                    'permissions'=>$permissions,
                    'userAuth'=>$userAuth,
                ]);
            } else {
                // 権限がない場合の処理
                return response()->json(['error' => '編集権限がありません。'], 403);
            }
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
     try {
        $ship = Ship::findOrFail($ship->id);
        DB::transaction(function () use ($request, &$ship) {
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
                'issueInspCert' => $request->input('issueInspCert'),
                'expiry_date' => $request->input('expiry_date'),
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

            // dd($request);
            foreach ($request->attachments ?? [] as $attachData) {
                Ship_attachment::where('id', $attachData['id'])
                    ->where('ship_id', $ship->id)
                    ->update(['title' => $attachData['title']]);
            }

            $userIds = collect($request->input('assignedUsersList'))->pluck('id')->all();
            // dd($userIds );
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

    public function upload(Request $request, $id)
    {
        $ship = Ship::findOrFail($id);
        $files = $request->file('files');
        $uploadedFiles = [];
        $today=now();
        // dd($files);
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

            // $filename = $file->storeAs("/ships/{$id}", $originalName);
            // dd($filename);
            $filename = Storage::put("ships/{$id}", $file);
            $users =  Auth::user('id','name');
            // データベースに記録
            $newFile = Ship_attachment::create([
                'ship_id' => $id,
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
        $attachment = Ship_attachment::where('id', $attachData)->first();
        // dd($attachment);
        if ($attachment) {
            // データベースからレコードを削除
            Ship_attachment::where('id', $attachData)->delete();

            // ストレージからファイルを削除
            Storage::delete($attachment->filename);

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

    //ダウンロードファイル（バイナリー）の取得
    public function downloadFile(Request $request, $id)
    {
        $attachData = $request->input('attachmentId');
        $attachment = Ship_attachment::where('id', $attachData)->first();

        if ($attachment) {
            $filePath = Storage::path($attachment->filename);
            return response()->download($filePath, $attachment->originname);
        }

        return response()->json(['message' => 'File not found'], 404);
    }

    //ダウンロードファイルの名前の取得
    public function getFileName(Request $request, $id)
    {
        $attachData = $request->input('attachmentId');
        $attachment = Ship_attachment::where('id', $attachData)->first();

        if ($attachment) {
            return response()->json(['filename' => $attachment->originname]);
        }

        return response()->json(['message' => 'File not found'], 404);
    }

    function calculateInspectionDates(UpdateShipRequest $request, Ship $ship) {
        try {
            // dd($ship);
            $ship = Ship::findOrFail($ship->id);
            DB::transaction(function () use ($request, &$ship) {
                $shipType = $ship->navigation_area_id;
                $baseDate = $ship->expiry_date;
                $dates = new DateTime($baseDate);
                $inspectionPeriods = [];
        if($baseDate!= null && $shipType!= null ){

            if ($shipType == 1) {
                $dates->modify('-72 months')->format('Y-m-d');
                $inspectionPeriods['midterm1'] = [
                    'start' => $dates->modify('+33 months')->format('Y-m-d'),
                    'end' => $dates->modify('+6 months')->format('Y-m-d'),
                ];

                $inspectionPeriods['regular1'] = [
                    'end' => $dates->modify('+33 months')->format('Y-m-d'),
                    'start' => $dates->modify('-3 months')->format('Y-m-d'),
                ];

                $inspectionPeriods['midterm2'] = [
                    'start' => $dates->modify('+36 months')->format('Y-m-d'),
                    'end' => $dates->modify('+6 months')->format('Y-m-d'),
                ];

                $inspectionPeriods['regular2'] = [
                    'end' => $dates->modify('+33 months')->format('Y-m-d'),
                    'start' => $dates->modify('-3 months')->format('Y-m-d'),
                ];

            } elseif ($shipType !== 1) {
                $dates->modify('-60 months')->format('Y-m-d');
                $inspectionPeriods['midterm1'] = [
                    'start' => $dates->modify('+21 months')->format('Y-m-d'),
                    'end' => $dates->modify('+18 months')->format('Y-m-d'),
                ];

                $inspectionPeriods['regular1'] = [
                    'end' => $dates->modify('+21 months')->format('Y-m-d'),
                    'start' => $dates->modify('-3 months')->format('Y-m-d'),
                ];


                $inspectionPeriods['midterm2'] = [
                    'start' => $dates->modify('+24 months')->format('Y-m-d'),
                    'end' => $dates->modify('+18 months')->format('Y-m-d'),
                ];

                $inspectionPeriods['regular2'] = [
                    'end' => $dates->modify('+21 months')->format('Y-m-d'),
                    'start' => $dates->modify('-3 months')->format('Y-m-d'),
                ];
                } else {
                    throw new Exception('Invalid ship type provided.');
                }
                $ship->schedules->update([
                    'interim_start1' => $inspectionPeriods['midterm1']['start'],
                    'interim_dline1' => $inspectionPeriods['midterm1']['end'],
                    'Periodic_start1' => $inspectionPeriods['regular1']['start'],
                    'Periodic_dline1' => $inspectionPeriods['regular1']['end'],
                    'interim_start2' => $inspectionPeriods['midterm2']['start'],
                    'interim_dline2' => $inspectionPeriods['midterm2']['end'],
                    'Periodic_start2' => $inspectionPeriods['regular2']['start'],
                    'Periodic_dline2' => $inspectionPeriods['regular2']['end'],
                ]);
             }
            });
            // dd($ownerData);
            return redirect()->back()->with([
                'message' => '更新しました。',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            // トランザクション失敗時のリダイレクト
            return redirect()->back()->with([
                'message' => '更新できませんでした。航海区域、定期検査期限の入力を確認ください。'. $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}
