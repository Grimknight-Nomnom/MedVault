@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3">
                <a href="{{ route('admin.announcements.index') }}" class="text-decoration-none text-muted fw-bold small">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bullhorn me-2"></i>Create New Announcement</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Title --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold small text-uppercase">Headline Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg" placeholder="e.g. Clinic Closed for Holidays" required>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold small text-uppercase">Content / Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control" placeholder="Enter the full details here..." required></textarea>
                        </div>

                        {{-- Image Upload --}}
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold small text-uppercase">Cover Image (Optional)</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <div class="form-text">Recommended size: 800x400px. Max size: 2MB.</div>
                        </div>

                        {{-- Visibility Toggle --}}
                        <div class="mb-4 p-3 bg-light rounded border d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked style="width: 3em; height: 1.5em;">
                                <label class="form-check-label ms-3 fw-bold pt-1" for="is_active">Publish Immediately?</label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                <i class="fas fa-save me-2"></i> Post Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection