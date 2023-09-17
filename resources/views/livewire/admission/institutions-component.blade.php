<div class="row">
    {{-- Add Insitution --}}
    <div class="col-md-5">
        @include('layouts.messages')
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Add a New Institution
                </div>

                <div class="form-body">
                    <form wire:submit.prevent="submit">
                        <div class="form-group">
                            <label for="institution_name">Insitution Name</label>
                            <input type="text" wire:model.lazy="institution_name" class="form-control" />
                            @error('institution_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Add Institution</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- View Insitutions --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex justify-between">
                    View Institutions
                    <input type="search" wire:model.lazy="search" placeholder="Search . . ." />
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Insitution Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                if ($institutions) {
                                    $page = $institutions->currentPage();
                                    $paginate = $institutions->perPage();
                                    $count = $page * $paginate - $paginate + 1;
                                }
                            @endphp
                            @forelse($institutions as $institution)
                                <tr>
                                    <th>{{ $count++ }}</th>
                                    <td>{{ $institution->pname }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-danger text-center">
                                        No record found . . . !
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($institutions)
                        {{ $institutions->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
