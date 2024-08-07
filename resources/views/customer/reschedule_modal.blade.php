<div class="modal fade" id="rescheduleBookingModal" tabindex="-1" aria-labelledby="rescheduleBookingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="reschedule-booking-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h5 class="modal-title text-center" id="rescheduleBookingModalLabel">Reschedule Booking</h5>
                    <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reschedule-date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="reschedule-date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="reschedule-time" class="form-label">Time</label>
                        <select class="form-select" id="reschedule-time" name="time" required>
                            @foreach ($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setMinDate(inputId) {
            var dateInput = document.getElementById(inputId);

            // Set the min attribute of the date input field to tomorrow
            var today = new Date();
            var tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            var yyyy = tomorrow.getFullYear();
            var mm = String(tomorrow.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            var dd = String(tomorrow.getDate()).padStart(2, '0');
            dateInput.setAttribute('min', `${yyyy}-${mm}-${dd}`);
        }

        function validateDateInput(inputId) {
            var dateInput = document.getElementById(inputId);
            dateInput.addEventListener('input', function() {
                var selectedDate = new Date(dateInput.value);
                var today = new Date();
                today.setHours(0, 0, 0, 0); // Clear time part for comparison
                if (selectedDate <= today) {
                    alert('Please select a date at least one day ahead.');
                    dateInput.value = '';
                }
            });
        }

        // Apply the date restrictions for the reschedule form
        setMinDate('reschedule-date');
        validateDateInput('reschedule-date');
    });
</script>
