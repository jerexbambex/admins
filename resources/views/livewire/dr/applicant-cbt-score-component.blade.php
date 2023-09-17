<div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.messages')
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex w-full justify-between">
                        <h2>Applicants' CBT Scores Upload</h2>
                        <button class="btn btn-secondary" type="button" wire:click="download_template"
                            wire:loading.attr="disabled">
                            <i class="fa fa-download"></i> Download Template
                        </button>
                    </div>

                    <div class="form-body">
                        {{-- <form wire:submit.prevent="upload"> --}}
                        <form action="{{ route('applicants_score_upload') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf()
                            <div class="form-group">
                                <label>Select File</label>
                                <input type="file" name="file" wire:model="file" class="form-control" />
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary btn-block" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
