<div class="card">
    <div class="card-body">
        <div class="card-title">
            Result Submission Component
        </div>
        
        @include('layouts.messages')
        <div class="form-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Programme</label>
                    <select wire:model="programme" class="form-control">
                        <option value="">Select . . .</option>
                        @foreach ($programmes as $prog)
                            <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                        @endforeach
                    </select>
                    @error('programme')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Dept. Option</label>
                    <select wire:model="option_id" class="form-control">
                        <option value="">Select . . .</option>
                        @foreach ($dept_options as $dept_option)
                            <option value="{{ $dept_option->do_id }}">{{ $dept_option->programme_option }}</option>
                        @endforeach
                    </select>
                    @error('option_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Level</label>
                    <select wire:model="level_id" class="form-control">
                        <option value="">Select . . .</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Set</label>
                    <select wire:model="app_session" class="form-control">
                        <option value="">Select . . .</option>
                        @foreach ($sessions as $set)
                            <option>{{ $set->year }}</option>
                        @endforeach
                    </select>
                    @error('app_session')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Session</label>
                    <select wire:model="session" class="form-control">
                        <option value="">Select . . .</option>
                        @foreach ($sessions as $sess)
                            <option>{{ $sess->session }}</option>
                        @endforeach
                    </select>
                    @error('session')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Programme Type</label>
                    <select wire:model="programme_type" class="form-control">
                        <option value="">Select . . .</option>
                        @foreach ($progtypes as $progtype)
                            <option value="{{ $progtype->programmet_id }}">{{ $progtype->programmet_name }}</option>
                        @endforeach
                    </select>
                    @error('programme_type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>Semester</label>
                    <select wire:model="semester" class="form-control">
                        <option>First Semester</option>
                        <option>Second Semester</option>
                        @if ($programme_type == 2)
                            <option>Third Semester</option>
                        @endif
                    </select>
                    @error('semester')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>B.O.S Number</label>
                    <input wire:model="bos_number" class="form-control" />
                    @error('bos_number')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-12 form-group">
                    <button type="button" class="btn btn-sm btn-block btn-outline-primary" wire:click="submit"
                        wire:loading.attr="disabled"
                        onclick="(confirm('Are you sure that bos number is correct?') && confirm('Please confirm again!')) || event.stopImmediatePropagation()">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
