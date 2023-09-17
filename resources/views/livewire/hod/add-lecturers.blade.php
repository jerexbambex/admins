<div class="container">
    <div class="wrap-breadcrumb">
        @include('layouts.messages')
        <ul>
            <a href="{{ route('hod.lecturers') }}" style='color: white; float: right;' class="btn btn-primary">All
                Lecturers</a>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 col-md-offset-3">
            <div class=" main-content-area">
                <div class="wrap-login-item ">
                    <div class="register-form form-item ">
                        @if (Session::has('message'))
                            <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                        @endif
                        <form class="form-stl" wire:submit.prevent="submit">
                            <fieldset class="wrap-title">
                                @if ($action == 'add')
                                    <h3 class="form-title">Create Lecturer's Account</h3>
                                @else
                                    <h3 class="form-title">Update Lecturer's Account</h3>
                                @endif
                            </fieldset>
                            <div class="row">
                                <div class="col-md">
                                    <fieldset class="wrap-title">
                                        <h4 class="form-subtitle">Personal infomation</h4>
                                    </fieldset>
                                    <fieldset class="wrap-input form-group">
                                        <x-jet-input class="block mt-1 w-full" type="text"
                                            placeholder="Mr / Mrs / Miss / Dr / Prof" wire:model.lazy="title" />
                                        @error('title')
                                            <small><small class="text-danger">{{ $message }}</small></small>
                                        @enderror
                                    </fieldset>
                                    <fieldset class="wrap-input form-group">
                                        <x-jet-input class="block mt-1 w-full" type="text" placeholder="Full name"
                                            wire:model.lazy="name" />
                                        @error('name')
                                            <small><small class="text-danger">{{ $message }}</small></small>
                                        @enderror
                                    </fieldset>
                                    <fieldset class="wrap-input form-group">
                                        <x-jet-input class="block mt-1 w-full" type="text"
                                            placeholder="Mobile Number" wire:model.lazy="mobile" />
                                        @error('mobile')
                                            <small><small class="text-danger">{{ $message }}</small></small>
                                        @enderror
                                    </fieldset>
                                    <fieldset class="wrap-input form-group">
                                        <x-jet-input class="block mt-1 w-full" type="text" placeholder="Staff ID"
                                            wire:model.lazy="staff_id" />
                                        @error('staff_id')
                                            <small><small class="text-danger">{{ $message }}</small></small>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md">
                                    <fieldset class="wrap-title">
                                        <h3 class="form-subtitle">Login Information</h3>
                                    </fieldset>
                                    @if ($action == 'add')
                                        <fieldset class="wrap-input form-group item-width-in-half left-item ">
                                            <x-jet-input class="block mt-1 w-full" type="text" placeholder="Username"
                                                wire:model.lazy="username" />
                                            @error('username')
                                                <small><small class="text-danger">{{ $message }}</small></small>
                                            @enderror
                                        </fieldset>
                                    @endif
                                    <fieldset class="wrap-input form-group item-width-in-half left-item ">
                                        <x-jet-input class="block mt-1 w-full" type="text" placeholder="Work Email"
                                            wire:model.lazy="email" />
                                        @error('email')
                                            <small><small class="text-danger">{{ $message }}</small></small>
                                        @enderror
                                    </fieldset>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary float-end float-right"
                                value="{{ $action == 'add' ? 'Submit' : 'Update' }}" name="register">
                            <br>
                            <div style="padding-top: 10px;">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!--end main products area-->
        </div>
    </div>
    <!--end row-->
</div>
