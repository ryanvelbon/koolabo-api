<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateViewJobs extends Migration
{
    public function up()
    {
        if( config('database.default') != 'mysql')
            return;

        DB::statement("
            CREATE OR REPLACE VIEW view_jobs
            AS
            SELECT
                jobs.id AS id,
                crafts.id AS craft_id,
                crafts.title AS craft,

                view_projects.title AS project_title,
                view_projects.description AS project_description,
                view_projects.type AS project_type,
                view_projects.projected_timeline AS project_timeline,
                view_projects.manager_id AS project_manager_id,
                view_projects.manager_username AS project_manager_username,

                users.id AS assigned_to_id,
                users.username AS assigned_to_username
            FROM
                jobs
                LEFT JOIN crafts ON jobs.craft_id = crafts.id
                LEFT JOIN view_projects ON jobs.project_id = view_projects.id
                LEFT JOIN users ON jobs.assigned_to = users.id;
        ");
    }

    public function down()
    {
        if( config('database.default') != 'mysql')
            return;
        Schema::dropIfExists('view_jobs');
    }
}
