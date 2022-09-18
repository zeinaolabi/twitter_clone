const getTweetsAPI = "http://localhost/twitter_test/get_tweets.php?user_id=" + localStorage.getItem("userID").toString();
const postTweetAPI ="http://localhost/twitter_test/post_tweet.php";;
const addTweetButton = document.getElementById("add_tweet");
const addTweetButton2 = document.getElementById("add_tweet2");
const tweetInput = document.getElementById("tweet_input");
const tweetInput2 = document.getElementById("tweet_input2");
const addedImage = document.getElementById("added_image");
const addedImage2 = document.getElementById("added_image2");
const likeButtons = document.querySelectorAll(".like_btn");
const likeImage = document.getElementById("like_image");

addTweetButton.onclick = function(){
    postTweet(tweetInput.value, addedImage);
    tweetInput.value = "";
    addedImage.value = "";
}

addTweetButton2.onclick = function(){
    postTweet(tweetInput2.value, addedImage2);
    tweetModal.style.display = "none";
    tweetInput2.value = "";
    addedImage2.value = "";
}

const postTweet = (tweet, addedImage) => {
    const addedImageValue = addedImage.value;
    const [file] = addedImage.files;

    //If tweet is empty, do nothing
    if(tweet == "" && addedImageValue == ""){
        return
    }

    if(tweet.length > 280){
        return
    }

    const base64Image = btoa(encodeURIComponent(addedImageValue));

    // Send the data to the database using POST method
    fetch(postTweetAPI, {
        method: 'POST',
        body: new URLSearchParams({"user_id": userID,
        "tweet": tweet,
        "image": base64Image
    }),
    })
    .then(response=>response.json())
    .then(
        data =>  {
        //Show error
        if (data.message == "") {
            //Do nothing - TO BE EDITED
            return
        }

        let originalTweet = document.getElementById("tweet");
        let clone = originalTweet.cloneNode(true);
        clone.style.display ="flex";
  
        clone.id= data.tweet_id;
        clone.classList.add("tweet");
        let paragraph = clone.querySelector(".tweet_text");
        paragraph.textContent = tweet;
        let image = clone.querySelector(".tweet_image");

        if(addedImageValue == ""){
            image.style.display = "none";
        }
        else{
            if (file){
                image.src = URL.createObjectURL(file);
            }
        }
        originalTweet.after(clone);
    })

    //TO BE EDITED
    // likeButtons.forEach(likeButton => addEventListener('click', liked))
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
            likes.id = data.tweet_id;
           
            //Add div after the original tweet
            originalTweet.after(clone);
        }
    })
}

// const liked = () =>{
//     likeImage.src = "images/redheart.png";
// }


