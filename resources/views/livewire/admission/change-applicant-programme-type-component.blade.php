<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Search Applicant
            </div>
            @include('layouts.messages')

            <div class="form-body">
                <div class="form-group">
                    <form wire:submit.prevent="searchApplicant">
                        <input type="text" class="form-control" placeholder="Search Applicant's form number"
                            wire:model.prevent="appno" />
                        <button type="submit" class="btn btn-block btn-primary">Search</button>
                    </form>
                </div>
            </div>

            @if ($applicant)
                <div class="table-responsive">
                    <div class="table table-bordered">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Form Number</th>
                                    <th>Reg Number</th>
                                    <th>Faculty</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Programme</th>
                                    <th>Programme Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>1</th>
                                    <td>{{ $applicant->full_name }}</td>
                                    <td>{{ $applicant->app_no }}</td>
                                    <td>{{ $applicant->reg_no }}</td>
                                    <td>{{ $applicant->faculty_name }}</td>
                                    <td>{{ $applicant->department_name }}</td>
                                    <td>{{ $applicant->course_name }}</td>
                                    <td>{{ $applicant->programme_name }}</td>
                                    <td>{{ $applicant->programme_type_name }}</td>
                                    <td>
                                        <select wire:change="setNewProgType" class="form-control"
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
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <div class="col-md-8">
                        {{ $is_accepted_list ? 'Accepted List' : 'Migrated List' }} <br />
                        <button type="button" class="btn btn-sm btn-secondary" wire:click="changeListType"
                            wire:loading.attr="disabled">
                            <i class="fa fa-eye"></i>
                            {{ $is_accepted_list ? 'View Migrated List' : 'View Accepted List' }}
                        </button>

                        <button class="btn btn-info" type="button" wire:click="downloadAccepted"
                            wire:loading.attr="disabled">
                            Download Accepted List
                        </button>
                    </div>
                    <div class="col-md-4">
                        <input type="search" wire:model.lazy="search" placeholder="Search by form number"
                            class="form-control" />
                        <button type="button" class="btn btn-primary btn-block">Search</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Old Form Number</th>
                            <th>New Form Number</th>
                            <th>Faculty</th>
                            <th>Department</th>
                            <th>Programme</th>
                            <th>Old Programme Type</th>
                            <th>New Programme Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            if ($list) {
                                $page = $list->currentPage();
                                $paginate = $list->perPage();
                                $count = $page * $paginate - $paginate + 1;
                            }
                        @endphp
                        @forelse($list as $data)
                            @php
                                $applicant = $data->applicant;
                            @endphp
                            <tr>
                                <th>{{ $count++ }}</th>
                                <td>{{ $applicant->full_name }}</td>
                                <td>{{ $data->initial_appno }}</td>
                                <td>{{ $data->new_appno }}</td>
                                <td>{{ $applicant->faculty_name }}</td>
                                <td>{{ $applicant->department_name }}</td>
                                <td>{{ $applicant->programme_name }}</td>
                                <td>{{ PROG_TYPES[$data->initial_prog_type] }}</td>
                                <td>{{ PROG_TYPES[$data->new_prog_type] }}</td>
                                <td>
                                    @if ($is_accepted_list)
                                        <button type="button" class="btn btn-block btn-primary"
                                            wire:click.prevent="reverse({{ $data->id }})"
                                            onclick="(confirm('Are you sure you want to reverse this student admission request?') &&
                                        confirm('Please confirm change this action again!')) ||
                                        event.stopImmediatePropagation()
                                        ">
                                            Reverse
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-danger text-center">
                                    No record found . . . !
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $list->links() }}
        </div>
    </div>
</div>
