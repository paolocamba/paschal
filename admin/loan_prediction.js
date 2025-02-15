function fetchLoanPrediction(loanId) {
    // Show loading state
    const predictionDetails = document.getElementById('prediction_details_' + loanId);
    if (predictionDetails) {
        predictionDetails.style.display = 'none';
    }

    // Add loading indicator
    const loadingIndicator = document.createElement('div');
    loadingIndicator.id = 'loading_' + loanId;
    loadingIndicator.textContent = 'Loading prediction...';
    predictionDetails?.parentNode?.insertBefore(loadingIndicator, predictionDetails);

    fetch('get_loan_prediction.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'LoanID=' + encodeURIComponent(loanId)
    })
    .then(async response => {
        // Remove loading indicator
        document.getElementById('loading_' + loanId)?.remove();

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // First try to get the response as text
        const textResponse = await response.text();
        
        try {
            // Then try to parse it as JSON
            return JSON.parse(textResponse);
        } catch (e) {
            console.error('JSON Parse Error. Response received:', textResponse);
            throw new Error(`Invalid JSON response: ${e.message}`);
        }
    })
    .then(data => {
        if (!data) {
            throw new Error('No data received from server');
        }

        if (data.success) {
            updatePredictionUI(loanId, data.prediction);
        } else {
            throw new Error(data.error || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        
        // Show user-friendly error message
        const errorMessage = document.createElement('div');
        errorMessage.className = 'alert alert-danger';
        errorMessage.textContent = `Unable to fetch prediction: ${error.message}`;
        
        // Insert error message before prediction details
        const predictionDetails = document.getElementById('prediction_details_' + loanId);
        predictionDetails?.parentNode?.insertBefore(errorMessage, predictionDetails);
        
        // Remove error message after 5 seconds
        setTimeout(() => errorMessage.remove(), 5000);
    });
}

function updatePredictionUI(loanId, prediction) {
    // Update loanable amount
    const loanableAmountField = document.getElementById('loanable_amount_' + loanId);
    if (loanableAmountField) {
        loanableAmountField.value = formatAmount(prediction.predicted_amount);
    }

    // Update prediction details
    const predictionDetails = document.getElementById('prediction_details_' + loanId);
    if (predictionDetails) {
        predictionDetails.style.display = 'block';

        // Update eligibility
        const eligibilityElement = document.getElementById('loan_eligibility_' + loanId);
        if (eligibilityElement) {
            eligibilityElement.textContent = prediction.loan_eligibility;
            eligibilityElement.className = prediction.loan_eligibility.startsWith('Eligible') 
                ? 'text-success' 
                : 'text-danger';
        }

        // Update other prediction details
        updateElement('interest_rate_' + loanId, formatPercent(prediction.interest_rate));
        const paymentLabel = prediction.payment_interval > 1 
        ? `Payment every ${prediction.payment_interval} months` 
        : 'Monthly Payment';
    updateElement('payment_label_' + loanId, paymentLabel);
    
    updateElement('periodic_payment_' + loanId, formatCurrency(prediction.periodic_payment));
        updateElement('total_payment_' + loanId, formatCurrency(prediction.total_payment));
        // Update total value if it exists (for Collateral loans)
        const totalValueElement = document.getElementById('total_value_' + loanId);
        if (totalValueElement && prediction.total_value) {
            updateElement('total_value_' + loanId, formatCurrency(prediction.total_value));
        }
    }
}

// Helper functions
function updateElement(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = value;
    }
}

function formatAmount(amount) {
    return Number(amount).toFixed(2);
}

function formatPercent(value) {
    return `${Number(value).toFixed(2)}%`;
}

function formatCurrency(amount) {
    return `₱${Number(amount).toFixed(2)}`;
}

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('[data-toggle="modal"][data-target^="#editModal"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const loanId = this.getAttribute('data-target').replace('#editModal', '');
            fetchLoanPrediction(loanId);
        });
    });
});