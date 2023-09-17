<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                Applicants Filter
            </h5>

            <div class="row">
                <div class="col-md form-group">
                    <label>Programme</label>
                    <select class="form-control" wire:model="prog_id">
                        <option value="0">All</option>
                        @foreach (PROGRAMMES as $key => $programme)
                            <option value="{{ $key }}">{{ $programme }}</option>
                        @endforeach
                    </select>
                </div>
                @if (!$user_prog_type)
                    <div class="col-md form-group">
                        <label>Programme Type</label>
                        <select class="form-control" wire:model="prog_type_id">
                            <option value="0">All</option>
                            @foreach (PROG_TYPES as $key => $progType)
                                <option value="{{ $key }}">{{ $progType }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md form-group">
                    <label>Status</label>
                    <select class="form-control" wire:model="adm_status">
                        <option value="3">All</option>
                        @foreach (ADM_STATUS as $key => $admStatus)
                            <option value="{{ $key }}">{{ $admStatus }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md form-group">
                    <label>Faculty</label>
                    <select class="form-control" wire:model="faculty_id">
                        <option value="0">All</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md form-group">
                    <label>Department</label>
                    <select class="form-control" wire:model="department_id">
                        <option value="0">All</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->departments_id }}">
                                {{ $department->departments_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md form-group">
                    <label>Session</label>
                    <select class="form-control" wire:model="app_sess">
                        @foreach ($sessions as $s)
                            <option value="{{ $s->cs_session }}">
                                {{ sprintf('%s/%s', $s->cs_session, $s->cs_session + 1) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <button type="button" class="btn btn-block btn-outline-primary"
                        wire:click="$set('filterable', true)" wire:loading.attr="disabled">Filter</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex flex-row justify-content-between">
                    <div class="mx-4" style="text-transform: uppercase;">Applicants
                        ({{ sprintf('%s/%s', $app_sess, (int) $app_sess + 1) }})</div>
                    <div class="mx-4">
                        <input type="search" placeholder="Search" class="form-control " style="width: 300px;"
                            wire:model.lazy="search" />
                        <button class="btn btn-block btn-primary" type="button">Search</button>
                    </div>
                </div>
                @if (count($applicants))
                    <div class="mt-2">
                        <a class="btn btn-sm btn-info btn-block"
                            href="{{ route('download_applicants', [
                                'session' => $app_sess,
                                'faculty_id' => $faculty_id,
                                'department_id' => $department_id,
                                'prog_id' => $prog_id,
                                'prog_type_id' => $prog_type_id,
                                'adm_status' => $adm_status,
                                'search' => $search,
                            ]) }}">Download</a>
                        {{-- <button class="btn btn-sm btn-info btn-block" wire:click="download" type="button"
                            wire:loading.attr="disabled">Download</button> --}}
                        {{-- <button class="btn btn-sm btn-primary btn-block" wire:click="download_all" type="button"
                            wire:loading.attr="disabled">Download All</button> --}}
                    </div>
                @endif
            </div>
            <div class="table-responsive">
                @include('layouts.messages')
                <!--Table-->
                <table class="table table-bordered">

                    <!--Table head-->
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="th-lg">Form No.</th>
                            <th class="th-lg">Jamb No.</th>
                            <th class="th-lg">Fullname</th>
                            <th class="th-lg">Department</th>
                            <th class="th-lg">Course</th>
                            <th class="th-lg">Programme</th>
                            <th class="th-lg">Programme Type</th>
                            <th class="th-lg">Phone</th>
                            <th class="th-lg">Email</th>
                            <th class="th-lg">OLevels</th>
                            <th class="th-lg">JAMB Score</th>
                            <th class="th-lg">Submit Status</th>
                            <th class="th-lg">Admission Status</th>
                            <th class="th-lg">Date Admitted</th>
                            <th class="th-lg">Actions</th>
                            <th class="th-lg">Account Actions</th>
                        </tr>
                    </thead>
                    <!--Table head-->

                    <!--Table body-->
                    <tbody>

                        @php
                            $count = 1;
                            if (count($applicants)):
                                $page = $applicants->currentPage();
                                $paginate = $applicants->perPage();
                                $count = $page * $paginate - $paginate + 1;
                            endif;
                        @endphp
                        @forelse ($applicants as $applicant)
                            <tr>
                                <th scope="row">{{ $count++ }}</th>
                                <td>{{ $applicant->app_no }}</td>
                                <td>{{ $applicant->reg_no }}</td>
                                <td>{{ $applicant->full_name }}</td>
                                <td>{{ $applicant->department_name }}</td>
                                <td>{{ $applicant->course_name }}</td>
                                <td>{{ $applicant->programme_name }}</td>
                                <td>{{ $applicant->programme_type_name }}</td>
                                <td>{{ $applicant->student_mobiletel }}</td>
                                <td>{{ $applicant->student_email }}</td>
                                <td>
                                    {{ \App\Services\ApplicantService::getApplicantOLevelsString($applicant->olevels) }}
                                </td>
                                <td>{{ $applicant->jambs_sum_jscore }}</td>
                                <td>{{ $applicant->submit_status }}</td>
                                <td>{{ $applicant->admit_status }}</td>
                                <td>{{ $applicant->modified_date_admitted }}</td>
                                <td>
                                    <a style="color: white;" class="btn btn-info" target="_blank"
                                        href="{{ route($user_role . '.applicants.edit', ['std_logid' => $applicant->std_logid]) }}">Edit</a>
                                    @if (
                                        (!$prog_type_id || $user_role == 'cec-admin' || $user_role == 'dpp-admin') &&
                                            $applicant->admit_status != 'ADMITTED')
                                        <button class="btn btn-sm btn-primary"
                                            wire:click="admitApplicant({{ $applicant->std_id }})"
                                            onclick="confirm('Are you sure to admit this student?') || event.stopImmediatePropagation()">
                                            Admit
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-secondary"
                                        wire:click="resetPassword({{ $applicant->std_id }})"
                                        onclick="confirm('Are you sure to reset this student\'s passsword?') || event.stopImmediatePropagation()">
                                        Reset Password
                                    </button>
                                </td>
                                <td>
                                    @if (!$applicant->adm_status)
                                        {{-- Enable o-level edit --}}
                                        @if ($applicant->std_custome6)
                                            <button class="btn btn-sm btn-primary" type="button"
                                                wire:click="resetData('std_custome6', {{ $applicant->std_id }})"
                                                wire:loading.attr="disabled"
                                                onclick="confirm('Are you sure to reset this student\'s o-level?') || event.stopImmediatePropagation()">Enable
                                                O-Level Edit</button>
                                        @endif

                                        {{-- Enable jamb result edit --}}
                                        @if ($applicant->std_custome7)
                                            <button class="btn btn-sm btn-primary" type="button"
                                                wire:click="resetData('std_custome7', {{ $applicant->std_id }})"
                                                wire:loading.attr="disabled"
                                                onclick="confirm('Are you sure to reset this student\'s jamb result?') || event.stopImmediatePropagation()">Enable
                                                Jamb Result Edit</button>
                                        @endif

                                        {{-- Enable biodata edit --}}
                                        @if ($applicant->biodata)
                                            <button class="btn btn-sm btn-primary" type="button"
                                                wire:click="resetData('biodata', {{ $applicant->std_id }})"
                                                wire:loading.attr="disabled"
                                                onclick="confirm('Are you sure to reset this student\'s biodata?') || event.stopImmediatePropagation()">Enable
                                                BioData Edit</button>
                                        @endif

                                        {{-- Enable school attended edit --}}
                                        @if ($applicant->stdprogramme_id !== 1)
                                            @if ($applicant->std_custome5)
                                                <button class="btn btn-sm btn-primary" type="button"
                                                    wire:click="resetData('std_custome5', {{ $applicant->std_id }})"
                                                    wire:loading.attr="disabled"
                                                    onclick="confirm('Are you sure to reset this student\'s school attended?') || event.stopImmediatePropagation()">Enable
                                                    School Attended Edit</button>
                                            @endif
                                        @endif

                                        {{-- Enable profile photo edit --}}
                                        @if ($applicant->std_photo)
                                            @if ($applicant->std_photo)
                                                <button class="btn btn-sm btn-primary" type="button"
                                                    wire:click="resetData('std_photo', {{ $applicant->std_id }})"
                                                    wire:loading.attr="disabled"
                                                    onclick="confirm('Are you sure to reset this student\'s profile photo?') || event.stopImmediatePropagation()">Enable
                                                    Profile Photo Edit</button>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="text-center text-danger">
                                    No record found!
                                </td>
                            </tr>
                        @endforelse
                </table>
                @if (count($applicants))
                    {{ $applicants->links() }}
                @endif
                <!--Table-->

            </div>
        </div>
    </div>
</div>
