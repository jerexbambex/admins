<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Filter
                        <div class="row mt-2">
                            <div class="col-md form-group">
                                <label>Session</label>
                                <select wire:model="current_session" class="form-control">
                                    @foreach ($sessions as $session)
                                        <option>{{ $session->session }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md form-group">
                                <label>Set</label>
                                <select wire:model="current_set" class="form-control">
                                    @foreach ($students_sets as $set)
                                        <option>{{ $set }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md form-group">
                                <input type="search" placeholder="Search" class="form-control"
                                    wire:model.lazy="search_param">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <form wire:submit.prevent="download">
                                    <button class="btn btn-sm btn-primary btn-block" type="submit">Download
                                        Excel</button>
                                </form>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-primary btn-block" wire:click="downloadSheet">Download
                                    Scoresheet</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-title">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="ml-3 text-uppercase">Score Sheet</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @include('layouts.messages')
                        <!--Table-->
                        <table class="table table-bordered">

                            <!--Table head-->
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th class="th-lg">Matric No.</th>
                                    <th class="th-lg">Fullname</th>
                                    <th class="th-lg">C.A</th>
                                    <th class="th-lg">Mid Semester</th>
                                    <th class="th-lg">Examination</th>
                                    <th class="th-lg">Total</th>
                                    <th class="th-lg">Grade</th>
                                </tr>
                            </thead>
                            <!--Table head-->

                            <!--Table body-->
                            <tbody>
                                @php
                                    $page = $students->currentPage();
                                    $paginate = $students->perPage();
                                    $count = $page * $paginate - $paginate + 1;
                                @endphp

                                @forelse ($students as $student)
                                    @php($result = $student->result($current_session, $course_id))
                                    <tr>
                                        <th scope="row">{{ $count++ }}</th>
                                        <td>{{ $student->matric_no }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $result->c_a ?? '' }}</td>
                                        <td>{{ $result->mid_semester ?? '' }}</td>
                                        <td>{{ $result->examination ?? '' }}</td>
                                        <td>{{ $result->total ?? '' }}</td>
                                        <td>{{ $result->grade ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger">
                                            No record found!
                                        </td>
                                    </tr>
                                @endforelse
                        </table>
                        {{ $students->links() }}
                        <!--Table-->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
