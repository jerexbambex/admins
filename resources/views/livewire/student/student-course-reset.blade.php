<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Student Course Reset</div>

                    <div class="form-body">
                        @include('layouts.messages')
                        <form wire:submit.prevent="resetStudentCourse">
                            <div class="form-group">
                                <label for="student_data">Student ID</label>
                                <input type="text" id="student_data" wire:model="student_data" class="form-control" placeholder="Form Number or Matric Number" />
                                @error('student_data') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="level">Level</label>
                                <select id="level" wire:model="level" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach($levels as $level)
                                    <option value="{{$level->level_id}}">{{$level->level_name}}</option>
                                    @endforeach
                                </select>
                                @error('level') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select id="semester" wire:model="semester" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach(SEMESTERS as $semester)
                                    <option value="{{$semester}}">{{$semester}}</option>
                                    @endforeach
                                </select>
                                @error('semester') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="session_ref">Session</label>
                                <select id="session_ref" wire:model="session_ref" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach($sessions as $school_session)
                                    <option value="{{$school_session->year}}">{{$school_session->session}}</option>
                                    @endforeach
                                </select>
                                @error('session_ref') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
