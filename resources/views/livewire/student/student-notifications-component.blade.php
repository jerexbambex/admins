<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        {{$is_edit ? 'Update Notification' : 'Create Notification'}}

                        @if($is_edit)
                        <button class="btn btn-secondary btn-sm" type="button" wire:click="addNew">Add New</button>
                        @endif
                    </div>
                    @include('layouts.messages')

                    <div class="form-group">
                        <form wire:submit.prevent="submit">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" id="title" wire:model="title" class="form-control" wire:keyup="generateSlug" />
                                @error('title') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" readonly id="slug" value="{{$slug}}" class="form-control" />
                                @error('slug') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            {{$content}}
                            <div class="form-group" wire:ignore wire:key="summernote">
                                <label for="content">Content</label>
                                <textarea id="content" class="form-control">{{$content}}</textarea>
                            </div>
                            @error('content') <small class="text-danger">{{$message}}</small> @enderror
                            <div class="form-group">
                                @if(!$in_operation)
                                <input type="submit" class="btn btn-block btn-primary" value="{{$is_edit ? 'Update' : 'Create'}}"/>
                                @else
                                <input type="button" disabled class="btn btn-block btn-secondary" value="Please wait . . ."/>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Notifications List
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <th>#</th>
                                <th>title</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @php
                                    $page = $notifications->currentPage();
                                    $paginate = $notifications->perPage();
                                    $count = (($page * $paginate) - $paginate) + 1
                                @endphp
                                @forelse ($notifications as $notification)
                                    <tr>
                                        <th>{{$count++}}</th>
                                        <td>{{$notification->title}}</td>
                                        <td>{{modifiedDate($notification->created_at)}}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" wire:click="editNotification({{$notification->id}})">View/Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger" wire:click="deleteNotification({{$notification->id}})">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-danger text-center">
                                            No record found !
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            
            $('textarea').summernote()

            Livewire.on('setContent', content => {
                $('textarea').summernote('destroy')
                $('textarea').summernote('code', content)
            })

            $('textarea').on('summernote.change', function(we, contents, $editable) {
                @this.set('content', contents)
            });
        })

    </script>
</div>
