@extends('layouts/layoutMaster')

@section('title', 'Role Edit')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection


@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Edit Role</span>
                        </div>
                        <form action="{{ route('roles.update', ['id' => $role->id]) }}" method="post"
                            id="formAuthentication" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Role Name *</label>
                                <input type="text" class="form-control" name="role_name" value="{{ $role->role_name }}"
                                    autofocus required>
                                @error('role_name')
                                    <div class="text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $role->description }}</textarea>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="select2Primary" class="form-label">Primary</label>
                                <div class="select2-primary">
                                    <select id="select2Primary" class="select2 form-select" name="permissions[]" multiple>
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}" value="{{ $permission->id }}"
                                                {{ $role->permission->contains($permission->id) ? 'selected' : '' }}>
                                                {{ $permission->permission_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Update</button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
