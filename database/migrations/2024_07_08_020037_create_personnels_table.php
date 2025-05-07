<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->longtext('details');
            $table->string('reason')->nullable();
            $table->json('assessment');
            $table->json('declined')->nullable();
            $table->json('noted');
            $table->unsignedInteger('grade');
            $table->unsignedInteger('afpos');
            $table->unsignedInteger('required');
            $table->unsignedInteger('actual');
            $table->text('result');
            $table->string('month');
            $table->string('status');
            $table->string('ipaddress')->nullable();
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
        Schema::dropIfExists('personnels');
    }
}
