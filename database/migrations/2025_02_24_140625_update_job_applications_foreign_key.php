<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJobApplicationsForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove the existing foreign key constraint for job_id
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        // Add the new foreign key constraint for job_id referencing job_posts table
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreign('job_id')->references('id')->on('job_posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback the changes by removing the foreign key constraint
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }
}
