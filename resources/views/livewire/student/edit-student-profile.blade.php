<div>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                Update Student
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('students')}}" class="btn btn-success pull-right">All Students</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                        <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <form class="form-horizontal" wire:submit.prevent="updateStudent">
                            <div class="form-group">
                                <label for="" class="col-md-12 control-label">Matriculation Number/label>
                                    <div class="col-md-12">
                                        <input type="text" placeholder="Matriculation Number" class="form-control input-md" wire:model="matric_no">
                                        @error('matric_no')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" class="col-md-4 control-label">Firstname</label>
                                    <div class="col-md-4">
                                        <input type="text" placeholder="Firstname" class="form-control input-md" wire:model="firstname">
                                        @error('slug')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-md-4 control-label">Surname</label>
                                    <div class="col-md-4">
                                        <input type="text" placeholder="Surname" class="form-control input-md" wire:model="surname">
                                        @error('slug')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-md-4 control-label">Othernames</label>
                                    <div class="col-md-4">
                                        <input type="text" placeholder="Othernames" class="form-control input-md" wire:model="othernames">
                                        @error('slug')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
