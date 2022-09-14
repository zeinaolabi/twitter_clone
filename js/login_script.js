const signinModal = document.getElementById("signin_modal");
const signupModal = document.getElementById("signup_modal");
const signinButton = document.getElementById("signin");
const signupButton = document.getElementById("signup");
const createButton = document.getElementById("create_account");
const closeSignin = document.getElementById("close_signin");
const closeSignup = document.getElementById("close_signup");

//When the user clicks on the button, open the modal
signinButton.onclick = function() {
    signinModal.style.display = "block";
}

//When the user clicks on the button, open the modal
signupButton.onclick = function() {
    signupModal.style.display = "block";
}

//When the user clicks on creating an account, sign in modal closes and sign up opens
createButton.onclick = function (){
    signinModal.style.display = "none";
    signupModal.style.display = "block";
}

//When the user clicks on x, close the modal
closeSignin.onclick = function() {
    signinModal.style.display = "none";
}

//When the user clicks on x, close the modal
closeSignup.onclick = function() {
    signupModal.style.display = "none";
}

//When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == signinModal){
      signinModal.style.display = "none";
    }
  
    if (event.target == signupModal) {
      signupModal.style.display = "none";
    }
}

//Adding drop down menus for date
dates('option');
months('option');
years('option', 1990, 2022);