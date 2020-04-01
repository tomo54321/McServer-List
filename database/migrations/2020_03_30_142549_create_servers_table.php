<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("name");
            $table->string("ip");
            $table->integer("port");

            $table->string("has_banner")->default(false);
            $table->string("has_header")->default(false);
            $table->string("has_icon")->default(false);
            $table->mediumText("description");

            $table->string("youtube_id")->nullable();
            $table->mediumText("votifier_key")->nullable();
            $table->integer("votifier_port")->nullable();

            $table->dateTime("featured_until")->nullable();
            $table->dateTime("standing_out_until")->nullable();

            $table->integer("online_players")->nullable();
            $table->integer("max_players")->nullable();
            $table->string("version_string")->nullable();
            $table->boolean("is_online")->default(false);
            $table->dateTime("last_pinged")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
