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
        /*
         *   You might have your DBMS set to SQLite in your
         *   test env. In which case, this migration will be
         *   skipped since this file executes MySQL code.
         *   View tables aren't really necessary for testing
         *   anyway.
         */
        if( config('database.default') != 'mysql')
            return;

        // pending
        // AS created_by_id
        // AS create_by_username

        DB::statement("
            CREATE OR REPLACE VIEW view_projects
            AS
            SELECT
                projects.id AS id,
                projects.title AS title,
                projects.description AS description,
                _project_types.title AS type,
                _project_timelines.title AS projected_timeline,
                users.id AS manager_id,
                users.username AS manager_username,
                projects.planned_start_date AS start_date,
                projects.planned_end_date AS end_date
            FROM
                projects
                LEFT JOIN users ON projects.manager_id = users.id
                LEFT JOIN _project_types ON projects.type = _project_types.id
                LEFT JOIN _project_timelines ON projects.projected_timeline = _project_timelines.id;
        ");
    }

    public function down()
    {
        if( config('database.default') != 'mysql')
            return;
        Schema::dropIfExists('view_projects');
    }
}
