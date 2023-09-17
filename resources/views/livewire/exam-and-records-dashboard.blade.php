<div>
    {{-- Search Student --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Search Student</div>

                    <form wire:submit.prevent="searchStudentResult">
                        <div class="form-group">
                            <label for="matric_number">Matric Number</label>
                            <input type="text" placeholder="Enter Student Matric Number" id="matric_number"
                                wire:model.lazy="matric_number" class="form-control" />
                            @error('matric_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">Proceed to Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Result Display --}}
    @if ($result_data)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Student Result</div>

                        <div class="table-responsive text-uppercase">
                            <table width="100%" class="mt-3 table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>matric number</th>
                                        <th>full names</th>
                                        <th colspan="3">cumulative</th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $result_data['matric_number'] }}</td>
                                        <td>{{ $result_data['name'] }}</td>
                                        <td>{{ $result_data['cummulative_cp'] }}</td>
                                        <td>{{ $result_data['cummulative_tu'] }}</td>
                                        <td>{{ $result_data['cummulative_gp'] }}</td>
                                        <td>
                                            <a href="JavaScript:void();" class="btn btn-sm btn-primary btn-block"
                                                onClick="MM_openBrWindow('{{ route('exams-and-records.results.notification_of_result', [
                                                    'bos_log_id' => $bos_log->id,
                                                    'student_log_id' => $student_log_id,
                                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                                Print Notification of Result - Presentation {{ $bos_log->presentation }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="JavaScript:void();" class="btn btn-sm btn-secondary btn-block"
                                                onClick="MM_openBrWindow('{{ route('exams-and-records.results.certificate', [
                                                    'bos_log_id' => $bos_log->id,
                                                    'student_log_id' => $student_log_id,
                                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">
                                                Print Notification of Result - Presentation {{ $bos_log->presentation }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
