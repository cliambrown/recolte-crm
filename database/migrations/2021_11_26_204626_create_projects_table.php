<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('created_by_user_id')->nullable();
            $table->foreignId('updated_by_user_id')->nullable();
            $table->softDeletes();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedTinyInteger('start_month')->nullable();
            $table->unsignedTinyInteger('start_day')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->unsignedTinyInteger('end_month')->nullable();
            $table->unsignedTinyInteger('end_day')->nullable();
            $table->string('notes', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
