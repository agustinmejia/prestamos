<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_agents', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('loan_id')->nullable()->constrained('loans');
            
            $table->foreignId('agent_id')->nullable()->constrained('users');
            $table->string('agentType')->nullable();

            $table->text('observation')->nullable();

            $table->smallInteger('status')->default(1);

            $table->timestamps();
            $table->foreignId('register_userId')->nullable()->constrained('users');
            $table->string('register_agentType')->nullable();

            $table->softDeletes();
            $table->foreignId('deleted_userId')->nullable()->constrained('users');
            $table->string('deleted_agentType')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_agents');
    }
}
