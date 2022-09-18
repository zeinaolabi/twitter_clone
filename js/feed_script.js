const getTweetsAPI = "http://localhost/twitter_test/get_tweets.php?user_id=" + localStorage.getItem("userID").toString();
const postTweetAPI = "http://localhost/twitter_test/post_tweet.php";
const likeTweetAPI = "http://localhost/twitter_test/like_tweet.php?user_id="+ localStorage.getItem("userID").toString() + "&tweet_id=";
const unlikeTweetAPI = "http://localhost/twitter_test/unlike_tweet.php?user_id="+ localStorage.getItem("userID").toString() + "&tweet_id=";
const likedTweetAPI = "http://localhost/twitter_test/post_liked.php?user_id="+ localStorage.getItem("userID").toString() + "&tweet_id=";
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

    //Send data to the server using fetch
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
            if(file){
                image.src = URL.createObjectURL(file);
            }
        }
        originalTweet.after(clone);
    })

    //TO BE EDITED
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
        for(let i = 0; i < Object.keys(data).length; i++){
            //Make a clone of the tweet model
            let originalTweet = document.getElementById("tweet");
            let clone = originalTweet.cloneNode(true);
            clone.style.display ="flex";
            clone.id= data[i].tweet_id;
            clone.classList.add("tweet");

            //Get the tweet text and modify on it
            let paragraph = clone.querySelector(".tweet_text");
            paragraph.textContent = data[i].tweet;

            //Get username
            let username = clone.querySelector(".username");
            username.textContent = "@" + data[i].username;

            //Get name
            let name = clone.querySelector(".name");
            if(data[i].name != null){
                name.textContent = data[i].name;
            }

            //Get Profile picture
            let profilePic = clone.querySelector(".profile_pic");
            if(data[i].profile_picture != null){
                profilePic.src = 'data:image/jpeg;base64,' + data[i].profile_picture;
            }

            //Get the image if avaiable and show it
            let image = clone.querySelector(".tweet_image");
            if(data[i].image == null){
                image.style.display = "none";
            }
            else{
                image.src = 'data:image/jpeg;base64,' + data[i].image;
            }
            
            //Get likes
            let likes = clone.querySelector(".likes_number");
            likes.textContent = data[i].likes_count;
            likes.id = data[i].id;

            //Get like buttons
            let likeButton = clone.querySelector(".like_btn");
            likeButton.setAttribute('data', data[i].tweet_id);

            fetch(likedTweetAPI + data[i].id)
            .then(response => response.json())
            .then(data =>{

                likeButton.setAttribute('isLiked', data);
                likeButton.querySelector("#like_image").src = data ? "images/redheart.png" : "images/heart.png";

                likeButton.addEventListener('click', (event) => {
                    let tweet_id = event.currentTarget.getAttribute('data');
                    let isLiked = event.currentTarget.getAttribute('isLiked') === "true";
                    const tweetApi = isLiked ? unlikeTweetAPI : likeTweetAPI;
                    //Send data to the server using fetch
                    fetch(tweetApi + tweet_id)
                    .then(response=>response.json())
                    .then(data =>  {
                        //Show error
                        if (data.error !== undefined) {
                            //Do nothing
                            return
                        }

                        likes.textContent = isLiked? parseInt(likes.textContent) -1 : parseInt(likes.textContent) + 1;

                        likeButton.setAttribute('isLiked', !isLiked);
                        likeButton.querySelector("#like_image").src = isLiked ? "images/heart.png" : "images/redheart.png";
                    })
                });
            }
            
            )
            
            //Add div after the original tweet
            originalTweet.after(clone);
        }
    })
}

viewTweets();


