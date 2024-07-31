document.addEventListener('DOMContentLoaded', function () {
    function initBookingForm(dateSelector, timeSelector, serviceCheckboxSelector) {
        var serviceCheckboxes = document.querySelectorAll(serviceCheckboxSelector);
        var timeSelect = document.querySelector(timeSelector);
        var dateInput = document.querySelector(dateSelector);
        var originalOptions = Array.from(timeSelect.options); // Save original options

        function updateAvailableTimes() {
            var totalDuration = 0;
            serviceCheckboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    totalDuration += parseFloat(checkbox.getAttribute('data-duration'));
                }
            });

            var endTime = new Date();
            endTime.setHours(17, 0, 0, 0); // 5pm

            // Clear the timeSelect options
            timeSelect.innerHTML = '';

            // Filter and add available options
            originalOptions.forEach(function (option) {
                var startTime = new Date();
                var [hours, minutes] = option.value.split(':');
                startTime.setHours(hours, minutes, 0, 0);

                var endTimeWithService = new Date(startTime.getTime() + totalDuration * 60 * 60 * 1000);

                if (endTimeWithService <= endTime) {
                    timeSelect.add(option.cloneNode(true));
                }
            });
        }

        function validateDateInput() {
            var selectedDate = new Date(dateInput.value);
            var day = selectedDate.getDay();
            if (day === 0 || day === 6) { // 0 = Sunday, 6 = Saturday
                alert('Please select a weekday.');
                dateInput.value = '';
            }

            var today = new Date();
            if (selectedDate <= today) {
                alert('Please select a date at least one day ahead.');
                dateInput.value = '';
            }
        }

        serviceCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateAvailableTimes);
        });

        dateInput.addEventListener('input', validateDateInput);

        updateAvailableTimes();

        // Set the min attribute of the date input field to tomorrow
        var today = new Date();
        var tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        var yyyy = tomorrow.getFullYear();
        var mm = String(tomorrow.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        var dd = String(tomorrow.getDate()).padStart(2, '0');
        dateInput.setAttribute('min', `${yyyy}-${mm}-${dd}`);
    }

    // Initialise the booking form
    initBookingForm('#date', '#time', '.form-check-input');

});

function toggleNewVehicleFields() {
    var vehicleSelect = document.getElementById('vehicle');
    var newVehicleFields = document.getElementById('newVehicleFields');
    if (vehicleSelect.value === 'new') {
        newVehicleFields.style.display = 'block';
    } else {
        newVehicleFields.style.display = 'none';
    }

}

// For admin booking creation

function loadCustomerVehicles() {
    const customerId = document.getElementById('customer_id').value;
    const vehicleSelect = document.getElementById('vehicle');

    if (customerId) {
        fetch(`/admin/customers/${customerId}/vehicles`)
            .then(response => response.json())
            .then(data => {
                vehicleSelect.innerHTML = '<option value="">Select Vehicle</option>';
                data.vehicles.forEach(vehicle => {
                    vehicleSelect.innerHTML +=
                        `<option value="${vehicle.id}">${vehicle.make} ${vehicle.model} (${vehicle.licensePlate})</option>`;
                });
                vehicleSelect.innerHTML += '<option value="new">Add New Vehicle</option>';
            });
    } else {
        vehicleSelect.innerHTML =
            '<option value="">Select Vehicle</option><option value="new">Add New Vehicle</option>';
    }
}
