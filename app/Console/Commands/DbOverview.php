<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DbOverview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rvb:db-overview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List number of records for each model';

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
        $models = ["User", "Project", "Job"];

        $mask = "|%-20.20s |%-8.8s |\n";
        printf($mask, 'Model', 'Count');
        foreach($models as $model) {
            $x = "\App\Models\\$model";
            printf($mask, $model, $x::count());
        }
    }
}
