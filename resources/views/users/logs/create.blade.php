@extends('layouts/layoutMaster')

@section('title', 'User Create')

@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Add Activity Log</span>
                        </div>
                        <form action="{{ route('activityLogs.store') }}" method="post" id="formAuthentication" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" autofocus required>
                                @error('name')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type *</label>
                                <select name="type" class="form-control" required>
                                    <option disabled selected>Choose...</option>
                                    <option value="C">Coding</option>
                                    <option value="M">Meeting</option>
                                    <option value="P">Playing</option>
                                    <option value="V">Watching Video</option>
                                </select>
                                @error('type')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleFormControlTextarea1">Log</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="log"></textarea>
                                @error('log')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Add</button>
                                    <a href="{{ route('activityLogs.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
