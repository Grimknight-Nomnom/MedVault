{{-- RESOURCES/VIEWS/COMPONENTS/DELETE-MODAL.BLADE.PHP --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3 text-danger">
                    <i class="fas fa-trash-alt fa-3x opacity-50"></i>
                </div>
                <h6 class="fw-bold mb-2">Are you sure?</h6>
                <p id="deleteModalMessage" class="text-muted mb-0">
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer bg-light justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                
                <form id="deleteModalForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl, message) {
        // 1. Set the URL for the form action
        document.getElementById('deleteModalForm').action = actionUrl;
        
        // 2. Set the custom message
        if (message) {
            document.getElementById('deleteModalMessage').innerText = message;
        }

        // 3. Open the Bootstrap Modal
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }
</script>