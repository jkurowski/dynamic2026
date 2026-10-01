<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CompareDatabases extends Command
{
    protected $signature = 'db:compare';
    protected $description = 'Compare local and remote MySQL databases and show differences';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Connect to local and remote databases
        $localDB = DB::connection('mysql');
        $remoteDB = DB::connection('mysql_remote');

        // Fetch table names from both databases
        $localTables = $this->getTableNames($localDB, env('DB_DATABASE'));
        $remoteTables = $this->getTableNames($remoteDB, env('DB_DATABASE_REMOTE'));

        // Compare tables
        $this->compareTables($localDB, $remoteDB, $localTables, $remoteTables);
    }

    private function getTableNames($dbConnection, $databaseName)
    {
        $tables = $dbConnection->select('SHOW TABLES');
        $tableNameKey = 'Tables_in_' . $databaseName; // Construct the key based on the database name
        return array_map(fn($table) => $table->$tableNameKey, $tables);
    }

    private function compareTables($localDB, $remoteDB, $localTables, $remoteTables)
    {
        // Tables missing in remote database
        $missingInRemote = array_diff($localTables, $remoteTables);
        if (!empty($missingInRemote)) {
            $this->info("Tables missing in remote database:");
            foreach ($missingInRemote as $table) {
                $this->info($table);
            }
        } else {
            $this->info("All tables from the local database are present in the remote database.");
        }

        // Tables missing in local database
        $missingInLocal = array_diff($remoteTables, $localTables);
        if (!empty($missingInLocal)) {
            $this->info("Tables missing in local database:");
            foreach ($missingInLocal as $table) {
                $this->info($table);
            }
        } else {
            $this->info("All tables from the remote database are present in the local database.");
        }

        // Compare columns for common tables
        $commonTables = array_intersect($localTables, $remoteTables);
        foreach ($commonTables as $table) {
            $localColumns = $this->getTableColumns($localDB, $table);
            $remoteColumns = $this->getTableColumns($remoteDB, $table);

            $columnDiff = array_diff($localColumns, $remoteColumns);

            if (!empty($columnDiff)) {
                $this->info("Differences in table $table:");
                foreach ($columnDiff as $column) {
                    $this->info("Column in local but not in remote: $column");
                }
            }
        }
    }

    private function getTableColumns($dbConnection, $table)
    {
        $columns = $dbConnection->select("SHOW COLUMNS FROM $table");
        return array_map(fn($column) => $column->Field, $columns);
    }
}