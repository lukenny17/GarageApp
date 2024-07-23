<div class="modal fade" id="editServicesModal" tabindex="-1" aria-labelledby="editServicesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="edit-services-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServicesModalLabel">Edit Services</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editServicesModalBody">
                    <!-- Services checkboxes will be loaded here dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
