//Initialize variables
//Binding values
const signupAPI = "http://localhost/twitter_test/signup.php";
const signinAPI = "http://localhost/twitter_test/signin.php";
const signinModal = document.getElementById("signin_modal");
const signupModal = document.getElementById("signup_modal");
const signinModalButton = document.getElementById("signinbtn_modal");
const signupModalButton = document.getElementById("signupbtn_modal");
const createButton = document.getElementById("create_account");
const closeSignin = document.getElementById("close_signin");
const closeSignup = document.getElementById("close_signup");
const userEmail = document.getElementById("email");
const userPass = document.getElementById("password");
const loginButton = document.getElementById("login");
const newUserName = document.getElementById("new_name");
const newUserEmail = document.getElementById("new_email");
const newUserPass= document.getElementById("new_password");
const registerButton = document.getElementById("register");
const signinError = document.getElementById("signin_error");
const signupError = document.getElementById("signup_error");
const year = document.getElementById('year');
const month = document.getElementById('month');
const day = document.getElementById('day');
let errorMessage = "";

//If the user is logged in, redirect to feed page
if(localStorage.getItem("userID")){
    window.location.replace("feed_page.html");
}

//When the login button is clicked, validate input 
loginButton.addEventListener("click", (event)=>{
    //If one of the functions return false, stop
    if(!validateEmail(userEmail.value) || !validatePassword(userPass.value)){
        signinError.textContent = errorMessage;
        return
    }

    //Send the data to the database using POST method
    fetch(signinAPI, {
        method: 'POST',
        body: new URLSearchParams({ "email": userEmail.value,
        "password": userPass.value}),
    })
    .then(response=>response.json())
    .then(
        data =>  {
        if(data.message !== undefined) {
            errorMessage = data.message
            signinError.textContent = errorMessage
            return
        }

        localStorage.setItem("userID", data.id)
        window.location.replace("feed_page.html");
    })
})

//When the registration button is clicked, validate input 
registerButton.addEventListener("click", (event)=>{
    //Get value from dropdown menu
    const chosenYear = year.options[year.selectedIndex];
    const chosenMonth = month.options[month.selectedIndex];
    const chosenDay = day.options[day.selectedIndex];
    const date = `${chosenYear.value}-${chosenMonth.value}-${chosenDay.value}`;

    //If one of the functions return false, stop
    if(!validateName(newUserName.value) || !validateEmail(newUserEmail.value) || 
    !validatePassword(newUserPass.value || !validateDate(chosenYear, chosenMonth, chosenYear))){
        signupError.textContent = errorMessage;
        return
    }

    //Send the data to the database using POST method
    fetch(signupAPI, {
        method: 'POST',
        body: new URLSearchParams({ "username": newUserName.value,
        "email": newUserEmail.value,
        "password": newUserPass.value,
        "birth_date": date}),
    })
    .then(response=>response.json())
    .then(
        data =>  {
        //Show error
        console.log(data)
        console.log(data.message)
        console.log(data.id)
        if (data.message !== undefined) {
            errorMessage = data.message
            signupError.textContent = errorMessage
            return
        }

        //Save ID in local storage
        localStorage.setItem("userID", data.id)
        //Redirect to feed page
        window.location.replace("feed_page.html");
    })
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
    email.substring(0,email.indexOf("@")).length < 3 ||
    email.substring(email.indexOf("@") + 1,email.length).length < 5){
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

function validateDate(year, month, day){
    //Check if a date was selected
    if(year == "Year" || month == "Month" || day == "Day"){
        errorMessage = "Error: Missing field" 
        return false
    }
    return true
}

//Create a year drop down menu
const yearDropdown = () => {
    let year_start = 1900;
    let year_end = (new Date).getFullYear();

    let option = '';
    option = '<option>Year</option>';

    //Adding options from 1900 until current year
    for (let i = year_start; i <= year_end; i++) {
        option += '<option value="' + i + '">' + i + '</option>';
    }

    year.innerHTML = option;
};

//Create a month drop down menu
const monthDropdown = () => {
    let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var month_selected = (new Date).getMonth();
    var option = '';
    option = '<option>Month</option>';

    //Adding an option for each month
    for (let i = 0; i < months.length; i++) {
        let month_number = (i + 1);

        //Add 0 for before value single digited numbers
        let month = (month_number <= 9) ? '0' + month_number : month_number;
        option += '<option value="' + month + '">' + months[i] + '</option>';
    }
    month.innerHTML = option;
};

//Create a day drop down menu 
const dayDropdown = () => {
    let option = '';
    option = '<option>Day</option>'; // first option

    //Adding an option for each day
    for (let i = 1; i < 32; i++) {
        //Add 0 before value for single digited numbers
        let day = (i <= 9) ? '0' + i : i;

        option += '<option value="' + day + '">' + day + '</option>';
    }
    day.innerHTML = option;
};

//Run functions
dayDropdown();
monthDropdown();
yearDropdown();
