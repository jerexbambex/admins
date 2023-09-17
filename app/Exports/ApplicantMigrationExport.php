<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ApplicantMigrationExport implements FromView
{
    public $lists;

    public function __construct($lists)
    {
        $this->lists = $lists;
    }

    public function view(): View
    {
        return view('exports.admission.migrated-list', [
            'lists' => $this->lists,
        ]);
    }
}
