<?php

namespace App\Http\Livewire\Bursary;

use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HostelManualPaymentsComponent extends Component
{
    public $hostels;

    public function mount()
    {
        $this->hostels = [];
        $this->addHostel();
    }

    public function addHostel()
    {
        $this->hostels[] = '';
    }

    public function removeHostel($key)
    {
        $hostels = $this->hostels;
        unset($hostels[$key]);
        $this->hostels = $hostels;
    }

    public function submit()
    {
        $hostel_fee = DB::table('ofield')->where('ofield_name', 'like', '%hostel%')->first();
        $messages = "";
        $succes_message = false;
        foreach ($this->hostels as $appno) {
            if ($appno) :
                $student = Student::whereMatricNo($appno)->orWhere('matset', $appno)->first();
                if (!$student) $messages = $this->concat_message($messages, "$appno Account not found. ");

                $transaction_exist = Transaction::whereAppno($appno)->where('trans_name', 'like', '%hostel%')->wherePayStatus('Paid')->count();
                if ($transaction_exist) $messages = $this->concat_message($messages, "$appno Hostel Payment already Exist. ");
                $uniqid = uniqid();

                DB::table('stdtransaction')->insert([
                    'log_id' => $student->std_logid,
                    'trans_name' => 'Hostel Accommodation',
                    'trans_no' => hexdec($uniqid),
                    'rrr' => $uniqid,
                    'levelid' => $student->stdlevel,
                    'user_faculty' => $student->stdfaculty_id,
                    'user_dept' => $student->stddepartment_id,
                    'trans_amount' => $hostel_fee->of_amount,
                    'generated_date' => date('Y-m-d H:i:s'),
                    'trans_date' => date('Y-m-d H:i:s'),
                    't_date' => date('Y-m-d'),
                    'trans_year' => $student->std_admyear,
                    'trans_semester' => 'First Semester',
                    'pay_status' => 'Paid',
                    'policy' => "First Semester",
                    'fullnames' => "$student->surname $student->firstname $student->othernames",
                    'prog_id' => $student->stdprogramme_id,
                    'prog_type' => $student->stdprogrammetype_id,
                    'stdcourse' => $student->stdcourse,
                    'appno' => $appno,
                    'appsor' => $student->state_of_origin,
                    'channel' => 'Interswitch',
                    'fee_id' => $hostel_fee->of_id,
                    'fee_type' => 'ofees',
                    'lost_pay_ref' =>   'Manual'
                ]);
                if(!$succes_message) $succes_message = true;
            endif;
        }

        if ($messages) session()->flash('error_alert', $messages);
        if($succes_message) {
            session()->flash('success_toast', 'Successful');
            $this->mount();
        }
    }

    public function concat_message($messages = "", $new_message = "")
    {
        if ($messages) $messages .= "<br>$new_message";
        else $messages = $new_message;
        return $messages;
    }

    public function render()
    {
        return view('livewire.bursary.hostel-manual-payments-component');
    }
}
