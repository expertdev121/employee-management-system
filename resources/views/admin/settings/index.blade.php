@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-cog text-success"></i> System Settings</h2>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">General Settings</h5>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" value="EMS">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Default Working Hours</label>
                                <input type="number" class="form-control" value="8">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Timezone</label>
                                <select class="form-select">
                                    <option>UTC</option>
                                    <option>America/New_York</option>
                                    <option>Europe/London</option>
                                    <option>Asia/Kolkata</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Notifications</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">
                                        Enable shift reminder notifications
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">
                                        Enable attendance alerts
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-database"></i> Backup Database
                            </button>
                            <button class="btn btn-outline-warning">
                                <i class="fas fa-file-export"></i> Export Data
                            </button>
                            <button class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i> Clear Logs
                            </button>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">System Info</h5>
                        <p class="mb-1"><strong>Version:</strong> 1.0.0</p>
                        <p class="mb-1"><strong>PHP Version:</strong> {{ PHP_VERSION }}</p>
                        <p class="mb-1"><strong>Laravel Version:</strong> {{ app()->version() }}</p>
                        <p class="mb-0"><strong>Last Backup:</strong> {{ now()->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
