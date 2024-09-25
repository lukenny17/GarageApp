<div class="modal fade" id="reviewBookingModal" tabindex="-1" aria-labelledby="reviewBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="review-booking-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h5 class="modal-title text-center" id="reviewBookingModalLabel">Leave a Review</h5>
                    <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <div class="star-rating">
                            <input type="hidden" name="rating" id="rating" required>
                            <i class="fas fa-star" data-value="1"></i>
                            <i class="fas fa-star" data-value="2"></i>
                            <i class="fas fa-star" data-value="3"></i>
                            <i class="fas fa-star" data-value="4"></i>
                            <i class="fas fa-star" data-value="5"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn custom-btn btn-submit-review">Submit Review</button>
                </div>
            </div>
        </form>
    </div>
</div>
