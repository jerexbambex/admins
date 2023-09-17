<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                @include('layouts.messages')
                Department Codes Filter
            </div>
            <div class="form-body">
                <form wire:submit.prevent="addData">
                    <div class="row">
                        @if(!auth()->user()->department_id)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fac_id">Faculty</label>
                                <select wire:model="fac_id" id="fac_id" class="form-control">
                                    <option value="">Select Faculty . . .</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dept_id">Department</label>
                                <select wire:model="dept_id" id="dept_id" class="form-control">
                                    <option value="">Select Department . . .</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->departments_id }}">
                                            {{ $department->departments_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prog_id">Programme</label>
                                <select wire:model="prog_id" id="prog_id" class="form-control">
                                    <option value="">Select Programme . . .</option>
                                    @foreach ($programmes as $programme)
                                        <option value="{{ $programme->programme_id }}">
                                            {{ $programme->programme_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if ($fac_id)
                        <div class="row">
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Load Codes</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @if ($data)
        <div class="card">
            <div class="card-body">
                <div class="card-title">Department Codes Table</div>

                <form wire:submit.prevent="submit">
                    <div class="table-responsive">
                        @foreach ($departments as $department)
                            @php
                                $dept_codes = [];
                                $dpt_id = $department->departments_id;
                                if (isset($data[$dpt_id])) {
                                    $dept_codes = $data[$dpt_id];
                                }
                            @endphp
                            @if ($dept_codes)
                                <table class="table table-hover table-striped table-bordered mb-4">
                                    <thead>
                                        <tr>
                                            <th colspan="7" class="text-center">
                                                {{ $department->departments_name }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Department</th>
                                            <th>Dept. Option</th>
                                            <th>Programme</th>
                                            <th>Prog. Type</th>
                                            <th>Dept. Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dept_codes as $key => $code)
                                            <tr>
                                                <th>{{ $loop->iteration }}</th>
                                                <td>{{ $code['deptname'] }}</td>
                                                <td>{{ $code['option_name'] }}</td>
                                                <td>{{ PROGRAMMES[$code['prog_id']] }}</td>
                                                <td>{{ PROG_TYPES[$code['progtype_id']] }}</td>
                                                <td>
                                                    <input type="number"
                                                        wire:model.lazy="data.{{ $dpt_id }}.{{ $key }}.deptcode"
                                                        class="form-control" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
