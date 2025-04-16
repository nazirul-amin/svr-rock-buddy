<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\ToModel;

class ParticipantsImport implements ToModel
{
    public function model(array $row)
    {
        // Skip header row if present
        if (isset($row[0], $row[1]) && strtolower($row[0]) === 'email' && strtolower($row[1]) === 'name') {
            return null;
        }
        return Participant::firstOrCreate([
            'email' => $row[0],
        ], [
            'name' => $row[1],
        ]);
    }
}
