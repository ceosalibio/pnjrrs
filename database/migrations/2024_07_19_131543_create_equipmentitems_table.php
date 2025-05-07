<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipmentitems', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->integer('code');
            $table->text('cat');
            $table->text('sub')->nullable();
            $table->text('des')->nullable();
            $table->text('req')->nullable();
            $table->text('act');
            $table->string('month');
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
        Schema::dropIfExists('equipmentitems');
    }
}
