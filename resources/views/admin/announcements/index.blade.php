@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- ========================================================= --}}
    {{-- MANAGE ANNOUNCEMENTS SECTION --}}
    {{-- ========================================================= --}}
    <div class="mb-4">
        <div class="mb-3">
            <h2 class="fw-bold text-primary">Manage Announcements</h2>
            <p class="text-muted mb-0">Control the news and updates visible on the homepage.</p>
        </div>
        
        {{-- Button moved below the title --}}
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary rounded-pill shadow-sm fw-bold px-4">
            <i class="fas fa-plus-circle me-2"></i> Create New
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
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

    {{-- ========================================================= --}}
    {{-- MANAGE STAFF SECTION (NEW) --}}
    {{-- ========================================================= --}}
    <hr class="my-5 border-secondary opacity-25">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fas fa-user-md me-2 text-primary"></i>Manage Staff</h3>
            <p class="text-muted small mb-0">Add, edit, or remove clinic staff displayed on the homepage.</p>
        </div>
        <button class="btn btn-primary rounded-pill shadow-sm fw-bold px-4" data-bs-toggle="modal" data-bs-target="#addStaffModal">
            <i class="fas fa-plus-circle me-2"></i> Add New Staff
        </button>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted" style="width: 100px;">Picture</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Name</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted text-center">Role</th>
                            <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffMembers as $staff)
                        <tr>
                            <td class="ps-4">
                                @if($staff->picture_path)
                                    <img src="{{ asset('storage/' . $staff->picture_path) }}" alt="{{ $staff->name }}" class="rounded-circle object-fit-cover border shadow-sm" style="width: 50px; height: 50px;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border shadow-sm text-secondary" style="width: 50px; height: 50px; font-size: 20px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <h6 class="fw-bold text-dark mb-0">{{ $staff->name }}</h6>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-3">{{ $staff->role }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary rounded-start px-3" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $staff->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-end px-3" data-bs-toggle="modal" data-bs-target="#deleteStaffModal{{ $staff->id }}" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- EDIT MODAL FOR THIS STAFF --}}
                        <div class="modal fade" id="editStaffModal{{ $staff->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light py-3">
                                        <h6 class="modal-title fw-bold">Edit Staff Member</h6>
                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase">Full Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase">Role</label>
                                                <input type="text" name="role" class="form-control" value="{{ $staff->role }}" placeholder="e.g. Doctor, Nurse, Midwife" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase">Update Picture</label>
                                                <input type="file" name="picture" class="form-control" accept="image/*">
                                                <div class="form-text text-muted small">Leave blank to keep current picture. (Max: 5MB)</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-3 bg-light">
                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary px-4 fw-bold">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- DELETE MODAL FOR THIS STAFF --}}
                        <div class="modal fade" id="deleteStaffModal{{ $staff->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-body text-center p-4">
                                        <div class="text-danger mb-3"><i class="fas fa-exclamation-triangle fa-3x"></i></div>
                                        <h5 class="fw-bold">Remove Staff?</h5>
                                        <p class="text-muted small">Are you sure you want to remove <b>{{ $staff->name }}</b>? This action cannot be undone.</p>
                                        <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex gap-2 justify-content-center mt-4">
                                                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger px-4 fw-bold">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="mb-2"><i class="fas fa-users-slash fa-3x opacity-25"></i></div>
                                <p class="mb-0">No staff members added yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ADD STAFF MODAL --}}
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold"><i class="fas fa-plus me-2"></i>Add New Staff</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Jane Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Role / Title</label>
                        <input type="text" name="role" class="form-control" placeholder="e.g. Head Doctor" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Profile Picture</label>
                        <input type="file" name="picture" class="form-control" accept="image/*">
                        <div class="form-text text-muted small">Upload an image (Max: 5MB).</div>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Add Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- INCLUDE GLOBAL DELETE MODAL COMPONENT (For Announcements) --}}
@include('components.delete-modal')

@endsection