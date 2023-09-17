<div class="card">
    <div class="card-body">
        <div class="card-title">
            All Lecturers
            <a href="{{route('hod.lecturer', ['action' => 'add'])}}" class="btn btn-primary btn-sm float-end">Add Lecturer</a>
        </div>
        <div class="table-responsive">
            @include('layouts.messages')
            @if (Session::has('message'))
                <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
            @endif
            <!--Table-->
            <table class="table">

                <!--Table head-->
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th class="th-lg">Name</th>
                        <th class="th-lg">Email</th>
                        <th class="th-lg">Default Password</th>
                        <th class="th-lg">Status</th>
                        <th class="th-lg">Action</th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>

                    @foreach ($lecturers as $lecturer)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $lecturer->full_name }}</td>
                            <td>{{ $lecturer->email }}</td>
                            <td>{{ $lecturer->username }}</td>
                            <td>
                                @if ($lecturer->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Blocked</span>
                                @endif
                            </td>
                            <td>
                                @if ($lecturer->status)
                                    <a onclick="confirm('Are you sure you want to disable lecturer?') || event.stopImmediatePropagation()"
                                        wire:click.prevent="disableUser({{ $lecturer->id }})"
                                        class="btn btn-danger btn-sm">Block</a>
                                @else
                                    <a onclick="confirm('Are you sure you want to enable lecturer?') || event.stopImmediatePropagation()"
                                        wire:click.prevent="enableUser({{ $lecturer->id }})"
                                        class="btn btn-success btn-sm">Activate</a>
                                @endif
                                <a onclick="confirm('Are you sure you want to reset password for this lecturer?') || event.stopImmediatePropagation()"
                                    wire:click.prevent="resetPassword({{ $lecturer->id }})"
                                    class="btn btn-secondary btn-sm">Reset Password</a>
                                <a href="{{route('hod.lecturer', ['action' => 'update', 'param' => base64_encode($lecturer->email)])}}" class="btn btn-primary btn-sm">Update Info</a>
                            </td>
                        </tr>
                    @endforeach

                    <!--Table body-->

            </table>
            @if ($lecturers->all())
                {{ $lecturers->links() }}
            @endif
            <!--Table-->

        </div>
    </div>
</div>
