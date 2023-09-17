<div>
    <div class="container" style="padding: 30px 0;">
        <div class="d-flex flex-column">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="mx-2" style="text-transform: uppercase;">Update Student</div>
                            <div class="mx-2">
                                <a href="{{ route('admission.students') }}" class="btn btn-success pull-right">All Students</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    @include('layouts.messages')
                    <form class="form-horizontal" wire:submit.prevent="updateStudent">
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Matriculation Number</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Matriculation Number" class="form-control input-md"
                                    wire:model="matric_no">
                                @error('matric_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Firstname</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Firstname" class="form-control input-md"
                                        wire:model="firstname">
                                    @error('slug')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Surname</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Surname" class="form-control input-md"
                                        wire:model="surname">
                                    @error('slug')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Othernames</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Othernames" class="form-control input-md"
                                        wire:model="othernames">
                                    @error('slug')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Form Number</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Form Number" class="form-control input-md"
                                    wire:model="matset">
                                @error('matset')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">State</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="state">
                                        @foreach ($states as $state)
                                            <option value="{{ $state->state_id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Gender</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Gender" class="form-control input-md"
                                        wire:model="gender">
                                    @error('gender')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Faculty</label>
                            <div class="col-md-12">
                                <select class="form-control input-md" wire:model="faculty">
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('faculty')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Programme</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="programme">
                                        @foreach ($programmes as $programme)
                                            <option value="{{ $programme->programme_id }}">
                                                {{ $programme->programme_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('programme')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Programme Type</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="programme_type">
                                        @foreach ($progtypes as $progtype)
                                            <option value="{{ $progtype->programmet_id }}">
                                                {{ $progtype->programmet_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('progtype')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Department</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="department">
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->departments_id }}">
                                                {{ $department->departments_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Course</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="course">
                                        <option value="">Select . . .</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->do_id }}">{{ $course->programme_option }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Email</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Email" class="form-control input-md"
                                        wire:model="email">
                                    @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Phone Number</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Phone Number" class="form-control input-md"
                                        wire:model="phone">
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Birth Date</label>
                                <div class="col-md-12">
                                    <input type="date" placeholder="Birth Date" class="form-control input-md"
                                        wire:model="birth_date">
                                    @error('birth_date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="" class="col-md-12 control-label"></label>
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
