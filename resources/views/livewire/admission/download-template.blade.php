<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                Admission Template Filter
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
                <div class="col-md form-group">
                    <label>Programme Type</label>
                    <select class="form-control" wire:model="prog_type_id">
                        @if (!$user_prog_type)
                            <option value="0">All</option>
                        @endif
                        @foreach (PROG_TYPES as $key => $progType)
                            @if (($user_prog_type && $user_prog_type == $key) || !$user_prog_type)
                                <option value="{{ $key }}">{{ $progType }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md form-group">
                    <label>Set</label>
                    <select class="form-control" wire:model="session_year">
                        @foreach ($sessions as $s)
                            <option>{{ $s->cs_session }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex flex-row justify-content-between">
                    <div class="mx-4" style="text-transform: uppercase;">Admission Template</div>
                    <div class="mx-4">
                        <input type="search" placeholder="Search" class="form-control " style="width: 300px;"
                            wire:model.lazy="search">
                        <button type="button" class="btn btn-sm btn-primary" style="width: 300px;">Search</button>
                    </div>
                </div>
                <div class="mt-2">
                    <form wire:submit.prevent="download">
                        <button class="btn btn-sm btn-primary btn-block" type="submit">Download Excel</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <!--Table-->
                <table class="table table-bordered">

                    <!--Table head-->
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="th-lg">Form No.</th>
                            <th class="th-lg">Fullname</th>
                            <th class="th-lg">Programme</th>
                            <th class="th-lg">Programme Type</th>
                            <th class="th-lg">Submit Status</th>
                        </tr>
                    </thead>
                    <!--Table head-->

                    <!--Table body-->
                    <tbody>


                        @forelse ($applicants as $applicant)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $applicant->app_no }}</td>
                                <td>{{ $applicant->full_name }}</td>
                                <td>{{ $applicant->programme->programme_name }}</td>
                                <td>{{ $applicant->progType->programmet_name }}</td>
                                <td>{{ SUBMIT_STATUS[$applicant->std_custome9] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">
                                    No record found!
                                </td>
                            </tr>
                        @endforelse
                </table>
                @if ($applicants->all())
                    {{ $applicants->links() }}
                @endif
                <!--Table-->

            </div>
        </div>
    </div>
</div>
