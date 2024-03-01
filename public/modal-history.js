const openModalButtons = document.querySelectorAll(".button-table");
const modalHistory = document.querySelector(".modal-history");
const retourButton = document.querySelector(".closebutton_modal");

openModalButtons.forEach(button => {
    button.addEventListener("click", displayModalHistory);
});

retourButton.addEventListener("click", closeModalHistory);

function displayModalHistory(event) {
    const button = event.currentTarget;
    const modalData = {
        date: button.dataset.date,
        time: button.dataset.time,
        reason: button.dataset.reason,
        car: button.dataset.car,
        customer: button.dataset.customer,
        phone: button.dataset.phone
    };

    populateModal(modalData);
    modalHistory.style.display = "block";
}

function populateModal(data) {
    const modalDate = document.querySelector(".data-modal-date");
    const modalTime = document.querySelector(".data-modal-time");
    const modalReason = document.querySelector(".data-modal-reason");
    const modalCar = document.querySelector(".data-modal-car");
    const modalCustomer = document.querySelector(".data-modal-customer");
    const modalPhone = document.querySelector(".data-modal-phone");

    modalDate.textContent = data.date;
    modalTime.textContent = data.time;
    modalReason.textContent = data.reason;
    modalCar.textContent = data.car;
    modalCustomer.textContent = data.customer;
    modalPhone.textContent = data.phone;
}

function closeModalHistory() {
    modalHistory.style.display = "none";
}
