<?php

namespace App\Http\Livewire\Payments;

use App\Models\SchoolSession;
use App\Models\Transaction;
use Livewire\Component;

class PaymentHistoryCountComponent extends Component
{
    public $session_year, $payment_type = 'School Fee';

    public function mount()
    {
        $this->session_year = SchoolSession::first()->year;
        $this->payment_type = 'School Fee';
    }

    public function render()
    {
        $sch_sessions = SchoolSession::all();
        return view('livewire.payments.payment-history-count-component', compact('sch_sessions'));
    }
}
