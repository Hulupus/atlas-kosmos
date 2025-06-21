<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\DeviceGroup; // Import DeviceGroup model

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->foreignIdFor(DeviceGroup::class)
                ->after('description')
                ->constrained()
                ->onDelete('restrict');

            $table->dropColumn('device_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('device_group')->nullable()->after('description');

            $table->dropConstrainedForeignIdFor(DeviceGroup::class);
            $table->dropColumn('device_group_id');
        });
    }
};
