<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinkVansAndLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_logs', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('user');
            $table->bigInteger('van_id')->unsigned();
            $table->foreign('van_id')->references('id')->on('vans')->onDelete('cascade');
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
