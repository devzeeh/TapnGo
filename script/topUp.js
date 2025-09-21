const MAX_AMOUNT = 20000;
const MIN_AMOUNT = 50;
const inputField = document.getElementById("card-amount");
const checkoutButton = document.querySelector('button[type="submit"]');

// Select ALL amount buttons
const buttonAmounts = document.querySelectorAll(".topup-button-grid button");

// Save original checkout color
const originalCheckoutColor = checkoutButton.style.backgroundColor;

// Disable checkout button initially
checkoutButton.disabled = true;
checkoutButton.style.backgroundColor = "#cccccc"; // light gray

// Event listener for grid buttons
document
    .querySelector(".topup-button-grid")
    .addEventListener("click", function (e) {
        if (e.target.tagName === "BUTTON") {
            const amount = parseInt(e.target.textContent);
            validateAmount(amount);

            // Highlight the clicked button, fade others
            buttonAmounts.forEach((button) => {
                if (button === e.target) {
                    button.style.opacity = "1"; // clicked button normal
                } else {
                    button.style.opacity = "0.5"; // others faded
                }
            });
        }
    });

// Event listener for input field
inputField.addEventListener("input", function (e) {
    const amount = parseInt(e.target.value);
    if (!e.target.value) {
        checkoutButton.disabled = true;
        checkoutButton.style.backgroundColor = "#cccccc"; // light gray
        resetValidation();
        clearButtonHighlight();
        return;
    }
    validateAmount(amount);
    clearButtonHighlight(); // if user types manually, remove faded buttons
});

// Event listener for cancel button
document
    .querySelector('button[type="reset"]')
    .addEventListener("click", function (e) {
        e.preventDefault();
        clearAmounts();
        resetValidation();
        checkoutButton.disabled = true;
        checkoutButton.style.backgroundColor = "#cccccc"; // light gray
        clearButtonHighlight();
    });

function validateAmount(amount) {
    if (amount < MIN_AMOUNT) {
        showError("Minimum top up amount is ₱50");
        checkoutButton.disabled = true;
        checkoutButton.style.backgroundColor = "#cccccc";
    } else if (amount > MAX_AMOUNT) {
        showError("Maximum amount is ₱20,000");
        checkoutButton.disabled = true;
        checkoutButton.style.backgroundColor = "#cccccc";
    } else {
        updateAmounts(amount);
        resetValidation();
        checkoutButton.disabled = false;
        checkoutButton.style.backgroundColor = originalCheckoutColor; // restore color
    }
}

function updateAmounts(amount) {
    // Update input field
    inputField.value = amount;
    // Update top-up card span
    const formattedAmount = parseFloat(amount).toLocaleString("en-PH", {
        style: "currency",
        currency: "PHP",
    });
    document.querySelector(".amount span").textContent = formattedAmount;
}

function clearAmounts() {
    inputField.value = "";
    document.querySelector(".amount span").textContent = "₱ 0.00";
}

function showError(message) {
    inputField.style.border = "2px solid red";
    const errorDiv = document.querySelector(".error-message");
    errorDiv.style.display = "block";
    errorDiv.style.color = "red";
    errorDiv.style.marginTop = "5px";
    errorDiv.textContent = message;
}

function resetValidation() {
    inputField.style.border = "";
    const errorMessage = document.querySelector(".error-message");
    if (errorMessage) {
        errorMessage.style.display = "none";
        errorMessage.textContent = "";
    }
}

function clearButtonHighlight() {
    buttonAmounts.forEach((button) => {
        button.style.opacity = "1"; // reset all buttons to normal
    });
}
