@extends('layouts/layoutMaster')

@section('title', 'Edit Activity log')

@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Edit Activity Log</span>
                        </div>
                        <form action="{{ route('activityLogs.update', ['id' => $log->id]) }}" method="post" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" value="{{ $log->name }}"
                                    autofocus required>
                                @error('name')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type *</label>
                                <select name="type" class="form-control" required>
                                    <option value="C" @if ($log->type == 'C') selected @endif>Coding</option>
                                    <option value="M" @if ($log->type == 'M') selected @endif>Meeting
                                    </option>
                                    <option value="P" @if ($log->type == 'P') selected @endif>Playing
                                    </option>
                                    <option value="V" @if ($log->type == 'V') selected @endif>Watching Video
                                    </option>
                                </select>
                                @error('type')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Log</label>
                                <textarea class="form-control" rows="3" name="log">{{ $log->log }}</textarea>
                                @error('log')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Update</button>
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
