var regModal = document.getElementById("reg_modal");
var signup = document.getElementById("signup_btn");
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
signup.onclick = function() {
    regModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    regModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == regModal) {
    regModal.style.display = "none";
  }
}

//Adding drop down menus for date
dates('option');
months('option');
years('option', 1990, 2022);