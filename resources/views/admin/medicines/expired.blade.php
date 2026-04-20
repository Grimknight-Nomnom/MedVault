@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Page Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-danger mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Expired Medicines</h2>
        <p class="text-muted small mb-0">Track and manage actions taken on expired medicines.</p>
    </div>
    <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i>Back to Inventory
    </a>
</div>

    {{-- Expired Medicines Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-danger text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-pills me-2"></i>Pending Action Items</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small">
                        <tr>
                            <th class="py-3 ps-4">Medicine Name</th>
                            <th class="py-3">Quantity</th>
                            <th class="py-3">Expired Date</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiredRecords as $record)
                        <tr class="{{ $record->action_taken ? 'opacity-50' : '' }}">
                            <td class="ps-4 fw-bold text-dark">{{ $record->medicine_name }}</td>
                            <td>
                                <span class="badge bg-danger rounded-pill px-3">
                                    {{ abs($record->quantity_changed) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $record->formatted_date }}</small>
                            </td>
                            <td>
                                @if($record->action_taken)
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">
                                        <i class="fas fa-check-circle me-1"></i>{{ ucfirst($record->action_taken) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-3">
                                        <i class="fas fa-hourglass-half me-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if(!$record->action_taken)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#actionModal{{ $record->id }}" 
                                            title="Record Action">
                                        <i class="fas fa-check-square me-1"></i>Record Action
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewActionModal{{ $record->id }}" 
                                            title="View Details">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- RECORD ACTION MODAL --}}
                        @if(!$record->action_taken)
                        <div class="modal fade" id="actionModal{{ $record->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-danger text-white py-3">
                                        <h6 class="modal-title fw-bold"><i class="fas fa-check-square me-2"></i>Record Action Taken</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.medicine-history.record-action', $record->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="alert alert-light border-2 border-warning mb-3">
                                                <strong>Medicine:</strong> {{ $record->medicine_name }}<br>
                                                <strong>Quantity:</strong> {{ abs($record->quantity_changed) }}<br>
                                                <strong>Expired:</strong> {{ $record->formatted_date }}
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase">Action Taken <span class="text-danger">*</span></label>
                                                <select name="action_taken" class="form-select" required>
                                                    <option value="" disabled selected>Select an action...</option>
                                                    <option value="disposed">Disposed (Destroyed/Incinerated)</option>
                                                    <option value="returned">Returned (To Supplier)</option>
                                                    <option value="donated">Donated (To Organization)</option>
                                                    <option value="destroyed">Destroyed (As per Protocol)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase">Notes (Optional)</label>
                                                <textarea name="action_notes" class="form-control" rows="3" placeholder="e.g., Returned to XYZ Supplier on April 20, 2026..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-3 bg-light">
                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger px-4 fw-bold">Record Action</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- VIEW ACTION MODAL --}}
                        @if($record->action_taken)
                        <div class="modal fade" id="viewActionModal{{ $record->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-success text-white py-3">
                                        <h6 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i>Action Recorded</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Medicine</label>
                                            <p class="form-control-plaintext fw-bold">{{ $record->medicine_name }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Quantity</label>
                                            <p class="form-control-plaintext">{{ abs($record->quantity_changed) }} units</p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Expired Date</label>
                                            <p class="form-control-plaintext">{{ $record->formatted_date }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Action Taken</label>
                                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>{{ ucfirst($record->action_taken) }}
                                            </span>
                                        </div>

                                        @if($record->action_notes)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Notes</label>
                                            <div class="alert alert-info border-0 bg-info-subtle">
                                                {{ $record->action_notes }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer p-3 bg-light">
                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted opacity-50">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                    <p>All expired medicines have been accounted for!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection