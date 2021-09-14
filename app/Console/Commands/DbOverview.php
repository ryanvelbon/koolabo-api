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
        // $this->goo();
        // return;

        $models = ['Language', 'Skill', 'Topic', 'User', 'UserSkill', 'UserLanguage', 'Project', 'ProjectLike', 'Job', 'JobVacancy'];

        $mask = "|%-20.20s |%-8.8s |\n";
        printf($mask, 'Model', 'Count');
        foreach($models as $model) {
            $x = "\App\Models\\$model";
            printf($mask, $model, $x::count());
        }

        // pivot tables - average per user/project
    }

    // pending
    private function goo()
    {
        $files = [];
        foreach ([
            app_path('Models/*.php'),
            app_path('*.php'),
        ] as $path) {
            $folder = dirname($path);
            if (!file_exists($folder) || !is_dir($folder)) {
                continue;
            }
            $files = array_merge($files, glob($path));
        }

        $models = [];

        foreach ($files as $file) {
            $fp = fopen($file, 'r');
            $class = $buffer = '';
            $i = 0;
            while (!$class) {
                if (feof($fp))
                    break;
                $buffer .= fread($fp, 512);
                $tokens = token_get_all($buffer);
                if (strpos($buffer, '{') === false)
                    continue;
                for (; $i < count($tokens); $i++) {
                    if ($tokens[$i][0] === T_CLASS) {
                        for ($j = $i + 1; $j < count($tokens); $j++) {
                            if ($tokens[$j] === '{') {
                                if (preg_match('#namespace (.*?);#', substr($buffer, 0, $tokens[$i + 2][0]), $namespace)) {
                                    $class = '\\' . $namespace[1] . '\\' . $tokens[$i + 2][1];
                                } else {
                                    $class = $tokens[$i + 2][1];
                                }
                            }
                        }
                    }
                }
            }
            if (
                $class
                && class_exists($class)
                && is_subclass_of($class, \Illuminate\Database\Eloquent\Model::class)
            ) {
                $models[] = $class;
            }
        }

        // Now $models is a list of class names, with their namespace.
        foreach($models as $class) {
            $model = new $class();
            // error_log($model);
            error_log($model->getTable());
        }
    }
}
