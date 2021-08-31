<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use App\Models\Craft;

class JsonCrafts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rvb:json-crafts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $crafts = Craft::all()->makeHidden(['is_root_category']);
      $tree = buildTree($crafts->toArray());
      $json = json_encode($tree, JSON_PRETTY_PRINT);

      $path = dirname(__DIR__, 3) . "/database/snapshots/";
      if(!File::exists($path)) {
        File::makeDirectory($path);
      }
      $file = fopen($path."crafts.json", "w") or die("Unable to open file!");
      fwrite($file, $json);
      fclose($file);
      $this->line("<fg=green>Generated nested JSON array for `crafts` table.</>");
    }
}
