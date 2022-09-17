const getTweetsAPI = "http://localhost/twitter_test/get_tweets.php"
const addTweetButton = document.getElementById("add_tweet");
const tweetInput = document.getElementById("tweet_input");
const postTweetAPI ="http://localhost/twitter_test/post_tweet.php";
const tweetInput2 = document.getElementById("tweet_input2");
const addTweetButton2 = document.getElementById("add_tweet2");
const addedImage = document.getElementById("added_image");
const userID = localStorage.getItem("userID");

addTweetButton.onclick = function(){
    postTweet(tweetInput.value);
    tweetInput.value = "";
}

addTweetButton2.onclick = function(){
    postTweet(tweetInput2.value);
    tweetModal.style.display = "none";
    tweetInput2.value = "";
}

const refresh = () => {
    window.location.replace("feed_page.html");
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

const viewTweets = () =>{
    // Send the data to the database using POST method
    fetch(getTweetsAPI)
    .then(response=>response.json())
    .then(
        data =>  {
        //Show error
        if (data.message !== undefined) {
            //Do nothing - TO BE EDITED
            return
        }

        //Loop over the response
        for(let i = 0; i < data.length; i++){
            //Make a clone of the tweet model
            let originalTweet = document.getElementById("tweet");
            let clone = originalTweet.cloneNode(true);
            clone.style.display ="flex";
            clone.id= data.tweet_id;
            clone.classList.add("tweet");

            //Get the tweet text and modify on it
            let paragraph = clone.querySelector(".tweet_text");
            paragraph.textContent = data.tweet;

            //Get the image and decode it from base64
            let image = clone.querySelector(".tweet_image");
            let decodeBase64 = decodeURIComponent(escape(window.atob(data.image)));
            image.src = URL.createObjectURL(decodeBase64);

            //Get likes
            let likes = clone.querySelector(".likes_number");
            likes.textContent = data.likes;
           
            //Add div after the original tweet
            originalTweet.after(clone);
        }
    })
}