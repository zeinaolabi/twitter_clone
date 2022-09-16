//Initialize variables
const signupAPI = "http://localhost/twitter_test/signup.php";
const signinAPI = "http://localhost/twitter_test/signin.php";
const signinModal = document.getElementById("signin_modal");
const signupModal = document.getElementById("signup_modal");
const signinModalButton = document.getElementById("signinbtn_modal");
const signupModalButton = document.getElementById("signupbtn_modal");
const createButton = document.getElementById("create_account");
const closeSignin = document.getElementById("close_signin");
const closeSignup = document.getElementById("close_signup");
const userName = document.getElementById("name");
const userEmail = document.getElementById("email");
const userPass= document.getElementById("password");
const loginButton = document.getElementById("login");
const newUserName = document.getElementById("new_name");
const newUserEmail = document.getElementById("new_email");
const newUserPass= document.getElementById("new_password");
const registerButton = document.getElementById("register");
const signinError = document.getElementById("signin_error");
const signupError = document.getElementById("signup_error");
let errorMessage = "";

//Adding drop down menus for date
dates('option');
months('option');
years('option', 1990, 2022);

//When the registration button is clicked, validate input 
loginButton.addEventListener("click", (event)=>{
    //If one of the functions return false, stop
    if(!validateEmail(userEmail.value) || !validatePassword(userPass.value)){
        signinError.textContent = errorMessage ;
        return
    }

    //Send the data to the database using POST method
    fetch(signupAPI, {
        method: 'POST',
        body: new URLSearchParams({ "email": newUserEmail.value,
        "password": newUserPass.value}),
    })
    .then(response=>response.json())
    .then(data => error.textContent = data.success)
})

//When the registration button is clicked, validate input 
registerButton.addEventListener("click", (event)=>{
    //If one of the functions return false, stop
    console.log(newUserName.value);
    if(!validateName(newUserName.value) || !validateEmail(newUserEmail.value) || !validatePassword(newUserPass.value)){
        signupError.textContent = errorMessage;
        return
    }

    //Send the data to the database using POST method
    fetch(signupAPI, {
        method: 'POST',
        body: new URLSearchParams({ "username": newUserName.value,
        "email": newUserEmail.value,
        "password": newUserPass.value}),
    })
    .then(response=>response.json())
    .then(data => error.textContent = data.success)
})

//When the user clicks on the sign in button, open the modal
signinModalButton.onclick = function() {
    signinModal.style.display = "block";
}

//When the user clicks on the button sign up, open the modal
signupModalButton.onclick = function() {
    signupModal.style.display = "block";
}

//When the user clicks on creating an account, sign in modal closes and sign up opens
createButton.onclick = function (){
    signinModal.style.display = "none";
    signupModal.style.display = "block";
}

//When the user clicks on x, close the modal(sign in)
closeSignin.onclick = function() {
    signinModal.style.display = "none";
}

//When the user clicks on x, close the modal(sign up)
closeSignup.onclick = function() {
    signupModal.style.display = "none";
}

//When the user clicks anywhere outside of the modals, close them
window.onclick = function(event) {
    if (event.target == signinModal){
      signinModal.style.display = "none";
    }
  
    if (event.target == signupModal) {
      signupModal.style.display = "none";
    }
}

function validateName(name){
    //Check if the input is not empty and is a string
    if(name == ""){
        errorMessage = "Error: Missing field" 
        return false
    }
    else if(typeof name !== "string"){
        errorMessage = "Error: Invalid name";
        return false
    }

    return true
}

function validateEmail(email){
    //Check if the email is valid and has 3 characters after the @ and 5 after
    if(email == ""){
        errorMessage ="Error: Missing field" 
        return false
    }
    else if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) || 
    email.substring(0,email.value.indexOf("@")).length < 3 ||
    email.substring(email.value.indexOf("@") + 1,email.length).length < 5){
        errorMessage = "Error: Invalid email";
        return false
    }
    return true
}

function validatePassword(password){
    //Check if the input is not empty and is a string
    if(password == ""){
        errorMessage = "Error: Missing field" 
        return false
    }

    return true
}

