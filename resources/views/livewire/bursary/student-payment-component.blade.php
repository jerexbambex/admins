<div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md form-group">
                        <label>Faculty</label>
                        <select class="form-control" wire:model="faculty_id">
                            <option value="0">All</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Department</label>
                        <select class="form-control" wire:model="department_id">
                            <option value="0">All</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->departments_id }}">{{ $department->departments_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Indegene Status</label>
                        <select class="form-control" wire:model="state">
                            <option value=0>All</option>
                            <option value=1>Indegene</option>
                            <option value=2>Non-Indegene</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md form-group">
                        <label>Programme</label>
                        <select class="form-control" wire:model="prog_id">
                            <option value="0">All</option>
                            @foreach (PROGRAMMES as $key => $programme)
                                <option value="{{ $key }}">{{ $programme }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Level</label>
                        <select class="form-control" wire:model="level_id">
                            <option value="0">All</option>
                            @if ($prog_id)
                                @foreach (\App\Models\Level::where('programme_id', $prog_id)->get() as $level)
                                    <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Programme Type</label>
                        <select class="form-control" wire:model="prog_type_id">
                            <option value="0">All</option>
                            @foreach (PROG_TYPES as $key => $progType)
                                <option value="{{ $key }}">{{ $progType }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md form-group">
                        <label>Fee Class</label>
                        <select class="form-control" wire:model="feeClass">
                            <option value="0">All</option>
                            <option value="fees">Normal Fees</option>
                            <option value="ofees">Other Fees</option>
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Fee Type</label>
                        <select class="form-control" wire:model="fee_id">
                            <option value=0>All</option>
                            @if ($feeClass == 'fees')
                                @foreach ($fees as $fee)
                                    <option value={{ $fee->field_id }}>{{ $fee->field_name }}</option>
                                @endforeach
                            @endif
                            @if ($feeClass == 'ofees')
                                <option value="Hostel Accommodation">Hostel Accommodation</option>
                                <option value="Acceptance Fee">Acceptance Fee</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Semester</label>
                        <select wire:model="semester" class="form-control">
                            <option value="0">All</option>
                            @foreach (SEMESTERS as $semester)
                                <option>{{ $semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Session</label>
                        <select class="form-control" wire:model="session">
                            <option value="0">All</option>
                            @foreach ($sch_sessions as $sess)
                                <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md form-group">
                        <label>Start Date</label>
                        <input type="date" class="form-control" wire:model="start_date" />
                    </div>
                    <div class="col-md form-group">
                        <label>End Date</label>
                        <input type="date" class="form-control" wire:model="end_date" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <form wire:submit.prevent="download">
                    <button class="btn btn-sm btn-primary" type="submit">Download</button>
                </form>
                <div class="form-body">
                    <div class="form-group">
                        <button class="btn btn-sm btn-primary float-end" type="button">Search</button>
                        <input type="text" class="float-end" placeholder="Search . . ."
                            wire:model.lazy="search_param" />
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-md form-group">
                            <div class="text-center text-white"
                                style="display: flex; flex-direction: column; font-size:30px; font-weight: 900; border-radius: 20px; background: #318CE7; padding:50px 10px; ">
                                <span style="color:white; font-size:24px; font-weight: 900;">Total Number:</span>
                                <b>{{ number_format($number) }}</b>
                            </div>
                        </div>
                        <div class="col-md form-group">
                            <div class="text-center text-white"
                                style="display: flex; flex-direction: column; font-size:30px; font-weight: 900; border-radius: 20px; background: #0066b2; padding:50px 10px; ">
                                <span style="color:white; font-size:24px; font-weight: 900;">Total Amount: </span>
                                <b>&#8358;{{ number_format($total_amount, 2) }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="text-center">
                            <th>#</th>
                            <th>Matric Number</th>
                            <th>Full Name</th>
                            <th>Level</th>
                            <th>Department</th>
                            <th>Faculty</th>
                            <th>Programme Type</th>
                            <th>Transaction name</th>
                            <th>State</th>
                            <th>Amount</th>
                            <th>Fee Type</th>
                            <th>Session</th>
                            <th>Semester</th>
                            <th>Transaction date</th>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                                if ($transactions):
                                    $page = $transactions->currentPage();
                                    $paginate = PAGINATE_SIZE;
                                    $count = $page * $paginate - $paginate + 1;
                                endif;
                            @endphp
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <th>{{ $count++ }}</th>
                                    <td>{{ $transaction->appno }}</td>
                                    <td>{{ $transaction->full_name }}</td>
                                    <td>{{ $transaction->level_name }}</td>
                                    <td>{{ $transaction->department_name }}</td>
                                    <td>{{ $transaction->faculty_name }}</td>
                                    <td>{{ $transaction->programme_type_name }}</td>
                                    <td>{{ $transaction->trans_name }}</td>
                                    <td>{{ $transaction->state_name }}</td>
                                    <td>{{ $transaction->trans_amount }}</td>
                                    <td>{{ $transaction->trans_name }}</td>
                                    <td>{{ $transaction->trans_session->session }}</td>
                                    <td>{{ $transaction->trans_semester }}</td>
                                    <td>{{ $transaction->t_date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-danger">
                                        No record found!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
