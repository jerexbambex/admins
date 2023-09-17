<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">New Date</div>

            <div class="form-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="session">Session:</label>
                        <select class="form-control" wire:model.lazy="session">
                            <option value="">Select . . . </option>
                            @foreach ($sessions as $sess)
                                <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_type">Type:</label>
                        <select class="form-control" wire:model.lazy="date_type">
                            <option value="">Select . . . </option>
                            <option value="1">Lecturer Upload Dates</option>
                            <option value="2">Departmental Moderation Dates</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from">Date From:</label>
                        <input type="datetime" class="form-control" wire:model.lazy="date_from" />
                    </div>
                    <div class="form-group">
                        <label for="date_to">Date To:</label>
                        <input type="datetime" class="form-control" wire:model.lazy="date_to" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
