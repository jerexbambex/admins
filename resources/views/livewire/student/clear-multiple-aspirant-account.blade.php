<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Multiple Clear Option</div>

                    <div class="form-group d-flex justify-content-between">
                        <div>
                            <input type="number" class="form-control" wire:model="multiple">
                            @error('multiple') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div>
                            <button {{ $mc_status ? 'disabled' : '' }} class="btn btn-block btn-sm btn-dark" wire:click="multipleClear">
                                {{ $mc_status ? 'Please wait...' : 'Clear Multiple'}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Clear Multiple Accounts (Aspirants)</div>
                    @include('layouts.messages')
                    <div class="list-group">
                        @foreach ($portals as $portal)
                        <div class="list-group-item d-flex justify-content-between">
                            <div>
                                {{" $portal->fullname ($portal->appno) "}}
                            </div>
                            <div>
                                <button class="btn btn-sm btn-secondary" type="button" wire:click="clearDuplicates('{{$portal->appno}}')">Clear</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{$portals->links()}}

                </div>
            </div>
        </div>
    </div>
</div>
