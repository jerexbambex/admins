<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
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
                    <label>Programme Type</label>
                    <select class="form-control" wire:model="programme_type">
                        @forelse ($progtypes as $progtype)
                            <option value="{{ $progtype['programmet_id'] }}">{{ $progtype['programmet_name'] }}</option>
                        @empty
                            <option value="0">Null</option>
                        @endforelse
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-title">
            <div class="d-flex flex-row justify-content-between">
                <div class="mx-3" style="text-transform: uppercase;">
                    Score Sheet
                    ({{ sprintf('Set: %s, Session: %s', $current_set, $current_session) }})
                </div>
                <form wire:submit.prevent="download">
                    <button class="btn btn-sm btn-primary" type="submit">Download</button>
                </form>
                <div class="mx-2">
                    <input type="search" placeholder="Search" class="form-control " style="width: 300px;"
                        wire:model.lazy="search_param">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @include('layouts.messages')
                {{-- Table --}}
                <table class="table table-bordered">

                    {{-- Table head --}}
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="th-lg">Matric No.</th>
                            {{-- <th class="th-lg">Form No.</th> --}}
                            <th class="th-lg">Fullname</th>
                            <th class="th-lg">C.A</th>
                            <th class="th-lg">Mid Semester</th>
                            <th class="th-lg">Examination</th>
                            <th class="th-lg">Total</th>
                        </tr>
                    </thead>
                    {{-- Table head --}}

                    {{-- Table body --}}
                    <tbody>


                        @forelse ($students as $student)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $student->matric_no }}</td>
                                {{-- <td>{{ $student->matset }}</td> --}}
                                <td>{{ $student->surname }} {{ $student->firstname }} {{ $student->othernames }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">
                                    No record found!
                                </td>
                            </tr>
                        @endforelse
                </table>
                {{ $students->links() }}
                {{-- Table --}}

            </div>
        </div>
    </div>
</div>
