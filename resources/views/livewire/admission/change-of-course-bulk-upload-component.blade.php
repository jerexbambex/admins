<div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.messages')
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Change of Course Bulk Upload</div>

                    <div class="form-body">
                        <form action="{{ route('change_of_course_bulk_upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf()
                            <div class="form-group">
                                <label>Faculty</label>
                                <select wire:model="faculty_id" name="faculty_id" class="form-control">
                                    <option value="">Select faculty . . .</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <select wire:model="department_id" name="department_id" class="form-control">
                                    <option value="">Select department . . .</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->departments_id }}">
                                            {{ $department->departments_name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Select File</label>
                                <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" />
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
