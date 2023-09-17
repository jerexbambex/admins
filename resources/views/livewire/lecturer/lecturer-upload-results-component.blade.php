<div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.messages')
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Upload Result</div>

                    <div class="form-body">
                        {{-- <form wire:submit.prevent="upload"> --}}
                        <form method="post" enctype="multipart/form-data" action="{{ route('lecturer.result.upload') }}">
                            @csrf
                            <div class="form-group">
                                <label>Session</label>
                                <select name="session" wire:model="session" class="form-control" required>
                                    @foreach ($sessions as $session)
                                        <option>{{ $session->session }}</option>
                                    @endforeach
                                </select>
                                @error('session')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Course</label>
                                <select name="course_id" class="form-control" required>
                                    <option value="">Select . . .</option>
                                    @foreach ($courses as $course)
                                        @php
                                            $c = $course->course;
                                            $programmeType = $course->programmeType()->first(['programmet_name']);
                                            $option = $c->department_course()->first(['programme_option']);
                                            $sets = implode(',', $c->for_set);
                                        @endphp
                                        <option value="{{ $course->id }}">
                                            {{ sprintf('%s (%s) - %s (Set: %s)', $c->thecourse_code, $programmeType->programmet_name, $option->programme_option, $sets) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Select File</label>
                                <input type="file" name="file" class="form-control" required
                                    accept=".csv,.xlsx,.xls" />
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary btn-block" value="Upload" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
