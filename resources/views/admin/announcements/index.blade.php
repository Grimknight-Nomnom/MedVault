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
        
        {{-- Visible to Admin & Staff (Dynamic Route) --}}
        <a href="{{ route(auth()->user()->role . '.announcements.create') }}" class="btn btn-primary rounded-pill shadow-sm fw-bold px-4">
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
                                    {{-- Edit is dynamic --}}
                                    <a href="{{ route(auth()->user()->role . '.announcements.edit', $item->id) }}" class="btn btn-sm btn-outline-primary {{ auth()->user()->role === 'staff' ? 'rounded px-3' : 'rounded-start px-3' }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- Delete is admin only --}}
                                    @if(auth()->user()->role === 'admin')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger rounded-end px-3" 
                                            title="Delete"
                                            onclick="openDeleteModal('{{ route('admin.announcements.delete', $item->id) }}', 'Are you sure you want to delete this announcement? It will be removed from the homepage.')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
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
{{-- MANAGE STAFF SECTION --}}
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
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Status</th>
                        <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffMembers as $staff)
                    <tr class="{{ !$staff->is_active ? 'opacity-50' : '' }}">
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
                            @if(!$staff->is_active)
                                <small class="text-danger"><i class="fas fa-ban me-1"></i>Inactive</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-3">{{ $staff->role }}</span>
                        </td>
                        <td class="text-center">
                            @if($staff->is_active)
                                <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3">
                                    <i class="fas fa-times-circle me-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                {{-- Edit button: Only enable if active --}}
                                @if($staff->is_active)
                                    <button class="btn btn-sm btn-outline-primary {{ auth()->user()->role === 'staff' ? 'rounded px-3' : 'rounded-start px-3' }}" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $staff->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary {{ auth()->user()->role === 'staff' ? 'rounded px-3' : 'rounded-start px-3' }}" disabled title="Cannot edit inactive staff">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif

                                @if(auth()->user()->role === 'admin')
                                    @if($staff->is_active)
                                        {{-- Deactivate button --}}
                                        <button class="btn btn-sm btn-outline-warning rounded-end px-3" data-bs-toggle="modal" data-bs-target="#deactivateStaffModal{{ $staff->id }}" title="Deactivate">
                                            <i class="fas fa-pause-circle"></i>
                                        </button>
                                    @else
                                        {{-- Reactivate button --}}
                                        <button class="btn btn-sm btn-outline-success rounded-end px-3" data-bs-toggle="modal" data-bs-target="#reactivateConfirmModal{{ $staff->id }}" title="Reactivate">
                                            <i class="fas fa-play-circle"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- EDIT MODAL FOR THIS STAFF (Only shown if active) --}}
                    @if($staff->is_active)
                    <div class="modal fade" id="editStaffModal{{ $staff->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light py-3">
                                    <h6 class="modal-title fw-bold">Edit Staff Member</h6>
                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route(auth()->user()->role . '.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4 text-start">
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
                    @endif

                    {{-- DEACTIVATE MODAL FOR THIS STAFF (ADMIN ONLY) --}}
                    @if(auth()->user()->role === 'admin' && $staff->is_active)
                    <div class="modal fade" id="deactivateStaffModal{{ $staff->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-warning text-dark py-3">
                                    <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-circle me-2"></i>Deactivate Staff</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.staff.deactivate', $staff->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="alert alert-warning border-0 mb-3" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            You are about to deactivate <strong>{{ $staff->name }}</strong>. Please provide a reason.
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Reason for Deactivation <span class="text-danger">*</span></label>
                                            <textarea name="inactive_reason" class="form-control" rows="4" placeholder="e.g., On leave, Transferred, Resigned, etc." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-3 bg-light">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning px-4 fw-bold">Deactivate</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- REACTIVATE CONFIRMATION MODAL --}}
                    @if(auth()->user()->role === 'admin' && !$staff->is_active)
                    <div class="modal fade" id="reactivateConfirmModal{{ $staff->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-info text-white py-3">
                                    <h6 class="modal-title fw-bold"><i class="fas fa-info-circle me-2"></i>View Deactivation Reason</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Staff Name</label>
                                        <p class="form-control-plaintext fw-bold">{{ $staff->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Deactivation Reason</label>
                                        <div class="alert alert-light border-2 border-warning">
                                            {{ $staff->inactive_reason }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Deactivated On</label>
                                        <p class="form-control-plaintext">{{ $staff->deactivated_at ? $staff->deactivated_at->format('M d, Y \a\t H:i') : 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="modal-footer p-3 bg-light">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                                    <form action="{{ route('admin.staff.reactivate', $staff->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success px-4 fw-bold">
                                            <i class="fas fa-check-circle me-1"></i>Reactivate Staff
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
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
            <form action="{{ route(auth()->user()->role . '.staff.store') }}" method="POST" enctype="multipart/form-data">
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

{{-- INCLUDE GLOBAL DELETE MODAL COMPONENT (Admin Only) --}}
@if(auth()->user()->role === 'admin')
    @include('components.delete-modal')
@endif

@endsection