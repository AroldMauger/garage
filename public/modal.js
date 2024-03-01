const openModalButton = document.querySelector(".rdv-button-modifier");
const modalDashboard = document.querySelector(".modal-dashboard");
const retourButton = document.querySelector(".closebutton_modal");

openModalButton.addEventListener("click", displayModalDashboard)
retourButton.addEventListener("click", closeModalDashboard)

function displayModalDashboard () {
    modalDashboard.style.display = "block";
}

function closeModalDashboard () {
    modalDashboard.style.display = "none";
}