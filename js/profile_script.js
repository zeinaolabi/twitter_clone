//Initialized variables
const backButton = document.getElementById("back_button")

//Remove info from local storage and redirect to login page
backButton.onclick = function() {
    window.location.replace("feed_page.html");
}