<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @include('layouts.messages')
                    <div class="card-title">
                        Student's Suspension Form
                    </div>

                    <form wire:submit.prevent="submit">
                        @php
                            $student = $penalty->student;
                        @endphp
                        <div class="form-group">
                            <label>Student Number</label>
                            <input type="text" readonly value="{{ $penalty->std_no }}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Penalty</label>
                            <input type="text" readonly value="{{ $penalty->penalty }}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Session</label>
                            <input type="text" readonly value="{{ $penalty->session }}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Level</label>
                            <input type="text" readonly value="{{ LEVELS[$penalty->level_id] }}"
                                class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Semester</label>
                            <input type="text" readonly value="{{ SEMESTERS[$penalty->semester_id] }}"
                                class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Reinstate To</label>
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
                            <input type="checkbox" id="with_next" wire:click="$set('with_next', {{ !$with_next }})"
                                {{ $with_next ? 'checked' : '' }} wire:loading.attr="disabled" />
                            <label for="with_next">Include normal session data</label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-block btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
