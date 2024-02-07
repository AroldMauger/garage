const loginButton = document.querySelector(".login-button");
const loginForm = document.querySelector(".login-form");
const retourButton = document.querySelector(".closebutton_modal");

loginButton.addEventListener("click", displayLoginForm)
retourButton.addEventListener("click", closeLoginForm)

function displayLoginForm () {
    loginForm.style.display = "block";
}

function closeLoginForm () {
    loginForm.style.display = "none";
}