<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                Students Filter
            </h5>

            {{-- <div class="row">
                <div class="col-md form-group">
                    <label>Department</label>
                    <select class="form-control" wire:model="department_id">
                        <option value="0">All</option>
                        @foreach($departments as $department)
                        <option value="{{$department->departments_id}}">{{$department->departments_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md form-group">
                    <label>Clear Status</label>
                    <select class="form-control" wire:model="clear_status">
                        <option value="all">All</option>
                        <option value="0" class="bg-danger">Not Cleared</option>
                        <option value="1" class="bg-success">Cleared</option>
                    </select>
                </div>
                <div class="col-md form-group">
                    <label>Session</label>
                    <select class="form-control" wire:model="adm_year">
                        @foreach($sch_sessions as $sch_session)
                        <option value="{{$sch_session->year}}">{{$sch_session->session}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md form-group">
                    <label>Programme</label>
                    <select class="form-control" wire:model="prog_id">
                        <option value="">All</option>
                        @foreach(PROGRAMMES as $key => $programme)
                        <option value="{{$key}}">{{$programme}}</option>
                        @endforeach
                    </select>
                </div>
                @if(!auth()->user()->prog_type_id)
                <div class="col-md form-group">
                    <label>Programme Type</label>
                    <select class="form-control" wire:model="prog_type_id">
                        <option value="">All</option>
                        @foreach(PROG_TYPES as $key => $progType)
                        <option value="{{$key}}">{{$progType}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            @if($clear_status)  
            <div class="row">
                <div class="col-md form-group">
                    <label>Filter cleared from date:</label>
                    <input type="date" class="form-control" wire:model="date_from" />
                </div>
                <div class="col-md form-group">
                    <label>Filter cleared to date:</label>
                    <input type="date" class="form-control" wire:model="date_to" />
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">Summary</div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Total Cleared</th>
                            <td>{{$total_cleared}}</td>
                        </tr>
                        <tr>
                            <th>Cleared Today</th>
                            <td>{{$cleared_today}}</td>
                        </tr>
                        <tr>
                            <th>Not Cleared</th>
                            <td>{{$uncleared}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">
            <div class="mt-2 d-flex flex-row justify-content-between">
                <div class="mx-4" style="text-transform: uppercase;">
                    All Students <br>
                    <form wire:submit.prevent="download">
                        <button type="submit" class="btn btn-primary">Download</button>
                    </form>
                </div>
                <div class="mx-4">
                    <input type="search" placeholder="Search" class="form-control " style="width: 300px;" wire:model.lazy="search">
                    <button class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @include('layouts.messages')
                @if (Session::has('message'))
                <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                @endif
                <!--Table-->
                <table class="table">

                    <!--Table head-->
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="th-lg">Matric No.</th>
                            <th class="th-lg">Form No.</th>
                            <th class="th-lg">Surname</th>
                            <th class="th-lg">Firstname</th>
                            <th class="th-lg">Othernames</th>
                            <th class="th-lg">Department</th>
                            <th class="th-lg">Submit Status</th>
                            <th class="th-lg">Date Cleared</th>
                            <th class="th-lg">Clearance Files</th>
                            <th class="th-lg">Action</th>
                        </tr>
                    </thead>
                    <!--Table head-->

                    <!--Table body-->
                    <tbody>

                        @php
                            if($students) :
                                $page = $students->currentPage();
                                $paginate = $students->perPage();
                                $count = ((($page * $paginate) - $paginate) + 1);
                            endif;
                        @endphp
                        @forelse ($students as $student)
                        <tr>
                            <th scope="row">{{ $count++ }}</th>
                            <td>{{$student->matric_no}}</td>
                            <td>{{$student->matset ? $student->matset : $student->matric_no}}</td>
                            <td>{{$student->surname}}</td>
                            <td>{{$student->firstname}}</td>
                            <td>{{$student->othernames}}</td>
                            <td>{{$student->department_name}}</td>
                            <td>{{$student->submit_status}}</td>
                            <td>{{$student->modified_date_cleared}}</td>
                            <td>
                                <a 
                                    class="btn btn-primary text-white" 
                                    href="{{route('admission.student.download.file', ['std_logid'=>$student->std_logid])}}"
                                    target="_blank"
                                >View File</a>
                            </td>
                            <td>
                                @if(!$student->eclearance)
                                <a 
                                    class="btn btn-warning text-white" wire:click="clear({{$student->std_id}})" 
                                    onclick="confirm('Are you sure you want to clear this student?') || event.stopImmediatePropagation()"
                                >Clear</a>
                                @else
                                <div class="btn btn-success text-white" wire:click="unClear({{$student->std_id}})" onclick="
                                    (
                                        confirm('Are you sure you want to revert clearance?') &&
                                        confirm('Please confirm revert again!')
                                    ) ||
                                    event.stopImmediatePropagation()
                                    ">Cleared</div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-danger">
                                No record found!
                            </td>
                        </tr>
                        @endforelse
                </table>
            </div>
            {{$students->links()}}
        </div>
    </div>
</div>