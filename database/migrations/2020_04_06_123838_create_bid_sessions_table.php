<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamp("finishes_at")->useCurrent();
            $table->timestamp("begins_at")->useCurrent();
            $table->timestamp("payment_due")->useCurrent();
            $table->integer("min_bid")->default(20);
            $table->timestamp("sponsor_from")->useCurrent();
            $table->integer("duration_weeks")->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_sessions');
    }
}
