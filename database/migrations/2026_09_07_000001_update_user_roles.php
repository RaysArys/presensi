<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'user', 'supervisor', 'staff') NOT NULL DEFAULT 'staff'");
        DB::table('users')->where('role', 'user')->update(['role' => 'staff']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'supervisor', 'staff') NOT NULL DEFAULT 'staff'");
    }

    public function down(): void
    {
        DB::table('users')->whereIn('role', ['supervisor', 'staff'])->update(['role' => 'user']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};