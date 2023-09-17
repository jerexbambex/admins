<div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.messages')
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Upload Admitted Students</div>

                    <div class="form-body">
                        <form action="{{ route('upload_admitted_applicants') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf()
                            <div class="form-group">
                                <label>Admission year</label>
                                <select wire:model="adm_year" name="adm_year" class="form-control">
                                    @foreach ($sessions as $s)
                                        <option>{{ $s->cs_session }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select File</label>
                                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" />
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-sm btn-primary btn-block" value="Upload" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
