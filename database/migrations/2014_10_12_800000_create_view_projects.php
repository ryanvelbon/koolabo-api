<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateViewProjects extends Migration
{
    /*
     * Incomplete
     *
     * See socialnet project as an example
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_projects
            AS
            SELECT
                projects.title,
                _project_types.title AS type,
                _project_timelines.title AS projected_timeline,
                users.id AS manager_id,
                users.username AS manager_username
            FROM
                projects
                LEFT JOIN users ON projects.manager = users.id
                LEFT JOIN _project_types ON projects.type = _project_types.id
                LEFT JOIN _project_timelines ON projects.projected_timeline = _project_timelines.id;
        ");
    }

    public function down()
    {
        Schema::dropIfExists('view_projects');
    }
}
