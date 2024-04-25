@extends('layouts/layoutMaster')

@section('title', 'Module - Edit')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Edit Module</span>
                        </div>
                        <form id="formAuthentication" class="mb-3"
                            action="{{ route('modules.update', ['code' => $module->code]) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Module Name *</label>
                                <input type="text" class="form-control" id="email" name="module_name"
                                    value="{{ $module->module_name }}" autofocus required>

                                @error('module_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $module->description }}</textarea>
                            </div>

                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Update</button>
                                    <a href="{{ route('modules.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
