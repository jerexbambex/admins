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
                        <div class="card-title">Student's Death Form</div>

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
                                    <input type="text" readonly value="Death" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Date Dead</label>
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
                    Students' Death Table
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
                                <th>Matric Number</th>
                                <th>Form Number</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Input By</th>
                                <th>Date Dead</th>
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
                                    <td>{{ $penalty->matric_number }}</td>
                                    <td>{{ $penalty->std_no }}</td>
                                    <td>{{ $penalty->student->full_name }}</td>
                                    <td>{{ $penalty->description }}</td>
                                    <td>{{ $penalty->assigned_by }}</td>
                                    <td>{{ $penalty->date_penalized }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <th class="text-danger text-center" colspan="6">
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
