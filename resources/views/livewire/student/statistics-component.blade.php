<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Students' Statistics Filter
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="statistics_type">Statistics Type</label>
                            <select wire:model="statistics_type" id="statistics_type" class="form-control">
                                <option value="registered">Registered</option>
                                <option value="payment">Payment</option>
                            </select>
                        </div>
                    </div>
                    @if ($user_role !== 'hod')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fac_id">Faculty</label>
                                <select wire:model="fac_id" id="fac_id" class="form-control">
                                    <option value="0">Select Faculty . . .</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dept_id">Department</label>
                                <select wire:model="dept_id" id="dept_id" class="form-control">
                                    <option value="0">Select Department . . .</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->departments_id }}">
                                            {{ $department->departments_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="prog_id">Programme</label>
                            <select wire:model="prog_id" id="prog_id" class="form-control">
                                <option value="0">Select Programme . . .</option>
                                @foreach ($programmes as $programme)
                                    <option value="{{ $programme->programme_id }}">
                                        {{ $programme->programme_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="opt_id">Programme Option</label>
                            <select wire:model="opt_id" id="opt_id" class="form-control">
                                <option value="0">Select Option . . .</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option->do_id }}">{{ $option->programme_option }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sess_id">Session</label>
                            <select wire:model="sess_id" id="sess_id" class="form-control">
                                <option value="0">Select Session . . .</option>
                                @foreach ($sessions as $sess)
                                    <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="semester_id">Semester</label>
                            <select wire:model="semester_id" id="semester_id" class="form-control">
                                <option value="0">Select Level . . .</option>
                                @foreach ($semesters as $key => $semester)
                                    <option value="{{ $key }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Statistics Table
                <form wire:submit.prevent="downloadStats">                    
                    <button class="btn btn-sm btn-primary" type="submit">Download</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Faculty</th>
                            <th>Department</th>
                            <th>Dept. Option</th>
                            <th>Level</th>
                            <th>Prog. Type</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stats as $data)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $data->faculty ?? '' }}</td>
                                <td>{{ $data->department ?? '' }}</td>
                                <td>{{ $data->option_name ?? '' }}</td>
                                <td>{{ $data->level_name ?? '' }}</td>
                                <td>{{ $data->programme_type ?? '' }}</td>
                                <td>{{ $data->total_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$stats->links()}}
        </div>
    </div>
</div>
