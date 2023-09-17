<div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title"></div>
                @include('layouts.messages')

                @if ($layout == 'form')
                    <button type="button" wire:click="$set('layout', 'view')" wire:loading.attr="disabled"
                        class="btn btn-sm btn-block btn-info">View Table</button>
                @else
                    <button type="button" wire:click="$set('layout', 'form')" wire:loading.attr="disabled"
                        class="btn btn-sm btn-block btn-info">Go to Form</button>
                @endif
            </div>
        </div>
    </div>
    @if ($layout == 'form')
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Student's Sick/Deferment List Form
                        </div>

                        <form wire:submit.prevent="submit">
                            @if (!$validated)
                                <div class="form-group">
                                    <label>Student Number</label>
                                    <input type="text" wire:model.lazy="std_no" class="form-control"
                                        placeholder="Matric Number or Form Number" />
                                    @error('std_no')
                                        <small class="text-danger">This field is required</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-block btn-outline-secondary"
                                        wire:click="validateStudent" wire:loading.attr="disbaled">
                                        Validate
                                    </button>
                                </div>
                            @else
                                <div class="form-group">
                                    <label>Student Number</label>
                                    <input type="text" readonly value="{{ $std_no }}" class="form-control" />
                                    @error('std_no')
                                        <small class="text-danger">This field is required</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" readonly value="{{ $full_name }}" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Penalty</label>
                                    <input type="text" readonly value="Sick/Deferment" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Session</label>
                                    <select wire:model.lazy="session" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($sessions as $sess)
                                            <option>{{ $sess->session }}</option>
                                        @endforeach
                                    </select>
                                    @error('session')
                                        <small class="text-danger">This field is required</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    @php
                                        $levels = $programme == 1 ? ND_LEVELS : HND_LEVELS;
                                    @endphp
                                    <label>Level</label>
                                    <select wire:model.lazy="level_id" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($levels as $key => $lvl)
                                            <option value="{{ $key }}">{{ $lvl }}</option>
                                        @endforeach
                                    </select>
                                    @error('level_id')
                                        <small class="text-danger">This field is required</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Semester</label>
                                    <select wire:model.lazy="semester" class="form-control">
                                        @foreach (range(1, $semesters) as $sem_id)
                                            <option value="{{ $sem_id }}">{{ SEMESTERS[$sem_id] }}</option>
                                        @endforeach
                                        <option value="all">All ({{ "$semesters Semesters" }})</option>
                                    </select>
                                    @error('semester')
                                        <small class="text-danger">This field is required</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Date Deferred</label>
                                    <input type="date" wire:model.lazy="date_penalized" class="form-control" />
                                    @error('date_penalized')
                                        <small class="text-danger">This field is required</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea wire:model.lazy="description" class="form-control" style="resize: none;"></textarea>
                                    @error('description')
                                        <small class="text-danger">This field is required</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-block btn-primary">Submit</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Students' Sick/Deferment List Table
                </div>
                <div class="table-responsive">
                    <div class="row">
                        <div class="float-end float-right">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="search" placeholder="Search . . ." wire:model.lazy="search_param"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Matric Number</th>
                                <th>Form Number</th>
                                <th>Name</th>
                                <th>Session</th>
                                <th>Semester</th>
                                <th>Level</th>
                                <th>Description</th>
                                <th>Deferred By</th>
                                <th>Date Deferred</th>
                                <th>Reinstated To</th>
                                <th>Reinstated By</th>
                                <th>Date Reinstated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                if ($penalties) {
                                    $page = $penalties->currentPage();
                                    $paginate = $penalties->perPage();
                                    $count = $page * $paginate - $paginate + 1;
                                }
                            @endphp
                            @forelse ($penalties as $penalty)
                                <tr>
                                    <th>{{ $count++ }}</th>
                                    <td>{{ $penalty->student->full_name }}</td>
                                    <td>{{ $penalty->matric_number }}</td>
                                    <td>{{ $penalty->student->matset }}</td>
                                    <td>{{ $penalty->student->full_name }}</td>
                                    <td>{{ $penalty->session }}</td>
                                    <td>{{ SEMESTERS[$penalty->semester_id] }}</td>
                                    <td>{{ LEVELS[$penalty->level_id] }}</td>
                                    <td>{{ $penalty->description }}</td>
                                    <td>{{ $penalty->assigned_by }}</td>
                                    <td>{{ $penalty->date_penalized }}</td>
                                    <td>{{ $penalty->reinstated_to }}</td>
                                    <td>{{ $penalty->reinstated_user }}</td>
                                    <td>{{ $penalty->reinstated_at ? modifiedDate($penalty->reinstated_at) : '' }}</td>
                                    <td>
                                        @if (!$penalty->reinstated_at)
                                            <a href="{{ route('director.student-penalties.reinstatement', ['penalty_id' => $penalty->id]) }}"
                                                type="button" class="btn btn-sm btn-block btn-primary">Reinstate</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th class="text-danger text-center" colspan="15">
                                        No record found . . . !
                                    </th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (count($penalties))
                    {{ $penalties->links() }}
                @endif
            </div>
        </div>
    @endif
</div>
