<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'results_themes', 'result_id', 'theme_id');
    }
}
