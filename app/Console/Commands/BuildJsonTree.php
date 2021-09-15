<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

// use App\Models\Craft;

/*
 * Our database consists of a number of hierarchical tables.
 *
 * Hierarchical tables: `genres`, `crafts`
 *
 * This artisan command transforms such data into a JSON nested array.
 */

class BuildJsonTree extends Command
{
    protected $signature = 'rvb:json {table}';

    protected $description = 'Generates a nested JSON array from specified table';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $table = $this->argument('table');
        $records = DB::table($table)->get(['id', 'title', 'parent_id']);
        $tree = buildTree(json_decode(json_encode($records), true));
        $json = json_encode($tree, JSON_PRETTY_PRINT);
        $path = dirname(__DIR__, 3) . "/database/json/";

        if(!File::exists($path)) {
            File::makeDirectory($path);
        }

        $file = fopen($path."{$table}.json", "w") or die("Unable to open file!");
        fwrite($file, $json);
        fclose($file);
        $this->line("<fg=green>Generated nested JSON array for `{$table}` table.</>");
    }
}
