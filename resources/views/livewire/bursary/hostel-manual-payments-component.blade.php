<div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.messages')
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="alert alert-danger">
                            <p><b>This is only for students who paid to bank and NOT portal!</b></p>
                            <p><b>Please verify the student's Payment before you proceed!</b></p>
                        </div>
                        Student Hostel Manual (Bank) Payment

                        <div class="mx-4">
                            <button class="btn btn-primary" type="button" wire:click="addHostel"> <i class="fa fa-plus"></i> Add New</button>
                        </div>
                    </div>

                    <form class="form-body" wire:submit.prevent="submit">
                        <div class="row">
                            @foreach ($hostels as $key => $hostel)
                                <div class="col-md-4" wire:key="hostel_{{ $key }}">
                                    <div class="form-group">
                                        <input type="text" class="w-75"
                                            wire:model.lazy="hostels.{{ $key }}" 
                                            placeholder="Student's Form Number"
                                        />
                                        <button class="btn btn-danger w-10" type="button"
                                            wire:click="removeHostel({{ $key }})"> <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="btn btn-block btn-primary" type="submit">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
