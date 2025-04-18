<?php

use App\Models\Result;
use App\Models\Theme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('results_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Result::class);
            $table->foreignIdFor(Theme::class);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('results_themes');
    }
};
