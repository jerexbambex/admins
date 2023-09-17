<div>
    <div class="row">
        @include('layouts.messages')
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Set Exam Dates
                    </div>

                    <div class="form-body">
                        <form wire:submit.prevent="set_dates">
                            <div class="form-group">
                                <label>Exam Start Date</label>
                                <input type="date" wire:model="start_date" class="form-control" />
                                @error('start_date') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label>Exam End Date</label>
                                <input type="date" wire:model="end_date" class="form-control" />
                                @error('end_date') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary" value="Set Dates"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Get Students
                    </div>

                    <div class="form-body">
                        <form wire:submit.prevent="get_students">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" wire:model="from_date" class="form-control" />
                                @error('from_date') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" wire:model="to_date" class="form-control" />
                                @error('to_date') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary" value="Get Students"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
