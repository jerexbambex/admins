<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BursaryTransactionExport implements FromView
{
    public $transactions, $trans_action; 

    public function __construct($transactions, $trans_action)
    {
        $this->transactions = $transactions;
        $this->trans_action = $trans_action;
    }

    public function view() : View
    {
        return view('exports.bursary.history', [
            'transactions' => $this->transactions,
            'trans_action'   =>  $this->trans_action
        ]);
    }
}
