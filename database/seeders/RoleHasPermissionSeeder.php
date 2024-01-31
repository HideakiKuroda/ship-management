<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_has_permissions')->insert([
            //3開発者 すべての権限
            ['permission_id' => 1,'role_id'=> 1],
            ['permission_id' => 2,'role_id'=> 1],
            ['permission_id' => 3,'role_id'=> 1],
            ['permission_id' => 4,'role_id'=> 1],
            ['permission_id' => 5,'role_id'=> 1],
            ['permission_id' => 6,'role_id'=> 1],
            ['permission_id' => 7,'role_id'=> 1],
            ['permission_id' => 8,'role_id'=> 1],
            ['permission_id' => 9,'role_id'=> 1],
            ['permission_id' => 10,'role_id'=> 1],
            ['permission_id' => 11,'role_id'=> 1],
            ['permission_id' => 12,'role_id'=> 1],
            ['permission_id' => 13,'role_id'=> 1],
            ['permission_id' => 14,'role_id'=> 1],
            ['permission_id' => 15,'role_id'=> 1],
            //2プロジェクトマネージャー
            ['permission_id' => 1,'role_id'=> 2],
            ['permission_id' => 2,'role_id'=> 2],
            ['permission_id' => 3,'role_id'=> 2],
            ['permission_id' => 4,'role_id'=> 2],
            ['permission_id' => 5,'role_id'=> 2],
            ['permission_id' => 6,'role_id'=> 2],
            ['permission_id' => 7,'role_id'=> 2],
            ['permission_id' => 8,'role_id'=> 2],
            ['permission_id' => 9,'role_id'=> 2],
            ['permission_id' => 10,'role_id'=> 2],
            ['permission_id' => 12,'role_id'=> 2],
            ['permission_id' => 15,'role_id'=> 2],
            //3管理者
            ['permission_id' => 1,'role_id'=> 3],
            ['permission_id' => 2,'role_id'=> 3],
            ['permission_id' => 4,'role_id'=> 3],
            ['permission_id' => 5,'role_id'=> 3],
            ['permission_id' => 6,'role_id'=> 3],
            ['permission_id' => 8,'role_id'=> 3],
            ['permission_id' => 9,'role_id'=> 3],
            ['permission_id' => 10,'role_id'=> 3],
            ['permission_id' => 12,'role_id'=> 3],
            ['permission_id' => 13,'role_id'=> 3],
            ['permission_id' => 14,'role_id'=> 3],
            ['permission_id' => 15,'role_id'=> 3],
            //タスクマネージャ
            ['permission_id' => 4,'role_id'=> 4],
            ['permission_id' => 5,'role_id'=> 4],
            ['permission_id' => 6,'role_id'=> 4],
            ['permission_id' => 7,'role_id'=> 4],
            ['permission_id' => 8,'role_id'=> 4],
            ['permission_id' => 12,'role_id'=> 4],
            ['permission_id' => 15,'role_id'=> 4],
            //閲覧者
            ['permission_id' => 4,'role_id'=> 5],
            ['permission_id' => 8,'role_id'=> 5],
            ['permission_id' => 12,'role_id'=> 5],
        ]);
    }
}
            //1 'developer' 　開発者
            //2 'projectManager' プロジェクトマネージャー
            //3 'admin'     管理者
            //4 'taskManager'  タスクマネージャー
            //5 'viewer'  閲覧者
            //6 'tempuser'  一時ユーザー

            //1  'create_project'--プロジェクト作成
            //2  'edit_project'--プロジェクト編集
            //3  'assign_project'--プロジェクト割り当て
            //4  'view_project'--プロジェクト参照
            //5  'create_task'
            //6  'edit_task'
            //7  'assign_task'
            //8  'view_task'
            //9  'create_ship'
            //10  'edit_ship'
            //11  'assign_ship'
            //12  'view_ship'
            //13  'create_user'
            //14  'edit_user'
            //15  'view_user'
