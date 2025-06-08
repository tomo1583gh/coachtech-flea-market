<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceZipcodeWithZipInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 既存のzipcodeカラムを削除
            if (Schema::hasColumn('users', 'zipcode')) {
                $table->dropColumn('zipcode');
            }

            // 新しいzipカラムを追加
            if (!Schema::hasColumn('users', 'zip')) {
                $table->string('zip')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // zipカラムを削除
            if (Schema::hasColumn('users', 'zip')) {
                $table->dropColumn('zip');
            }

            // zipcodeカラムを復元
            if (!Schema::hasColumn('users', 'zipcode')) {
                $table->string('zipcode')->nullable()->after('email');
            }
        });
    }
}
