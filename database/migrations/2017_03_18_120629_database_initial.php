<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatabaseInitial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // staff
        Schema::create('Staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employNo');
            $table->string('department');
            $table->string('uEngName');
            $table->string('uCnName')->nullable();
            $table->string('section')->nullable();
            $table->date('joinDate')->nullable();
            $table->date('leaveDate')->nullable();

            $table->softDeletes();
        });

        // Roles
        Schema::create('Roles', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('enName');
            $table->boolean('hide');
        });

        // Pages
        Schema::create('Pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->boolean('showInEntrance');
            $table->string('icon')->nullable();
            $table->string('controller')->nullable();
            $table->string('action')->nullable();
        });

        // Role Pages
        Schema::create('RolePages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roleID');
            $table->integer('pageID');
        });

        // User Roles
        Schema::create('UserRoles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lanID', 20);
            $table->integer('roleID');
            $table->string('dept', 10);
            $table->boolean('active')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // occupational risk
        Schema::create('OccupationalRisk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department');
            $table->string('section');
            $table->string('description', env('FIELD_MAX_LENGTH'))->nullable();
            $table->string('riskLevel', 20);

            $table->softDeletes();
        });

        // kye operation
        Schema::create('KYEOperations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staffID');
            $table->string('occupationalRisk', 20);
            $table->string('RelationshipRisk', 20);
            $table->string('specialFactors', 20);
            $table->string('overallRisk', 20);
        });

        // operation log
        Schema::create('OperationLogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tableName');
            $table->string('tableID')->nullable();
            $table->string('type', 10);
            $table->string('from', env('FIELD_MAX_LENGTH'))->nullable();
            $table->string('to', env('FIELD_MAX_LENGTH'))->nullable();
            $table->string('madeBy', 20);
            $table->string('checkedBy', 20)->nullable();
            $table->boolean('checkedResult')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // parameters
        Schema::create('Parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key1', 100);
            $table->string('key2', 100)->nullable();
            $table->string('key3', 100)->nullable();
            $table->string('value', 100)->nullable();

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
        Schema::drop('Staff');
        Schema::drop('UserRoles');
        Schema::drop('Roles');
        Schema::drop('Pages');
        Schema::drop('RolePages');
        Schema::drop('OccupationalRisk');
        Schema::drop('KYEOperations');
        Schema::drop('OperationLogs');
        Schema::drop('Parameters');
    }
}
