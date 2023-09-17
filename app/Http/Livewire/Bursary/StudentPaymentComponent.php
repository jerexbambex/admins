<?php

namespace App\Http\Livewire\Bursary;

use App\Exports\BursaryTransactionExport;
use App\Models\Faculty;
use App\Models\Fees;
use App\Models\OtherFees;
use App\Models\SchoolSession;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StudentPaymentComponent extends Component
{
    public $faculty_id = 0, $department_id = 0,
        $level_id = 0, $prog_id = 0, $prog_type_id = 0,
        $fee_type, $fee_id = null, $state = 0, $feeClass, $semester, $session = 0;
    public $start_date;
    public $end_date;
    public $search_param;

    use WithPagination;

    public function download()
    {
        $transactions = $this->getTransactions()->get();
        $total_amount = $this->getTransactions()->sum('trans_amount');
        $number = $this->getTransactions()->count();
        $trans_action = json_decode(json_encode(compact('total_amount', 'number')));

        return Excel::download(new BursaryTransactionExport($transactions, $trans_action), "bursaryreport.xlsx");
    }


    public function getTransactions()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        //Transactions
        $transactions = Transaction::select(['stdtransaction.*'])->with(['trans_session', 'department', 'faculty', 'level', 'progType', 'state', 'student'])->where('pay_status', 'like', '%Paid%');
        // $transactions->withCount('log_id')->withSum('trans_amount');
        if ($this->department_id) $transactions->where('user_dept', $this->department_id);
        elseif ($this->faculty_id) $transactions->where('user_faculty', $this->faculty_id);
        if ($this->level_id) $transactions->where('levelid', $this->level_id);
        elseif ($this->prog_id) $transactions->where('prog_id', $this->prog_id);

        //Indigenous check
        if ($this->state) {
            if ($this->state == 1) $transactions->where('appsor', 30);
            elseif ($this->state == 2) $transactions->where('appsor', '!=', 30);
        }

        if ($this->feeClass) $transactions->where('fee_type', $this->feeClass);
        if ($this->fee_id) {
            if (is_numeric($this->fee_id)) $transactions->where('fee_id', $this->fee_id);
            else $transactions->where('trans_name', 'like', '%' . $this->fee_id . '%');
        }
        if ($this->start_date and $this->end_date) $transactions->whereBetween('t_date', [$start, $end]);
        if ($this->prog_type_id) $transactions->where('prog_type', $this->prog_type_id);
        if ($this->search_param) $transactions->where('appno', 'like', '%' . $this->search_param . '%');
        if ($this->semester) $transactions->where('trans_semester', $this->semester);
        if ($this->session) $transactions->where('trans_year', $this->session);
        $transactions->join('stdprofile', 'stdprofile.std_logid', 'stdtransaction.log_id');

        return $transactions;
    }

    public function render()
    {

        $faculties = Faculty::all();
        $otherfees = [];
        if ($this->prog_id) $otherfees = OtherFees::where('of_prog', $this->prog_id)->get();
        $fees = Fees::all();
        $departments = [];
        if ($this->faculty_id) $departments = Faculty::find($this->faculty_id)->departments;
        $transactions = $this->getTransactions()->paginate(PAGINATE_SIZE);
        $total_amount = $this->getTransactions()->sum('trans_amount');
        $number = $this->getTransactions()->count();
        // dd($transactions);
        $sch_sessions = SchoolSession::all(['year', 'session']);
        return view('livewire.bursary.student-payment-component', compact('faculties', 'departments', 'transactions', 'otherfees', 'fees', 'total_amount', 'number', 'sch_sessions'));
    }
}
