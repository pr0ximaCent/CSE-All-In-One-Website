function showLoginForm() {
    var loginCard = document.getElementById("login-card");
    var signupCard = document.getElementById("signup-card");
  
    loginCard.style.display = "block";
    signupCard.style.display = "none";
}

function showSignupForm() {
    var loginCard = document.getElementById("login-card");
    var signupCard = document.getElementById("signup-card");
  
    loginCard.style.display = "none";
    signupCard.style.display = "block";
}
