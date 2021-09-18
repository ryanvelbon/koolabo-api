<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateViewTables extends Migration
{
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


        DB::statement("
            CREATE OR REPLACE VIEW view_users
            AS
            SELECT
                users.id,
                users.username,
                users.email,
                users.created_at AS member_since,
                users.account_status,

                user_profiles.first_name,
                user_profiles.last_name,
                user_profiles.gender,
                DATEDIFF(CURRENT_DATE(), user_profiles.date_of_birth) DIV 365.25 AS age,
                user_profiles.date_of_birth,
                cities.id AS city_id,
                cities.city,
                user_profiles.bio_short,
                user_profiles.bio_long,
                user_profiles.availability,
                user_profiles.profile_pic,
                user_profiles.banner_pic
            FROM
                users
                LEFT JOIN user_profiles ON users.id = user_profiles.user_id
                LEFT JOIN cities ON user_profiles.city_id = cities.id;
        ");


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
        Schema::dropIfExists('view_projects');
        Schema::dropIfExists('view_users');
    }
}
