<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitrelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unitrelations', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('department')->nullable();
            $table->string('section')->nullable();
            $table->string('team')->nullable();
            $table->string('code_rel')->nullable();
            $table->string('office')->nullable();
            $table->string('restrict')->nullable();
            $table->string('rights')->nullable();
            $table->string('hpn')->nullable();
            $table->string('org')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('unitrelations');
    }
}
