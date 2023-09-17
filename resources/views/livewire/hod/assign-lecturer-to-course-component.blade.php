<div class="content">
    <div class="row">
        @include('layouts.messages')
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Assign Course to Lecturer ({{ $sch_session }})
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="submit">
                        <div class="form-body">
                            <div class="form-group">
                                <label>Course</label>
                                <input type="text" readonly disabled required
                                    value="{{ "$to_assign_course->thecourse_title ($to_assign_course->thecourse_code)" }}"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Lecturer</label>
                                <select required class="form-control" wire:model="lecturer_id">
                                    <option value="">Select Lecturer</option>
                                    @foreach ($lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>For Programme Type</label>
                                <select required class="form-control" wire:model="programme_type_id">
                                    <option value="">Select Programme Type</option>
                                    @foreach ($programmeTypes as $type)
                                        <option value="{{ $type['programmet_id'] }}">{{ $type['programmet_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-sm btn-block">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                {{-- <div class="search-bar">
                    <input type="search" class="float-end" wire:model="search"/>
                </div> --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Lecturer</th>
                                    <th>For Programme</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if ($coursesAssigned):
                                        $page = $coursesAssigned->currentPage();
                                        $paginate = PAGINATE_SIZE / 2;
                                        $count = $page * $paginate - $paginate + 1;
                                    endif;
                                @endphp
                                @forelse ($coursesAssigned as $assigned)
                                    @php
                                        $lecturer = $assigned->lecturer;
                                    @endphp
                                    <tr>
                                        <th>{{ $count++ }}</th>
                                        <td>{{ $assigned->course->thecourse_title . ' - ' . $assigned->course->thecourse_code }}
                                        </td>
                                        <td>{{ $lecturer ? $lecturer->name : '' }}</td>
                                        <td>{{ $assigned->programmeType->programmet_name }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="deleteAssigned({{ $assigned->id }})"
                                                onclick="(confirm('Are you sure you want to delete!?') && confirm('Please confirm delete again!')) || event.stopImmediatePropagation()"><i
                                                    class="fa fa-trash-alt"></i> Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <p class="text-danger text-center">
                                                No result found !
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{ $coursesAssigned->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>