<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Course Structure View
            </div>

            <div class="form-body row">
                <form wire:submit.prevent="print" class="col-md-6">
                    <div class="form-group">
                        <label for="faculty">Faculty</label>
                        <select wire:model="faculty" id="faculty" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($faculties as $fac)
                                <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}</option>
                            @endforeach
                        </select>
                        @error('faculty')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select wire:model="department" id="department" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->departments_id }}">{{ $dept->departments_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="programme">Programme</label>
                        <select wire:model="programme" id="programme" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($programmes as $prog)
                                <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                            @endforeach
                        </select>
                        @error('programme')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="session_year">Set</label>
                        <select wire:model="session_year" id="session_year" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->year }}">{{ $session->year }}</option>
                            @endforeach
                        </select>
                        @error('session_year')
                            {{ $message }}
                        @enderror
                    </div>
                    @if ($department && $programme && $session_year)
                        <div class="form-group">
                            {{-- <button type="submit" class="btn btn-block btn-primary">Print</button> --}}
                            <a href="JavaScript:void();" class="btn btn-sm btn-primary btn-block"
                                onClick="MM_openBrWindow('{{ route('print_course_structure', [
                                    'dept_id' => $department,
                                    'prog_id' => $programme,
                                    'session' => $session_year,
                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">Print</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
