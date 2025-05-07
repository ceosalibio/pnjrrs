<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->longtext('details');
            $table->longtext('master')->nullable();
            $table->unsignedInteger('required1');
            $table->unsignedInteger('required2');
            $table->unsignedInteger('actual1');
            $table->unsignedInteger('actual2');
            $table->text('result');
            $table->string('reason')->nullable();
            $table->json('assessment');
            $table->json('declined')->nullable();
            $table->json('noted');
            $table->string('month');
            $table->json('status');
            $table->string('ipaddress');
            $table->string('updated_by');
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
        Schema::dropIfExists('equipments');
    }
}
