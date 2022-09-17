//Initialized variables
const backButton = document.getElementById("back_button")
const postTweetAPI ="http://localhost/twitter_test/post_tweet.php";
const tweetInput = document.getElementById("tweet_input");
const addTweetButton = document.getElementById("add_tweet");

//Remove info from local storage and redirect to login page
backButton.onclick = function() {
    window.location.replace("feed_page.html");
}

addTweetButton.onclick = function(){
    postTweet(tweetInput.value);
    tweetModal.style.display = "none";
    tweetInput.value = "";
}

const postTweet = (tweet) => {
    //If tweet is empty, do nothing
    if(tweet == "" && addedImage.value == ""){
        return
    }
    
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
    image.style.display = "none";
    originalTweet.after(clone);
}
