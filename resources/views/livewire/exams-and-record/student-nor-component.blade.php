<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Enter B.O.S Number</div>

                <div class="form-group">
                    <input type="text" class="form-control" wire:model.lazy="bos_number" />
                    <button type="button" class="btn btn-block btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Result Presentations</div>

                {{-- Notification of Results --}}
                <div class="row" wire:key="{{ $bos_log->id }}">
                    @foreach ($this->bos_logs as $bos_log)
                        <div class="col-md-6">
                            <a href="JavaScript:void();" class="btn btn-sm btn-primary btn-block"
                                onClick="MM_openBrWindow('{{ route('exams-and-records.results.notification_of_result', [
                                    'bos_log_id' => $bos_log->id,
                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                Print Notification of Result - Presentation {{ $bos_log->presentation }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
