<div>
    <div class="row">
        @include('layouts.messages')
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <label>{{ ucfirst($action) }} Faculty Dean</label>
                        @if ($action == 'update')
                            <button class="btn btn-sm btn-secondary float-end float-right" type="button"
                                wire:click="enableCreate" wire:loading.attr="disabled">
                                Create Faculty Dean
                            </button>
                        @endif
                    </div>

                    <form wire:submit.prevent="submit">
                        <div class="form-group">
                            <label>Initial</label>
                            <input type="text" wire:model.lazy="title" placeholder="Mr / Mrs / Miss / Dr / Prof"
                                class="form-control" />
                            @error('title')
                                <small class="text-danger text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" wire:model.lazy="name" class="form-control" />
                            @error('name')
                                <small class="text-danger text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Staff ID</label>
                            <input type="text" wire:model.lazy="staff_id" class="form-control" />
                            @error('staff_id')
                                <small class="text-danger text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                        @if ($action == 'create')
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" wire:model.lazy="username" class="form-control" />
                                @error('username')
                                    <small class="text-danger text-sm">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" wire:model.lazy="email" class="form-control" />
                            @error('email')
                                <small class="text-danger text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="tel" wire:model.lazy="mobile" class="form-control" />
                            @error('mobile')
                                <small class="text-danger text-sm">{{ $message }}</small>
                            @enderror
                        </div>
                        @if ($action == 'create')
                            <div class="form-group">
                                <label>Faculty</label>
                                <select class="form-control" wire:model="faculty">
                                    <option value="">All</option>
                                    @foreach ($faculties as $fac)
                                        <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}</option>
                                    @endforeach
                                </select>
                                @error('faculty')
                                    <small class="text-danger text-sm">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group">
                            <button class="btn btn-sm btn-primary btn-block" type="submit">
                                {{ ucfirst($action) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Faculty Dean's Filter</div>

                    <div class="form-body">
                        <div class="form-group col-md">
                            <label>Status</label>
                            <select class="form-control" wire:model="filter_status">
                                <option value="0">Blocked</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Faculty Dean's List</div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Staff ID</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($deans as $user)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->staff_id }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->faculty_name }}</td>
                                        <td>
                                            @if ($filter_status)
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:click="blockUser({{ $user->id }})"
                                                    wire:loading.attr="disabled">
                                                    Block
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-success"
                                                    wire:click="enaleUser({{ $user->id }})"
                                                    wire:loading.attr="disabled">
                                                    Activate
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-info"
                                                wire:click="enableUpdate({{ $user->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="fa fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                wire:click="resetPassword({{ $user->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="fa fa-edit"></i>
                                                Reset Password
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th colspan="7" class="text-danger text-center">
                                            No record found . . . !
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $deans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
