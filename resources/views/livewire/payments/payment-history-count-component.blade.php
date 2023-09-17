<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Payment History Count Filter</div>

                    <div class="form-group">
                        <label for="session">Session</label>
                        <select wire:model="session_year" id="session" class="form-control">
                            @foreach ($sch_sessions as $ses)
                                <option value="{{ $ses->year }}">{{ $ses->session }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <p>{{"$payment_type, Payment History for Session: $session_year "}}</p>
        </div>

        @foreach (PROG_TYPES as $pt_id => $type)
            @php
                $sems = 2;
                if ($pt_id == 2) {
                    $sems = 3;
                }
                $sems = range(1, $sems);
            @endphp
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ "$type Payment Count" }}</div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    @foreach ($sems as $sem)
                                        <th>{{ SEMESTERS[$sem] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (LEVELS as $level_id => $level)
                                    <tr>
                                        <th>{{ $level }}</th>
                                        @foreach ($sems as $sem_id)
                                            <td>{{ \App\Services\PaymentHistoryService::getHistoryCount($level_id, $pt_id, $sem_id, $payment_type, $session_year) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
