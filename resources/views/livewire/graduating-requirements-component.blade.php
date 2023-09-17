<div class="row">
    {{-- Filter Graduating Requirements --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Graduating Requirement Filter
                </div>

                <div class="form-body row">
                    @if ($faculties)
                        <div class="col-md-4 form-group">
                            <label for="faculty">Faculty</label>
                            <select id="faculty" class="form-control" wire:model="faculty_id">
                                <option value="">Select . . .</option>
                                @foreach ($faculties as $fac)
                                    <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}</option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                    @if ($departments)
                        <div class="col-md-4 form-group">
                            <label for="department">Department</label>
                            <select id="department" class="form-control" wire:model="department_id">
                                <option value="">Select . . .</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->departments_id }}">{{ $dept->departments_name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                    <div class="col-md-4 form-group">
                        <label for="programme">Programme</label>
                        <select id="programme" class="form-control" wire:model="programme_id">
                            <option value="">Select . . .</option>
                            @foreach ($programmes as $prog)
                                <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                            @endforeach
                        </select>
                        @error('programme_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-8 form-group">
                        <label for="dept_option">Dept. Option</label>
                        <select id="dept_option" class="form-control" wire:model="dept_option_id">
                            <option value="">Select . . .</option>
                            @foreach ($dept_options as $opt)
                                <option value="{{ $opt->do_id }}">
                                    {{ sprintf('%s (%s)', $opt->programme_option, $opt->programme->aprogramme_name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('dept_option_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="admission_year">Admission Year / Set</label>
                        <select id="admission_year" class="form-control" wire:model="admission_year">
                            <option value="">Select . . .</option>
                            @foreach ($school_sessions as $sess)
                                <option>{{ $sess->year }}</option>
                            @endforeach
                        </select>
                        @error('admission_year')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- <div class="form-group row">
                    <button class="btn btn-primary btn-block w-full" type="button" wire:click="submit"
                        wire:loading.attr="disabled">
                        Filter / Load Graduating Requirements
                    </button>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Add / Edit Graduating Requirement --}}
    <div class="col-md-4">
        @include('layouts.messages')
        <div class="card">
            <div class="card-body">
                @if ($is_update)
                    <div class="card-title d-flex w-full justify-between">
                        Update Graduating Requirement

                        <button type="button" class="btn btn-sm btn-secondary" wire:click="resetUpdatable"
                            wire:loading.attr="disabled">Add New</button>
                    </div>
                @else
                    <div class="card-title">
                        Add New Graduating Requirement
                    </div>
                @endif

                <form class="form-body" wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="core">Core:</label>
                        <input type="number" class="form-control" wire:model.lazy="core" />
                        @error('core')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="elective">Elective:</label>
                        <input type="number" class="form-control" wire:model.lazy="elective" />
                        @error('elective')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="gs">G.S:</label>
                        <input type="number" class="form-control" wire:model.lazy="gs" />
                        @error('gs')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="submit" value="{{ $is_update ? 'Update Requirement' : 'Add Requirement' }}"
                            class="btn btn-block btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Graduating Requirements List --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Graduating Requirements List
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Department Option</th>
                            <th>Programme</th>
                            <th>Admission Year</th>
                            <th>Core</th>
                            <th>Elective</th>
                            <th>G.S</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @php
                                if ($graduating_requirements):
                                    $page = $graduating_requirements->currentPage();
                                    $paginate = $graduating_requirements->perPage();
                                
                                    $count = $page * $paginate - $paginate + 1;
                                endif;
                            @endphp

                            @forelse ($graduating_requirements as $gr)
                                <tr>
                                    <th>{{ $count++ }}</th>
                                    <td>{{ $gr->dept_option->programme_option }}</td>
                                    <td>{{ $gr->dept_option->programme->programme_name }}</td>
                                    <td>{{ $gr->admission_year }}</td>
                                    <td>{{ $gr->core }}</td>
                                    <td>{{ $gr->elective }}</td>
                                    <td>{{ $gr->gs }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm"
                                            wire:click="setUpdatable({{ $gr->id }})"
                                            wire:loading.attr="disabled">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-danger" colspan="7">
                                        No record found . . . !
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($graduating_requirements)
                    {{ $graduating_requirements->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
