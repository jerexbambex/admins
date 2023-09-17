<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                Admit Students Filter
            </h5>

            <div class="row">
                <div class="col-md form-group">
                    <label>Department</label>
                    <select class="form-control" wire:model="department_id">
                        <option value="0">All</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->departments_id }}">{{ $department->departments_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md form-group">
                    <label>Programme</label>
                    <select class="form-control" wire:model="prog_id">
                        {{-- <option value="0">All</option> --}}
                        @foreach (PROGRAMMES as $key => $programme)
                            <option value="{{ $key }}">{{ $programme }}</option>
                        @endforeach
                    </select>
                </div>
                @if (!$prog_type_id)
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
                    <label>Admission Year</label>
                    <select class="form-control" wire:model="adm_year">
                        @foreach ($schSessions as $admYear)
                            <option value="{{ $admYear->year }}">{{ $admYear->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-title">
            <div class="mt-2 d-flex flex-row justify-content-between">
                <div class="mx-3" style="text-transform: uppercase;">Admitted Students</div>
                <div class="mx-3">
                    <input type="search" placeholder="Search" class="form-control " style="width: 300px;"
                        wire:model.lazy="search">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @include('layouts.messages')
                <!--Table-->
                <table class="table">

                    <!--Table head-->
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="th-lg">Form No.</th>
                            <th class="th-lg">Fullname</th>
                            <th class="th-lg">Faculty</th>
                            <th class="th-lg">Department</th>
                            <th class="th-lg">Programme</th>
                            <th class="th-lg">Course</th>
                            <th class="th-lg">Admission Action</th>
                            <th class="th-lg">Action</th>
                            @if (!Auth::user()->prog_type_id && $adm_year <= 2020)
                                <th class="th-lg">Change Programme Type</th>
                            @endif
                        </tr>
                    </thead>
                    <!--Table head-->

                    <!--Table body-->
                    <tbody>


                        @forelse ($portals as $portal)
                            @php
                                $faculty = $portal->faculty;
                                $department = $portal->department;
                                $course = $portal->course;
                                $programme = $portal->programme;
                            @endphp
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $portal->appno }}</td>
                                <td>{{ $portal->fullname }}</td>
                                <td>{{ $faculty ? $faculty->faculties_name : '' }}</td>
                                <td>{{ $department ? $department->departments_name : '' }}</td>
                                <td>{{ $programme ? $programme->programme_name : '' }}</td>
                                <td>{{ $course ? $course->programme_option : '' }}</td>
                                <td>
                                    @if (!$prog_type_id && $user_role != 'revalidation-admin')
                                        <button class="btn btn-sm btn-warning text-white"
                                            wire:click="unAdmit({{ $portal->pid }})"
                                            onclick="
                                    (confirm('Are you sure you want to revert this student\'s admission?') &&
                                        confirm('Please confirm delete again, this is irreversible!'))
||
                                    event.stopImmediatePropagation()
                                    ">
                                            Revert Admission
                                        </button>
                                    @else
                                        <p>No action</p>
                                    @endif
                                </td>
                                <td>
                                    @if ($portal->hasDuplicates())
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="deleteDuplicate({{ $portal->pid }})"
                                            onclick="
                                    (
                                        confirm('Are you sure you want to delete this student\'s data?') &&
                                        confirm('Please confirm delete again, this is irreversible!')
                                    ) ||
                                    event.stopImmediatePropagation()
                                    ">
                                            Delete
                                        </button>
                                    @endif
                                    @if (!$prog_type_id || $user_role == 'cec-admin' || $user_role == 'dpp-admin' || $user_role == 'revalidation-admin')
                                        <a class="btn btn-sm btn-warning"
                                            href="{{ route($user_role . '.admitted.students.update', ['param' => base64_encode($portal->pid)]) }}">
                                            <i class="fa fa-edit"></i> Edit</a>
                                    @endif
                                </td>
                                @if (!Auth::user()->prog_type_id && $adm_year <= 2020 && $user_role != 'revalidation-admin')
                                    <td>
                                        <select wire:change="setNewProgType({{ $portal->pid }})" class="form-control"
                                            wire:model="new_prog_type_id"
                                            onchange="
                                                (
                                                    confirm('Are you sure you want to change this student programme type?') &&
                                                    confirm('Please confirm change programme type again!')
                                                ) ||
                                                event.stopImmediatePropagation()
                                            ">
                                            <option value="">Select . . .</option>
                                            @foreach ($programme_types as $programme_type)
                                                <option value="{{ $programme_type->programmet_id }}">
                                                    {{ $programme_type->programmet_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-danger text-center">
                                    No record found!
                                </td>
                            </tr>
                        @endforelse
                </table>
                {{ $portals->links() }}
                <!--Table-->

            </div>
        </div>
    </div>
</div>
