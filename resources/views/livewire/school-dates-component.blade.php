<div>
    <div class="row">
        @include('layouts.messages')
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">{{ ucfirst($action) }} School Date(s)</div>

                    <div class="form-body">
                        <form wire:submit.prevent="submit">
                            <div class="form-group">
                                <label>Session</label>
                                <select class="form-control" wire:model="sch_session"
                                    {{ $action == 'update' ? 'readonly disabled' : '' }}>
                                    @foreach ($sessions as $sess)
                                        <option value="{{ $sess->year }}">
                                            {{ $sess->session }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sch_session')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Programme Type</label>
                                <select class="form-control" wire:model="programme_type">
                                    <option value="0">FT & DPP</option>
                                    <option value="1">CEC</option>
                                    <option value="2">ALL</option>
                                </select>
                                @error('programme_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Semester</label>
                                <select class="form-control" wire:model="semester"
                                    {{ $action == 'update' ? 'readonly disabled' : '' }}>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Third Semester</option>
                                </select>
                                @error('semester')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Date Type</label>
                                <select class="form-control" wire:model="date_type"
                                    {{ $action == 'update' ? 'readonly disabled' : '' }}>
                                    @foreach ($date_types as $type)
                                        <option value="{{ $type }}">
                                            {{ ucwords(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('date_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Date (From)</label>
                                <input type="datetime-local" class="form-control" wire:model="start_date"
                                    {{ $action == 'update' ? 'readonly' : '' }} />
                                @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Date (To)</label>
                                <input type="datetime-local" class="form-control" wire:model="end_date" />
                                @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Submit" class="btn btn-block btn-outline-primary" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">School Dates Filter</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>For Session</label>
                                <select class="form-control" wire:model="app_session">
                                    @foreach ($sessions as $ses)
                                        <option value="{{ $ses->year }}">
                                            {{ $ses->session }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>For Programme Type</label>
                                <select class="form-control" wire:model="filter_progtype">
                                    <option value="0">FT & DPP</option>
                                    <option value="1">CEC</option>
                                    <option value="2">ALL</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>For Semester</label>
                                <select class="form-control" wire:model="filter_semester">
                                    <option value="all">All Semesters</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Third Semester</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Type</label>
                                <select class="form-control" wire:model="filter_date_type">
                                    <option value="all">All</option>
                                    @foreach ($date_types as $type)
                                        @if ($type === 'course_update_fee')
                                            <option value="{{ $type }}">
                                                Course Add & Delete Fee
                                            </option>
                                        @else
                                            <option value="{{ $type }}">
                                                {{ ucwords(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-block btn-secondary" wire:click="$set('filterable', true)"
                                wire:loading.attr="disabled">Filter</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">School Dates Table</div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Date Type</th>
                                    <th>Semester</th>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                    <th>Programme Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 1;
                                @endphp
                                @forelse($configs as $date)
                                    @php
                                        $date_arr = $date->toArray();
                                    @endphp
                                    @foreach ($date_types as $dt)
                                        @php
                                            $startDate = $date_arr[$dt . '_start_date'];
                                            $endDate = $date_arr[$dt . '_end_date'];
                                        @endphp
                                        @if ($startDate and $endDate and $filter_date_type == 'all' || $dt == $filter_date_type)
                                            <tr>
                                                <th>{{ $count++ }}</th>
                                                <td>
                                                    @if ($dt === 'course_update_fee')
                                                        Course Add & Delete Fee
                                                    @else
                                                        {{ ucwords(str_replace('_', ' ', $dt)) }}
                                                    @endif
                                                </td>
                                                <td>{{ SEMESTERS[$date->semester_id] }}</td>
                                                <td>{{ modifiedDate($startDate) }}</td>
                                                <td>{{ modifiedDate($endDate) }}</td>
                                                <td>{{ $date->prog_type_string }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning"
                                                        data-id="{{ $date->id }}" data-type="{{ $dt }}"
                                                        id="update">
                                                        <i class="fa fa-edit"></i> Update
                                                    </button>
                                                    {{-- <button class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button> --}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger">
                                            No record found . . . !
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).on('click', '#update', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            Livewire.emit('trigger_update', id, type);
        })
    </script>
</div>
