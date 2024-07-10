<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:list-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all tables in the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tables = DB::select('SHOW TABLES'); // MySQL/MariaDB
        // For PostgreSQL, use: $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\'');

        foreach ($tables as $table) {
            $tableArray = get_object_vars($table);
            $this->info(array_values($tableArray)[0]);
        }
    }
}


