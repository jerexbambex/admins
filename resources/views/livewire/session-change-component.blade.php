<div>
    <div class="card">
        <div class="card-body">
            @include('layouts.messages')
            <div class="card-title">Change Session</div>
        </div>

        <div class="form-body">
            <form wire:submit.prevent="change_session">
                <div class="form-group">
                    <label for="cur_session">Current Session</label>
                    <select wire:model="cur_session" id="cur_session" disabled="disabled" class="form-control">
                        @foreach ($sch_sessions as $sess)
                            <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="new_session">New Session</label>
                    <select wire:model="new_session" id="new_session" class="form-control">
                        <option value="">Select . . .</option>
                        @foreach ($sch_sessions as $sess)
                            <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
