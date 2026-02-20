@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.selected { background: #0d6efd !important; border-color: #0d6efd !important; }
</style>

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
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Announcement</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold small text-uppercase">Headline Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold small text-uppercase">Content</label>
                            <textarea name="description" id="description" rows="5" class="form-control" required>{{ old('description', $announcement->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label fw-bold small text-uppercase text-danger">Auto-Hide Date</label>
                            <input type="text" 
                                   name="expires_at" 
                                   id="expires_at" 
                                   class="form-control bg-white cursor-pointer" 
                                   placeholder="Select Expiration Date..."
                                   value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d') : '') }}">
                            <div class="form-text">Optional: The announcement will automatically stop showing after this date.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Image</label>
                            <div class="d-flex gap-3 align-items-start">
                                @if($announcement->image_path)
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $announcement->image_path) }}" class="rounded border shadow-sm" style="width: 120px; height: 80px; object-fit: cover;">
                                        <div class="small text-muted text-center mt-1">Current</div>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                    <div class="form-text">Upload to replace the current image. Leave empty to keep existing.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 p-3 bg-light rounded border d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $announcement->is_active ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                <label class="form-check-label ms-3 fw-bold pt-1" for="is_active">Visible on Homepage</label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                <i class="fas fa-check-circle me-2"></i> Update Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#expires_at", {
        dateFormat: "Y-m-d",
        minDate: new Date().fp_incr(1), // Tomorrow
        allowInput: true
    });
</script>
@endsection