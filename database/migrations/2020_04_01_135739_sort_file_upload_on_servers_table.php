<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SortFileUploadOnServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string("banner_path")->nullable()->after("has_banner");
            $table->string("header_path")->nullable()->after("has_header");
            $table->string("icon_path")->nullable()->after("has_icon");
            $table->dropColumn("has_banner");
            $table->dropColumn("has_header");
            $table->dropColumn("has_icon");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->boolean("has_banner")->default(false)->after("banner_path");
            $table->boolean("has_header")->default(false)->after("header_path");
            $table->boolean("has_icon")->default(false)->after("icon_path");
            $table->dropColumn("banner_path");
            $table->dropColumn("header_path");
            $table->dropColumn("icon_path");
        });
    }
}
