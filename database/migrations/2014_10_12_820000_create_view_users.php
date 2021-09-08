<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateViewUsers extends Migration
{
    public function up()
    {
        if(config('database.default') != 'mysql')
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
    }

    public function down()
    {
        if(config('database.default') != 'mysql')
            return;
        Schema::dropIfExists('view_users');
    }
}
