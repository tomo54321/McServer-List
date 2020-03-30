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
            $table->string("banner_path")->default(null);
            $table->string("header_path")->default(null);
            $table->mediumText("description");
            $table->string("youtube_id")->nullable();
            $table->mediumText("votifier_key")->default(null);
            $table->integer("votifier_port")->default(null);
            $table->string("ip");
            $table->integer("port");

            $table->dateTime("featured_until")->default(null);
            $table->dateTime("standing_out_until")->default(null);

            $table->dateTime("last_pinged")->default(null);
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
