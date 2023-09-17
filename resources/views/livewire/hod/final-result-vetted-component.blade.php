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

                {{-- Vetted's copy --}}
                @foreach ($this->bos_logs as $bos_log)
                    <div class="row" wire:key="{{ $bos_log->id }}">
                        <div class="col-md">
                            <a href="JavaScript:void();" class="btn btn-sm btn-danger btn-block"
                                onClick="MM_openBrWindow('{{ route('hod.results.semester-result-vetter', [
                                    'encoded_session' => base64_encode($bos_log->session),
                                    'set' => $bos_log->adm_year,
                                    'level_id' => $bos_log->level_id,
                                    'prog_type_id' => $bos_log->prog_type_id,
                                    'semester_id' => $bos_log->semester_id,
                                    'prog_id' => $bos_log->prog_id,
                                    'option_id' => $bos_log->option_id,
                                    'bos_log_id' => $bos_log->id,
                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                Print Semester Result (Vetted Copy) - {{ $bos_log->presentation }}
                            </a>
                        </div>
                        <div class="col-md">
                            <a href="JavaScript:void();" class="btn btn-sm btn-danger btn-block"
                                onClick="MM_openBrWindow('{{ route('hod.results.running-list-vetter', [
                                    'encoded_session' => base64_encode($bos_log->session),
                                    'set' => $bos_log->adm_year,
                                    'level_id' => $bos_log->level_id,
                                    'prog_type_id' => $bos_log->prog_type_id,
                                    'semester_id' => $bos_log->semester_id,
                                    'prog_id' => $bos_log->prog_id,
                                    'option_id' => $bos_log->option_id,
                                    'bos_log_id' => $bos_log->id,
                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                Print Semester Running List (Vetted Copy) - {{ $bos_log->presentation }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
