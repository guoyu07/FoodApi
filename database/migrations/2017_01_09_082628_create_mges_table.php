<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mges', function (Blueprint $table) {
            $table->increments('mge_id');
            $table->string('mge_title')->nullable();
            $table->longText('mge_content')->nullable();
            $table->string('mge_pictrue')->nullable();
            $table->string('mge_url')->nullable();
            $table->integer('mge_type')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mges');
    }
}
