<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Reinstatement Form Indefinite Penalty Form</div>

                @include('layouts.messages')

                <form wire:submit.prevent="validateStudent">
                    <div class="form-group">
                        <label>Form Number</label>
                        <input type="text" wire:model.lazy="std_no" class="form-control" />
                        @error('std_no')
                            <small class="text-danger">This field is required</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-block btn-outline-secondary"
                            wire:loading.attr="disbaled">
                            Reinstate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
