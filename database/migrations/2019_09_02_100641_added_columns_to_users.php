<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('name');
            $table->string('gravatar_img')->nullable()->after('email');
            $table->string('role')->default('user')->after('gravatar_img');
            $table->boolean('isBan')->default(0)->after('role');
            $table->boolean('isMute')->default(0)->after('isBan');
            $table->integer('color_id')->after('isMute');
            $table->foreign('color_id')
                ->references('id')->on('colors')
                ->onDelete('cascade');
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
            $table->dropColumn('google_id');
            $table->dropColumn('gravatar_img');
            $table->dropColumn('isMute');
            $table->dropColumn('isBan');
            $table->dropColumn('role');
            $table->dropForeign('users_color_id_foreign');
            $table->dropColumn('color_id');
        });
    }
}
