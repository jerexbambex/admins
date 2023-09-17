<div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Filter Result to Download
                </div>
                <div class="form-body">
                    <div class="row">

                        <div class="col-md">
                            <div class="form-group">
                                <label>Set</label>
                                <select class="form-control" wire:model="set_year">
                                    @foreach ($sessions as $set)
                                        <option>{{ $set->year }}</option>
                                    @endforeach
                                </select>
                                @error('set_year')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Session</label>
                                <select class="form-control" wire:model="sch_session">
                                    @foreach ($sessions as $sess)
                                        <option>
                                            {{ $sess->session }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sch_session')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Semester</label>
                                <select class="form-control" wire:model="semester">
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Third Semester</option>
                                </select>
                                @error('semester')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Programme</label>
                                <select class="form-control" wire:model="prog_id">
                                    <option value="1">ND</option>
                                    <option value="2">HND</option>
                                </select>
                                @error('prog_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>


                        <div class="col-md">

                            <div class="form-group">
                                <label>Level</label>
                                <select class="form-control" wire:model="level">
                                    @foreach ($levels as $lvl)
                                        <option value="{{ $lvl->level_id }}">{{ $lvl->level_name }}</option>
                                    @endforeach
                                </select>
                                @error('level')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Program Type</label>
                                <select class="form-control" wire:model="prog_type">
                                    <option value="1">Full Time</option>
                                    <option value="2">CEC</option>
                                    <option value="3">DPP</option>
                                </select>
                                @error('prog_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Program Option</label>
                                <select class="form-control" wire:model="option_id">
                                    <option value="">Select . . .</option>
                                    @foreach ($options as $option)
                                        <option value="{{ $option->do_id }}">{{ $option->programme_option }}</option>
                                    @endforeach
                                </select>
                                @error('option_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>


                    <div class="form-group">
                        @if ($option_id)
                            {{-- Coordinator's copy --}}
                            <div class="row">
                                <div class="col-md">
                                    <a href="JavaScript:void();" class="btn btn-sm btn-secondary btn-block"
                                        onClick="MM_openBrWindow('{{ route('hod.results.semester-result', [
                                            'encoded_session' => base64_encode($sch_session),
                                            'set' => $set_year,
                                            'level_id' => $level,
                                            'prog_type_id' => $prog_type,
                                            'semester_id' => $semester,
                                            'prog_id' => $prog_id,
                                            'option_id' => $option_id,
                                        ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                        Print Semester Result (Coordinator's Copy)
                                    </a>
                                </div>
                                <div class="col-md">
                                    <a href="JavaScript:void();" class="btn btn-sm btn-secondary btn-block"
                                        onClick="MM_openBrWindow('{{ route('hod.results.running-list', [
                                            'encoded_session' => base64_encode($sch_session),
                                            'set' => $set_year,
                                            'level_id' => $level,
                                            'prog_type_id' => $prog_type,
                                            'semester_id' => $semester,
                                            'prog_id' => $prog_id,
                                            'option_id' => $option_id,
                                        ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                        Print Semester Running List (Coordinator's Copy)
                                    </a>
                                </div>
                            </div>
                            {{-- Vetter's copy --}}
                            <div class="row">
                                <div class="col-md">
                                    <a href="JavaScript:void();" class="btn btn-sm btn-danger btn-block"
                                        onClick="MM_openBrWindow('{{ route('hod.results.semester-result-vetter', [
                                            'encoded_session' => base64_encode($sch_session),
                                            'set' => $set_year,
                                            'level_id' => $level,
                                            'prog_type_id' => $prog_type,
                                            'semester_id' => $semester,
                                            'prog_id' => $prog_id,
                                            'option_id' => $option_id,
                                        ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                        Print Semester Result (Vetter's Copy)
                                    </a>
                                </div>
                                <div class="col-md">
                                    <a href="JavaScript:void();" class="btn btn-sm btn-danger btn-block"
                                        onClick="MM_openBrWindow('{{ route('hod.results.running-list-vetter', [
                                            'encoded_session' => base64_encode($sch_session),
                                            'set' => $set_year,
                                            'level_id' => $level,
                                            'prog_type_id' => $prog_type,
                                            'semester_id' => $semester,
                                            'prog_id' => $prog_id,
                                            'option_id' => $option_id,
                                        ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                        Print Semester Running List (Vetter's Copy)
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
