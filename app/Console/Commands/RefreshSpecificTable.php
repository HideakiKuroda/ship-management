<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class RefreshSpecificTable extends Command
{
    protected $signature = 'table:refresh {table} {--seed=}';
    protected $description = 'Refresh a specific table';

    public function handle()
    {
        $table = $this->argument('table');
        $seeder = $this->option('seed');

        if (!Schema::hasTable($table)) {
            $this->error("Table '{$table}' does not exist.");
            return 1;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info("Table '{$table}' has been truncated.");

        if ($seeder) {
            $this->call('db:seed', ['--class' => $seeder]);
        }

        return 0;
    }
}
// class RefreshSpecificTable extends Command
// {
//     /**
//      * The name and signature of the console command.
//      *
//      * @var string
//      */
//     protected $signature = 'app:refresh-specific-table';

//     /**
//      * The console command description.
//      *
//      * @var string
//      */
//     protected $description = 'Command description';

//     /**
//      * Execute the console command.
//      */
//     public function handle()
//     {
//         //
//     }
// }
