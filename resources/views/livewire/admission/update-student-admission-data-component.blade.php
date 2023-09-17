<div>
    <div class="row">
        @include('layouts.messages')
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Update Student Admission Data
                    </div>

                    <div class="form-body">
                        <form wire:submit.prevent="submit">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ $name }}" />
                            </div>
                            <div class="form-group">
                                <label>Form Number</label>
                                <input type="text" class="form-control" value="{{ $form_number }}" />
                            </div>
                            <div class="form-group">
                                <label>Faculty</label>
                                <select wire:model="faculty" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($faculties as $fac)
                                        <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <select wire:model="department" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->departments_id }}">{{ $dept->departments_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Programme</label>
                                <select wire:model="programme" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($programmes as $prog)
                                        <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Programme Type</label>
                                <select wire:model="programme_type" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach (PROG_TYPES as $prog_t_id => $prog_t)
                                        <option value="{{ $prog_t_id }}">{{ $prog_t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Course</label>
                                <select wire:model="course" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($options as $opt)
                                        <option value="{{ $opt->do_id }}">{{ $opt->programme_option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>State</label>
                                <select wire:model="state" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($states as $st)
                                        <option value="{{ $st->state_id }}">{{ $st->state_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select wire:model="gender" class="form-control">
                                    <option value="">Select . . .</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
