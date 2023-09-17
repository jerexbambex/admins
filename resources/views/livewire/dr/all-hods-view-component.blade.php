<div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.messages')
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Filter</div>
                    <div class="form-body">
                        <div class="form-group col-md">
                            <label>Faculty</label>
                            <select wire:model="faculty_id" class="form-control">
                                <option value="">All</option>
                                @foreach($faculties as $faculty)
                                <option value="{{$faculty->faculties_id}}">{{$faculty->faculties_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md">
                            <label>Department</label>
                            <select wire:model="department_id" class="form-control">
                                <option value="">All</option>
                                @foreach($departments as $department)
                                <option value="{{$department->departments_id}}">{{$department->departments_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    {{-- <th>Email</th> --}}
                                    <th>Department</th>
                                    {{-- <th>Faculty</th> --}}
                                    <th>Default Password</th>
                                    <th>Account Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $page = $hods->currentPage();
                                    $paginate = $hods->perPage();
                                    $count = (($page * $paginate) - $paginate) + 1;
                                @endphp
                                @forelse ($hods as $hod)
                                    <tr>
                                        <th>{{$count++}}</th>
                                        <td>{{$hod->name}}</td>
                                        <td>{{$hod->username}}</td>
                                        {{-- <td>{{$hod->email}}</td> --}}
                                        <td>{{$hod->department->departments_name}}</td>
                                        {{-- <td>{{$hod->department->faculty->faculties_name}}</td> --}}
                                        <td>{{$hod->username}}</td>
                                        <td>
                                            @if($hod->status)
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-danger">Disabled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button
                                                onclick="
                                                (confirm('Are you sure you want to reset this HOD\'s password?') && confirm('Please confirm password reset again!')) ||
                                                event.stopImmediatePropagation()
                                                "
                                                wire:click="resetPassword({{$hod->id}})"
                                                class="btn btn-warning btn-sm"
                                            >Reset Password</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th colspan="8">
                                            <p class="text-danger text-center">No record found!</p>
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
