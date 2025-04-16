<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

}
