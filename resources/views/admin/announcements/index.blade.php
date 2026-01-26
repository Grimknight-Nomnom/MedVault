@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">Manage Announcements</h2>
            <p class="text-muted mb-0">Control the news and updates visible on the homepage.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary rounded-pill shadow-sm fw-bold px-4">
            <i class="fas fa-plus-circle me-2"></i> Create New
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted" style="width: 100px;">Image</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Title & Preview</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted text-center">Status</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Last Updated</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $item)
                        <tr>
                            <td class="ps-4">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" 
                                         alt="Thumbnail" 
                                         class="rounded shadow-sm object-fit-cover" 
                                         style="width: 60px; height: 60px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <h6 class="fw-bold text-dark mb-1">{{ $item->title }}</h6>
                                <p class="text-muted small mb-0 text-truncate" style="max-width: 300px;">
                                    {{ Str::limit($item->description, 60) }}
                                </p>
                            </td>
                            <td class="text-center">
                                @if($item->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3">
                                        Hidden
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                <i class="far fa-clock me-1"></i> {{ $item->updated_at->format('M d, Y') }}
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.announcements.edit', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-start px-3" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- TRIGGER DELETE MODAL --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger rounded-end px-3" 
                                            title="Delete"
                                            onclick="openDeleteModal('{{ route('admin.announcements.delete', $item->id) }}', 'Are you sure you want to delete this announcement? It will be removed from the homepage.')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="mb-2"><i class="fas fa-clipboard-list fa-3x opacity-25"></i></div>
                                <p class="mb-0">No announcements found. Start by creating one!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($announcements->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>

{{-- INCLUDE GLOBAL DELETE MODAL COMPONENT --}}
@include('components.delete-modal')

@endsection