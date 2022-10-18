@extends('layout.app')

@section('title')
    Add Hardware
@endsection

@section('header_scripts')
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Add Hardware</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('hardwares.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>IP:</label>
                            <input type="text" class="form-control" name="ip" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>MAC Address:</label>
                            <input type="text" class="form-control" name="mac_address" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>App Path:</label>
                            <input type="text" class="form-control" name="app_path" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status:</label>
                            <select name="is_active" id="is_active" class="form-control" required>
                                <option value="">Select Option</option>D
                                <option value="0">Inactive</option>D
                                <option value="1">Active</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Add <i class="icon-add ml-2"></i></button>
                </div>
            </form>
        </div>
    </div>
@endsection
