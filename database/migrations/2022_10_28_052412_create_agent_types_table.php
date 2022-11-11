<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('observation')->nullable();

            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->softDeletes();
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_types');
    }
}
