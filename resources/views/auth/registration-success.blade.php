@extends('shared.layout')

@section('content')
    <div class="full-page-container d-flex align-items-center justify-content-center">
        <!-- Modal -->
        <div class="modal fade show d-block" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mx-auto" id="successModalLabel">Email Verification Required</h5>
                    </div>
                    <div class="modal-body text-center">
                        Please check your email to confirm your account registration.
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn custom-btn mx-auto" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'), {
                backdrop: 'static',
                keyboard: false
            });
            successModal.show();

            document.getElementById('successModal').addEventListener('hidden.bs.modal', function() {
                window.location.href = "{{ route('login') }}";
            });
        });
    </script>
@endsection
