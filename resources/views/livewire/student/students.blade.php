<div class="card">
    <div class="card-title">
        All Students
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
                        <th class="th-lg">Matric No.</th>
                        <th class="th-lg">Form No.</th>
                        <th class="th-lg">Surname</th>
                        <th class="th-lg">Firstname</th>
                        <th class="th-lg">Othernames</th>
                        <th class="th-lg">Department</th>
                        <th class="th-lg">Action</th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>


                    @forelse ($students as $student)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{$student->matric_no}}</td>
                        <td>{{$student->matset}}</td>
                        <td>{{$student->surname}}</td>
                        <td>{{$student->firstname}}</td>
                        <td>{{$student->othernames}}</td>
                        <td>{{$student->department->departments_name}}</td>
                        <td><button class="btn btn-info">Edit</button></td>
                    </tr>
                    @empty
                    <div>No result found</div>
                    @endforelse
            </table>
            @if($students->all())
            {{$students->links()}}
            @endif
            <!--Table-->

        </div>
    </div>
</div>
