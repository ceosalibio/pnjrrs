<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequiredequipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requiredequipments', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->longtext('details');
            $table->text('master')->nullable();
            $table->unsignedInteger('required1');
            $table->unsignedInteger('required2');
            $table->string('year')->nullable();
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
        Schema::dropIfExists('requiredequipments');
    }
}
