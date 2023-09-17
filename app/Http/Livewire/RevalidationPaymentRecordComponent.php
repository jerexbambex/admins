<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use Livewire\Component;

class RevalidationPaymentRecordComponent extends Component
{
    public $search;

    function mount()
    {
        $this->search = '';
    }

    public function render()
    {
        $transactions = Transaction::wherePayStatus('paid')->where('trans_name', 'like', '%revalidation%');
        if ($this->search) $transactions->whereAppno($this->search);
        $transactions = $transactions->paginate(25);

        return view('livewire.revalidation-payment-record-component', compact('transactions'));
    }
}
