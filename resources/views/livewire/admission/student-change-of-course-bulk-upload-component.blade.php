<div class="card">
    <div class="card-body">
        <div class="card-title">Students' Change Of Course (Bulk Upload)</div>
        @include('layouts.messages')

        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('student_change_of_course_bulk_upload') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf()
                    <div class="form-body">
                        <div class="form-group">
                            <label for="faculty">Faculty</label>
                            <select wire:model="faculty" name="faculty" id="faculty" class="form-control">
                                <option value="">Select . . .</option>
                                @foreach ($faculties as $fac)
                                    <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select wire:model="department" name="department" id="department" class="form-control">
                                <option value="">Select . . .</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->departments_id }}">
                                        {{ $dept->departments_name }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="programme">Programme</label>
                            <select wire:model="programme" name="programme" id="programme" class="form-control">
                                <option value="">Select . . .</option>
                                @foreach ($programmes as $prog)
                                    <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('programme')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="option">Dept. Option</label>
                            <select wire:model="option" name="option" id="option" class="form-control">
                                <option value="">Select . . .</option>
                                @foreach ($options as $opt)
                                    <option value="{{ $opt->do_id }}">{{ $opt->programme_option }}
                                    </option>
                                @endforeach
                            </select>
                            @error('option')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" name="file" id="file" class="form-control"
                                accept=".xlsx,.xls,.csv" />
                            @error('file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Upload" class="btn btn-sm btn-block btn-primary" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form wire:submit.prevent="download_template">
                    <div class="form-group">
                        <label>Download Template</label>
                        <button type="submit" class="btn btn-sm btn-block btn-outline-secondary">Download</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
