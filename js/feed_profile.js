//Initialize variables
const searchAPI = "http://localhost/twitter_test/search.php?username=";
const tweetModal = document.getElementById("tweet_modal");
const tweetButton = document.getElementById("showtweet_btn");
const closeTweet = document.getElementById("close_tweet");
const popupButton = document.getElementById("popup_btn");
const popup = document.getElementById("myPopup");
const twitterButton = document.getElementById("twitter_button");
const homeButton = document.getElementById("home_button");
const profileButton = document.getElementById("profile_button");
const logoutButton = document.getElementById("logout_button");
const searchInput = document.getElementById("search_input");
const searchResult = document.getElementById("search_result");
const userID = localStorage.getItem("userID");

//When user adds an input, search for results
searchInput.onkeyup = function() {
    searchResult.style.display = "block";
    search();

    if(searchInput.value == ""){
        searchResult.style.display = "none";
    }
}

//Remove info from local storage and redirect to login page
logoutButton.onclick = function() {
    localStorage.clear();
    window.location.replace("login_page.html");
}

//When the user clicks on the home button, refresh page
homeButton.onclick = function() {
    refresh();
}

//When the user clicks on the twitter button, refresh page
twitterButton.onclick = function() {
    refresh();
}

//When the user clicks on the profile button, redirect to profile page
profileButton.onclick = function() {
    window.location.replace("profile_page.html");
}

//When the user clicks on the button, open the modal
tweetButton.onclick = function() {
    tweetModal.style.display = "block";
}

//When the user clicks on x, close the modal
closeTweet.onclick = function() {
    tweetModal.style.display = "none";
}

//When the user clicks, open the popup
popupButton.onclick = function(event){
    popup.classList.toggle("show");
}

//When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if(event.target == tweetModal){
        tweetModal.style.display = "none";
    }
}

const search = () => {
    //Get search input and filter it
    filteredUsername = searchInput.value.toLowerCase().trim();

    //Send data to the server using fetch
    fetch(searchAPI + filteredUsername)
    .then(response=>response.json())
    .then(data => {
        //Check if there's an error
        if(data.message !== undefined){
            //Do nothing
            return
        }

        //Create a list item and add it results
        let li = document.createElement("li");
        li.appendChild(document.createTextNode(data.username));
        li.id = data.id
        searchResult.innerHTML = '';
        searchResult.appendChild(li);
    })
}

const refresh = () => {
    //refresh feed pade
    window.location.replace("feed_page.html");
}