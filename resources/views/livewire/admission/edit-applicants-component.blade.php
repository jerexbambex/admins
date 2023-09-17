<div>
    <div class="container" style="padding: 30px 0;">
        <div class="d-flex flex-column">
            @include('layouts.messages')
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="mx-2" style="text-transform: uppercase;">Update Applicant</div>
                            <div class="mx-2">
                                <a href="{{ route('admission.applicants.view') }}"
                                    class="btn btn-success pull-right">All Applicants</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" wire:submit.prevent="updateApplicant">
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Form Number</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Form Number" readonly class="form-control input-md"
                                    value="{{ $app_no }}">
                                @error('app_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Firstname</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Firstname" class="form-control input-md"
                                        wire:model.lazy="firstname">
                                    @error('firstname')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Surname</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Surname" class="form-control input-md"
                                        wire:model.lazy="surname">
                                    @error('surname')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="" class="col-md-12 control-label">Othernames</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Othernames" class="form-control input-md"
                                        wire:model.lazy="othernames">
                                    @error('othernames')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">State</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model.lazy="soo">
                                        <option value="">None</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->state_id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('soo')
                                        <p class=" text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Gender</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model.lazy="gender">
                                        <option value="">None</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    @error('gender')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="col-md-12 control-label">Email</label>
                                    <input type="email" wire:model.lazy="email" class="form-control input-md" />
                                    @error('email')
                                        <p class=" text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="col-md-12 control-label">Faculty</label>
                                    <select class="form-control input-md" wire:model="faculty">
                                        <option value="">Select . . .</option>
                                        @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->faculties_id }}">
                                                {{ $faculty->faculties_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('faculty')
                                        <p class=" text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Programme</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="programme">
                                        <option value="">Select . . .</option>
                                        @foreach ($programmes as $programme)
                                            <option value="{{ $programme->programme_id }}">
                                                {{ $programme->programme_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('programme')
                                        <p class=" text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Programme Type</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model.lazy="programme_type">
                                        <option value="">Select . . .</option>
                                        @foreach ($progtypes as $progtype)
                                            <option value="{{ $progtype->programmet_id }}">
                                                {{ $progtype->programmet_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('programme_type')
                                        <p class=" text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Department</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="department">
                                        <option value="">Select . . .</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->departments_id }}">
                                                {{ $department->departments_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <p class=" text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Course</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model.lazy="course">
                                        <option value="">Select . . .</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->do_id }}">{{ $course->programme_option }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course')
                                        <p class=" text-danger">{{ $message }}</p>
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
