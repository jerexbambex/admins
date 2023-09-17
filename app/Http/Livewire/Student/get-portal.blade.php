<div class="card">
    <div class="card-title">
        <div class="d-flex flex-row justify-content-between">
            <div class="mx-2" style="text-transform: uppercase;">All Unprofiled Students</div>
            <div class="mx-2">
                <input type="search" placeholder="Search" class="form-control " style="width: 300px;" wire:model="search">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @include('layouts.messages')
            @if (Session::has('message'))
            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
            @endif
            <!--Table-->
            <table class="table">

                <!--Table head-->
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th class="th-lg">Form No.</th>
                        <th class="th-lg">Fullname</th>
                        <th class="th-lg">Faculty</th>
                        <th class="th-lg">Department</th>
                        <th class="th-lg">Action</th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>


                    @forelse ($portals as $portal)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{$portal->appno}}</td>
                        <td>{{$portal->fullname}}</td>
                        <td>{{$portal->faculty->faculties_name}}</td>
                        <td>{{$portal->department->departments_name}}</td>
                        <td><a style="color: white;" class="btn btn-info" href="{{route('dr.portal.update', ['pid'=>$portal->pid])}}">Edit</a></td>
                    </tr>
                    @empty
                    <div>No result found</div>
                    @endforelse
            </table>
            @if($portals->all())
            {{$portals->links()}}
            @endif
            <!--Table-->

        </div>
    </div>
</div>
