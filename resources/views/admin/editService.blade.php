@extends('shared.layout')

@section('content')
    <section id="editService" class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-center">Edit Service</h2>

                    {{-- Dropdown to select a service to edit --}}
                    <div class="mb-3">
                        <label for="serviceSelect" class="form-label">Select Service</label>
                        <select id="serviceSelect" class="form-select">
                            <option value="">Select a service</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->serviceName }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Form to edit the selected service details --}}
                    <form id="editServiceForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="serviceName" class="form-label">Service Name</label>
                            <input type="text" id="serviceName" name="serviceName" class="form-control" required>
                            @error('serviceName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cost" class="form-label">Cost</label>
                            <input type="number" step="0.01" id="cost" name="cost" class="form-control"
                                required>
                            @error('cost')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (hours)</label>
                            <input type="number" step="0.1" id="duration" name="duration" class="form-control"
                                required>
                            @error('duration')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn custom-btn">Update Service</button>
                            <button type="button" id="deleteServiceButton" class="btn btn-danger">Delete Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Elements required for updating and deleting a service
            const serviceSelect = document.getElementById('serviceSelect');
            const editServiceForm = document.getElementById('editServiceForm');
            const serviceNameInput = document.getElementById('serviceName');
            const descriptionTextarea = document.getElementById('description');
            const costInput = document.getElementById('cost');
            const durationInput = document.getElementById('duration');
            const deleteServiceButton = document.getElementById('deleteServiceButton');

            // When a service is selected, fetch and display its details
            serviceSelect.addEventListener('change', function() {
                const serviceId = this.value;

                if (serviceId) {
                    fetch(`/admin/getService/${serviceId}`)
                        .then(response => response.json())
                        .then(service => {
                            // Populate form fields with the service details
                            serviceNameInput.value = service.serviceName;
                            descriptionTextarea.value = service.description;
                            costInput.value = service.cost;
                            durationInput.value = service.duration;
                            editServiceForm.action =
                                `/admin/update-service/${service.id}`; // Set form action URL for update
                            deleteServiceButton.dataset.serviceId = service
                                .id; // Set data attribute for delete button
                        })
                        .catch(error => console.error('Error fetching service details:', error));
                } else {
                    // Clear form fields if no service is selected
                    serviceNameInput.value = '';
                    descriptionTextarea.value = '';
                    costInput.value = '';
                    durationInput.value = '';
                    editServiceForm.action = '';
                    deleteServiceButton.dataset.serviceId = ''; // Clear data attribute for delete button
                }
            });
            
            // Handle delete button click to delete the selected service
            deleteServiceButton.addEventListener('click', function() {
                const serviceId = this.dataset.serviceId;

                if (serviceId && confirm('Are you sure you want to delete this service?')) {
                    fetch(`/admin/delete-service/${serviceId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert('Service deleted successfully.');
                                location.reload(); // Reload the page to reflect the changes
                            } else {
                                alert('Failed to delete service.');
                            }
                        })
                        .catch(error => console.error('Error deleting service:', error));
                }
            });
        });
    </script>
@endsection
