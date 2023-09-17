<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                {{$title}}

                <div class="row">
                    @if($fac_id)
                        <div class="col-md">
                            <button class="btn btn-secondary btn-sm btn-block" wire:click="goBack"> <i class="fa fa-chevron-left"></i>  Back </button>
                        </div>
                        <div class="col-md">
                            <button class="btn btn-dark btn-sm btn-block" wire:click="resetForm"> <i class="fa fa-reload"></i>  Reset</button>
                        </div>
                    @endif
                    <div class="col-md">
                        <a class="btn btn-primary btn-sm btn-block" target="_blank" href="{{route('admission.student.view_stds', ['dept_id'=>$dept_id, 'do_id'=>$do_id, 'fac_id'=>$fac_id])}}"> <i class="fa fa-eye"></i>  View All</a>
                    </div>
                </div>

                {{-- {{$dept_id.', '.$fac_id}} --}}
            </div>

            <div class="row d-flex justify-content-center">
                @if(!$fac_id)
                    @foreach ($data as $faculty)
                        <div style="cursor: pointer; border-radius: 10px;" class="col-md-3 p-4 m-1 text-white {{$faculty->clearance_status()}}" wire:click="$set('fac_id', {{$faculty->faculties_id}})">
                            <h6>{{$faculty->faculties_name}}</h6>

                            <hr>
                            <center>
                                <p>Total Cleared: <b>{{number_format($faculty->total_cleared())}}</b></p>
                                <p>Cleared Today: <b>{{number_format($faculty->cleared_today())}}</b></p>
                                <p>Not Cleared: <b>{{number_format($faculty->uncleared())}}</b></p>
                            </center>
                        </div>
                    @endforeach
                
                @elseif(!$dept_id)
                    @foreach ($data as $department)
                        <div style="cursor: pointer; border-radius: 10px;" class="col-md-3 p-4 m-1 text-white {{$department->clearance_status()}}" wire:click="$set('dept_id', {{$department->departments_id}})">
                            <h6>{{$department->departments_name}}</h6>

                            <hr>
                            <center>
                                <p>Total Cleared: <b>{{number_format($department->total_cleared())}}</b></p>
                                <p>Cleared Today: <b>{{number_format($department->cleared_today())}}</b></p>
                                <p>Not Cleared: <b>{{number_format($department->uncleared())}}</b></p>
                            </center>
                        </div>
                    @endforeach

                @elseif(!$do_id)
                    @foreach ($data as $dept_option)
                        <a target="_blank" style="cursor: pointer; border-radius: 10px;" class="col-md-3 p-4 m-1 text-white {{$dept_option->clearance_status()}}" href="{{route('admission.student.view_stds', ['dept_id'=>$dept_id, 'do_id'=>$dept_option->do_id, 'fac_id'=>$fac_id])}}">
                            <h6>{{sprintf('%s (%s)', $dept_option->programme_option, $dept_option->programme->programme_name)}}</h6>

                            <hr>
                            <center>
                                <p>Total Cleared: <b>{{number_format($dept_option->total_cleared())}}</b></p>
                                <p>Cleared Today: <b>{{number_format($dept_option->cleared_today())}}</b></p>
                                <p>Not Cleared: <b>{{number_format($dept_option->uncleared())}}</b></p>
                            </center>
                        </a>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</div>
