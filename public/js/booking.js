document.addEventListener('DOMContentLoaded', function () {
    var serviceCheckboxes = document.querySelectorAll('.form-check-input');
    var timeSelect = document.getElementById('time');
    var dateInput = document.getElementById('date');
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

    serviceCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateAvailableTimes);
    });

    dateInput.addEventListener('input', function () {
        var selectedDate = new Date(dateInput.value);
        var day = selectedDate.getDay();
        if (day === 0 || day === 6) { // 0 = Sunday, 6 = Saturday
            alert('Please select a weekday.');
            dateInput.value = '';
        }
    });

    updateAvailableTimes();
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