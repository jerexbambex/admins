<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">Student's Lost Access (Registration)</div>

            <div class="form-body">
                <form wire:submit.prevent="getAccess">
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label for="formNumber">Form Number</label>
                                <input type="text" id="formNumber" wire:model="appno" class="form-control" />
                                @if(!$is_valid)
                                <button class="btn btn-sm btn-secondary" type="button" wire:click="validateFormNumber">Validate</button>
                                @endif
                                @error('appno') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            @if($is_valid)
                            <div class="form-group">
                                <label for="fullName">Full Name(s)</label>
                                <input type="text" id="fullName" wire:model="fullname" class="form-control" />
                                @error('fullname') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" wire:model="gender" class="form-control">
                                    <option value="">Select . . .</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="school">Faculty</label>
                                <select id="school" wire:model="school" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{$faculty->faculties_id}}">{{$faculty->faculties_name}}</option>
                                    @endforeach
                                </select>
                                @error('school') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="dcos">Department</label>
                                <select id="dcos" wire:model="dcos" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->departments_id}}">{{$department->departments_name}}</option>
                                    @endforeach
                                </select>
                                @error('dcos') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            @endif
                        </div>
                        @if($is_valid)
                        <div class="col-md">
                            <div class="form-group">
                                <label for="adm_year">Admission Year</label>
                                <select id="adm_year" wire:model="adm_year" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($sessions as $session)
                                        <option value="{{$session->year}}">{{$session->year}}</option>
                                    @endforeach
                                </select>
                                @error('adm_year') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="state">State Of Origin</label>
                                <select id="state" wire:model="state" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($states as $state)
                                        <option value="{{$state->state_id}}">{{$state->state_name}}</option>
                                    @endforeach
                                </select>
                                @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="prog">Programme</label>
                                <select id="prog" wire:model="prog" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($progs as $key => $prog)
                                        <option value="{{$key}}">{{$prog}}</option>
                                    @endforeach
                                </select>
                                @error('prog') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="progtype">Programme Type</label>
                                <select id="progtype" wire:model="progtype" class="form-control">
                                    <option value="">Select . . .</option>
                                    @foreach ($prog_types as $key => $prog)
                                        <option value="{{$key}}">{{$prog}}</option>
                                    @endforeach
                                </select>
                                @error('progtype') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($is_valid)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-block btn-primary" value="Submit" />
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
