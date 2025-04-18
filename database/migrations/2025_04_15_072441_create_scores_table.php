<?php

use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Submission::class);
            $table->double('score')->default(0.0);
            $table->index(['user_id', 'submission_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
