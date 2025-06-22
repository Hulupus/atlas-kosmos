<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null');

            $table->string('name')->unique();
            $table->string('location')->nullable();
            $table->text('description')->nullable();

            $table->string('device_group')->default('Default');
            $table->string('webclient_start_url')->nullable();
            $table->timestamp('last_callback_at')->nullable();

            $table->boolean('has_dashboard')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
