<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void 
    {
        // Add `customer_id` column in `users` table.
        if (!Schema::hasColumn('users', 'customer_id')) {
            Schema::table('users', function (Blueprint $table) : void {
                $table->string('customer_id')->nullable()->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void 
    {
         // Drop `customer_id` column from `users` table.
         if (Schema::hasColumn('users', 'customer_id')) {
            Schema::table('users', function (Blueprint $table) : void {
                $table->dropColumn('customer_id');
            });
        }
    }
};
