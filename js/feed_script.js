const tweetModal = document.getElementById("tweet_modal");
const tweetButton = document.getElementById("showtweet_btn");
const closeTweet = document.getElementById("close_tweet");
const popupButton = document.getElementById("popup_btn");
const popup = document.getElementById("myPopup");

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
