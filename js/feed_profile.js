//Initialize variables
const searchAPI = "http://localhost/twitter_test/search.php?id=";
const postTweetAPI ="http://localhost/twitter_test/post_tweet.php";
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
const tweetInput = document.getElementById("tweet_input");
const tweetInput2 = document.getElementById("tweet_input2");
const addTweetButton = document.getElementById("add_tweet");
const addTweetButton2 = document.getElementById("add_tweet2");
const addedImage = document.getElementById("added_image");
const userID = localStorage.getItem("userID");

addTweetButton.onclick = function(){
    postTweet(tweetInput.value);
}

addTweetButton2.onclick = function(){
    postTweet(tweetInput2.value);
    tweetModal.style.display = "none";
}

const refresh = () => {
    window.location.replace("feed_page.html");
}

//When user adds an input, search for results
searchInput.onkeyup = function() {
    searchResult.style.display = "block";
    // search();

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
    if (event.target == tweetModal){
        tweetModal.style.display = "none";
    }
}

//TO BE EDITED
const search = () => {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById('myInput');
    filter = input.value.lowercase().trim();

    fetch(searchAPI + userID)
    .then(response=>response.json())
    .then(data => localStorage.setItem("userID", data.id))
}

const postTweet = (tweet) => {
    // Send the data to the database using POST method
    // fetch(postTweetAPI, {
    //     method: 'POST',
    //     body: new URLSearchParams({ "user_id": userID,
    //     "tweet": tweetInput.value}),
    // })
    // .then(response=>response.json())
    // .then(
    //     data =>  {
    //     //Show error
    //     if (data.message !== undefined) {
    //         //Do nothing - TO BE EDITED
    //         return
    //     }

    //     let originalTweet = document.getElementById("tweet");
    //     let clone = originalTweet.cloneNode(true);
    //     clone.style.display ="flex";
  
    //     clone.id= data.tweet_id;
    //     clone.classList.add("tweet");
    //     let paragraph = clone.querySelector(".tweet_text");
    //     paragraph.textContent = data.tweet;
    //     let image = clone.querySelector(".tweet_image");
    //     if(addedImage.value == ""){
    //         image.style.display = "none";
    //     }
    //     else{
    //         const [file] = addedImage.files
                //    if (file) {
                //     image.src = URL.createObjectURL(file)
                // }
    //     }
    //     originalTweet.after(clone);
    // })


    let originalTweet = document.getElementById("tweet");
    let clone = originalTweet.cloneNode(true);
    clone.style.display ="flex";

    clone.id="test";
    clone.classList.add("tweet");
    let paragraph = clone.querySelector(".tweet_text");
    paragraph.textContent = tweet;
    let image = clone.querySelector(".tweet_image");

    if(addedImage.value == ""){
        image.style.display = "none";
    }
    else{
        const [file] = addedImage.files
        if (file) {
            image.src = URL.createObjectURL(file)
        }
    }
    originalTweet.after(clone);
}
