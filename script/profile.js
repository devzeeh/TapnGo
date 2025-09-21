document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let originalEmail = '';
    let newEmail = '';
    let otpTimer;
    const otpExpireTime = 120; // 2 minutes

    const elements = {
        email: document.getElementById('email'),
        verifyBtn: document.getElementById('verifyEmailBtn'),
        otpModal: document.getElementById('otpModal'),
        otpInputs: document.querySelectorAll('.otp-input'),
        timerEl: document.getElementById('otpTimer'),
        resendBtn: document.getElementById('resendOtpBtn'),
        cardNumber: document.getElementById('cardNumber')
    };

    // Mask card number on load
    elements.cardNumber.value = maskCardNumber(elements.cardNumber.value);

    // Email change handler
    elements.email.addEventListener('change', function() {
        newEmail = this.value;
        elements.verifyBtn.style.display = newEmail !== originalEmail && newEmail ? 'block' : 'none';
    });

    // OTP verification flow
    elements.verifyBtn.addEventListener('click', async function() {
        elements.otpModal.style.display = 'block';
        startOtpTimer();
        await sendOtp(newEmail);
    });

    // Handle OTP input navigation
    elements.otpInputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => handleOtpInput(e, index));
    });

    // Password visibility toggle
    document.querySelector('.show-password')?.addEventListener('click', togglePasswordVisibility);

    // Form submission
    document.getElementById('profileForm').addEventListener('submit', handleFormSubmit);
});

// Helper Functions
function maskCardNumber(cardNumber) {
    return `********${cardNumber.slice(-4)}`;
}

