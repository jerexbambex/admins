<div>
    <div class="card">
        @include('layouts.messages')
        <div class="card-body">
            <div class="card-title">
                Registered Students' Filter
            </div>
            <div class="form-body">
                <form wire:submit.prevent="getStudents">
                    <div class="row">
                        @if ($user_role !== 'hod')
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fac_id">Faculty</label>
                                    <select wire:model="fac_id" id="fac_id" class="form-control">
                                        <option value="">Select Faculty . . .</option>
                                        @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fac_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dept_id">Department</label>
                                    <select wire:model="dept_id" id="dept_id" class="form-control">
                                        <option value="">Select Department . . .</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->departments_id }}">
                                                {{ $department->departments_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('dept_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="prog_id">Programme</label>
                                <select wire:model="prog_id" id="prog_id" class="form-control">
                                    <option value="">Select Programme . . .</option>
                                    @foreach ($programmes as $programme)
                                        <option value="{{ $programme->programme_id }}">{{ $programme->programme_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('prog_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="opt_id">Programme Option</label>
                                <select wire:model="opt_id" id="opt_id" class="form-control">
                                    <option value="">Select Option . . .</option>
                                    @foreach ($options as $option)
                                        <option value="{{ $option->do_id }}">{{ $option->programme_option }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('opt_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sess_id">Session</label>
                                <select wire:model="sess_id" id="sess_id" class="form-control">
                                    <option value="">Select Session . . .</option>
                                    @foreach ($sessions as $sess)
                                        <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                                    @endforeach
                                </select>
                                @error('sess_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        @if (!auth()->user()->prog_type_id)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="progtype_id">Programme Type</label>
                                    <select wire:model="progtype_id" id="progtype_id" class="form-control">
                                        <option value="">Select Type . . .</option>
                                        @foreach ($programme_types as $type)
                                            <option value="{{ $type->programmet_id }}">{{ $type->programmet_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('progtype_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="level_id">Level</label>
                                <select wire:model="level_id" id="level_id" class="form-control">
                                    <option value="">Select Level . . .</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                                    @endforeach
                                </select>
                                @error('level_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semester_id">Semester</label>
                                <select wire:model="semester_id" id="semester_id" class="form-control">
                                    <option value="">Select Level . . .</option>
                                    @foreach ($semesters as $key => $semester)
                                        <option value="{{ $key }}">{{ $semester }}</option>
                                    @endforeach
                                </select>
                                @error('semester_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Download</button>
                            </div>
                        </div>
                    </div>
                </form>
                @if ($user_role !== 'hod')
                    <form wire:submit.prevent="downloadByFaculty">
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-secondary">Download All Departments in
                                selected
                                Faculty</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <script type="text/javascript">
        const reloadable = @json(session()->has('download_next') && session()->get('download_next'))

        if (reloadable) location.reload(true);
    </script>
</div>
