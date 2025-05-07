<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->longtext('details');
            $table->text('activity');
            $table->unsignedInteger('required');
            $table->unsignedInteger('actual');
            $table->unsignedInteger('orac');
            $table->string('reason')->nullable();
            $table->text('result');
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
        Schema::dropIfExists('trainings');
    }
}
