// Prevent future dates in picker
window.onload = function () {
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("incident").setAttribute("max", today);
};

function showForm(type) {
    document.getElementById("reportType").value = type;

    document.querySelector(".form-group").style.display = "flex";
    document.querySelector(".form-description").style.display = "block";
    document.querySelector(".submit-btn").style.display = "block";
}

function showForm(type) {
    document.getElementById("reportType").value = type;

    document.querySelector(".form-group").style.display = "flex";
    document.querySelector(".form-description").style.display = "block";
    document.querySelector(".submit-btn").style.display = "block";

    const buttons = document.querySelectorAll('.report-button');
    buttons.forEach(button => {
        if (button.textContent.includes(type)) {
            button.classList.remove('faded'); // The clicked button stays bright
        } else {
            button.classList.add('faded'); // The other button fades
        }
    });
}