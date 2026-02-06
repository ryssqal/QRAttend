<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('name', 'title');
            $table->renameColumn('event_date', 'date');
            $table->renameColumn('event_time', 'start_time');
            $table->renameColumn('max_attendees', 'pax');
            $table->renameColumn('qr_code_path', 'media_path');
            $table->renameColumn('user_id', 'pengurus_id');

            // Add new columns
            $table->time('end_time')->nullable();
            $table->string('password_hash')->nullable();

            // Drop existing foreign key and add new one
            $table->dropForeign(['user_id']);
            $table->foreign('pengurus_id')->references('id')->on('pengurus_majlis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Reverse the changes
            $table->dropForeign(['pengurus_id']);
            $table->foreign('pengurus_id')->references('id')->on('users')->onDelete('cascade');

            // Drop new columns
            $table->dropColumn(['end_time', 'password_hash']);

            // Rename columns back
            $table->renameColumn('title', 'name');
            $table->renameColumn('date', 'event_date');
            $table->renameColumn('start_time', 'event_time');
            $table->renameColumn('pax', 'max_attendees');
            $table->renameColumn('media_path', 'banner_url');
            $table->renameColumn('pengurus_id', 'user_id');
        });
    }
};
