<?php

namespace App\Http\Livewire\Dr;

use App\Exports\StudentExamDatesExport;
use App\Models\AppCurrentSession;
use App\Models\Applicant;
use DateInterval;
use DatePeriod;
use DateTime;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExamDatesComponent extends Component
{
    public $start_date, $end_date, $from_date, $to_date;

    public $setting_dates = false, $getting_students = false;

    public $current_session;

    protected function rules()
    {
        if ($this->setting_dates) return [
            'start_date'    =>  'required|date',
            'end_date'    =>  'required|date',
        ];

        if ($this->getting_students) return [
            'from_date'    =>  'required|date',
            'to_date'    =>  'required|date',
        ];
    }

    public function mount()
    {
        $this->current_session = AppCurrentSession::where('status', 'current')->first()->cs_session;
    }

    public function update($prop)
    {
        $this->validateOnly($prop);
    }


    public function set_dates()
    {
        $this->setting_dates = true;
        $this->getting_students = false;
        $this->validate();

        $begin = new DateTime($this->start_date . " 00:00:00");
        $end = new DateTime($this->end_date . " 23:59:59");

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);


        // if(!$hoursRange) return session()->flash('error_toast', 'No hour range!');
        //dd($period);
        foreach ($period as $dt) {
            $studentsQuery = Applicant::where('std_programmetype', 1)->where('stdprogramme_id', 2)->whereAdmYear($this->current_session);
            $isFirstDay = $studentsQuery->whereNotNull('exam_date')->count() == 0;
            $hoursRange = null;
            if ($isFirstDay) $hoursRange = EXAM_HOURS['first'];
            else $hoursRange = EXAM_HOURS['other'];

            foreach (range($hoursRange[0], $hoursRange[1]) as $hour) {
                $date = $dt->format("Y-m-d $hour:00:00");
                //dd($date);

                $existing = $studentsQuery->where('exam_date', $date)->count();

                $new = EXAM_SEATS - $existing;

                $studentsQuery = Applicant::where('std_programmetype', 1)->where('stdprogramme_id', 2)->whereAdmYear($this->current_session);
                if ($new) $studentsQuery->whereNull('exam_date')->take($new)->update(['exam_date' => $date]);
            }
        }

        session()->flash('success_toast', 'Exam date set successful!');

        // $this->mount();
    }

    public function get_students()
    {
        $this->getting_students = true;
        $this->setting_dates = false;
        $this->validate();

        $from = (string)$this->from_date . " 00:00:00";
        $to = (string)$this->to_date . " 23:59:59";

        // dd($from, $to);

        $data = Applicant::where('stdprogramme_id', 2)->whereBetween('exam_date', [$from, $to])->orderby('exam_date', 'asc')->get();
        return Excel::download(new StudentExamDatesExport($data), "exam_dates.xlsx");
        $this->mount();
    }

    public function render()
    {
        return view('livewire.dr.exam-dates-component');
    }
}
