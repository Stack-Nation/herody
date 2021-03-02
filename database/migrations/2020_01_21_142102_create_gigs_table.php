<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gigs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cats')->nullable();
            $table->double('per_cost', 8, 2);
            $table->string('campaign_title');
            $table->longText('description');
            $table->bigInteger('user_id');
            $table->string('brand');
            $table->string('logo');
            $table->integer('mobile')->nullable();
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
        Schema::dropIfExists('gigs');
    }
}
