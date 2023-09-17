<div>
    <div class="container" style="padding: 30px 0;">
        <div class="d-flex flex-column">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="mx-2" style="text-transform: uppercase;">Update Student</div>
                            <div class="mx-2">
                                <a href="{{route('dr.students')}}" class="btn btn-success pull-right">All Students</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    @if(Session::has('message'))
                    <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                    @endif
                    <form class="form-horizontal" wire:submit.prevent="updatePortal">
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Form Number</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Form Number" class="form-control input-md" wire:model="appno">
                                @error('appno')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Full Name</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Full Name" class="form-control input-md" wire:model="fullname">
                                @error('fullname')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">State</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="state">
                                        @foreach ($states as $state)
                                        <option value="{{$state->state_id}}">{{$state->state_name}}</option>
                                        @endforeach
                                        @error('state')
                                        <p class=" text-danger">{{$message}}</p>
                                        @enderror
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Gender</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Gender" class="form-control input-md" wire:model="gender">
                                    @error('gender')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Faculty</label>
                            <div class="col-md-12">
                                <select class="form-control input-md" wire:model="faculty">
                                    @foreach ($faculties as $faculty)
                                    <option value="{{$faculty->faculties_id}}">{{$faculty->faculties_name}}</option>
                                    @endforeach
                                    @error('faculty')
                                    <p class=" text-danger">{{$message}}</p>
                                    @enderror
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Programme</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="programme">
                                        @foreach ($programmes as $programme)
                                        <option value="{{$programme->programme_id}}">{{$programme->programme_name}}</option>
                                        @endforeach
                                        @error('programme')
                                        <p class=" text-danger">{{$message}}</p>
                                        @enderror
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="col-md-12 control-label">Programme Type</label>
                                <div class="col-md-12">
                                    <select class="form-control input-md" wire:model="programme_type">
                                        @foreach ($progtypes as $progtype)
                                        <option value="{{$progtype->programmet_id}}">{{$progtype->programmet_name}}</option>
                                        @endforeach
                                        @error('progtype')
                                        <p class=" text-danger">{{$message}}</p>
                                        @enderror
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Level</label>
                            <div class="col-md-12">
                                <select class="form-control input-md" wire:model="level">
                                    @foreach ($levels as $level)
                                    <option value="{{$level->level_id}}">{{$level->level_name}}</option>
                                    @endforeach
                                    @error('level')
                                    <p class=" text-danger">{{$message}}</p>
                                    @enderror
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-12 control-label">Department</label>
                            <div class="col-md-12">
                                <select class="form-control input-md" wire:model="department">
                                    @foreach ($departments as $department)
                                    <option value="{{$department->departments_id}}">{{$department->departments_name}}</option>
                                    @endforeach
                                    @error('department')
                                    <p class=" text-danger">{{$message}}</p>
                                    @enderror
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="" class="col-md-12 control-label"></label>
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
