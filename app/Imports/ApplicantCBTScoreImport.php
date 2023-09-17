<?php

namespace App\Imports;

use App\Models\ApplicantCbtScore;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ApplicantCBTScoreImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collections)
    {
        $c = 1;
        foreach ($collections as $row) {
            if ($c >= 2) {
                if (ApplicantCbtScore::whereFormNumber($row[1])->count() === 0 && strlen($row[1]) > 0) {
                    ApplicantCbtScore::create([
                        'form_number'   =>  $row[1],
                        'score'         =>  $row[2]
                    ]);
                }
            }
            $c++;
        }

        // session()->flash('success_alert', 'Upload successful');
    }
}
