<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('sl_no')->nullable()->unique()->after('id');
        });

        $serial = 1;
        DB::table('members')->orderBy('id')->select('id')->chunkById(500, function ($members) use (&$serial) {
            foreach ($members as $member) {
                DB::table('members')->where('id', $member->id)->update(['sl_no' => $serial++]);
            }
        });

        Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('sl_no')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropUnique(['sl_no']);
            $table->dropColumn('sl_no');
        });
    }
};
