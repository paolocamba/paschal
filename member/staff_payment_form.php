<div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Record Walk-in Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" action="process_walkin_payment.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Member ID</label>
                        <input type="number" name="member_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loan ID</label>
                        <input type="number" name="loan_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount Paid</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Receipt Number</label>
                        <input type="text" name="receipt_no" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// AJAX form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success');
            $('#recordPaymentModal').modal('hide');
            location.reload(); // Refresh to show new payment
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    });
});
</script>