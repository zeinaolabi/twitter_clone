//Initialized variables
const postTweetAPI ="http://localhost/twitter_test/post_tweet.php";
const getUserTweetsAPI = "http://localhost/twitter_test/get_user_tweets.php?user_id=" + localStorage.getItem("userID").toString();
const likeTweetAPI = "http://localhost/twitter_test/like_tweet.php?user_id="+ localStorage.getItem("userID").toString() + "&tweet_id=";
const unlikeTweetAPI = "http://localhost/twitter_test/unlike_tweet.php?user_id="+ localStorage.getItem("userID").toString() + "&tweet_id=";
const likedTweetAPI = "http://localhost/twitter_test/post_liked.php?user_id="+ localStorage.getItem("userID").toString() + "&tweet_id=";
const getInfoAPI = "http://localhost/twitter_test/get_info.php?user_id=" + localStorage.getItem("userID").toString();
const backButton = document.getElementById("back_button")
const tweetInput = document.getElementById("tweet_input");
const addTweetButton = document.getElementById("add_tweet");
const addedImage = document.getElementById("added_image");
const editProfileModal = document.getElementById("editprofile_modal");
const saveButton = document.getElementById("save_edit");
const closeEdit = document.getElementById("close_edit");
const modalButton = document.getElementById("editmodal_btn");
const followersButton = document.getElementById("followers");
const coverImages = document.querySelectorAll(".cover");
const profilePictures = document.querySelectorAll(".profile_picture");
const userNames = document.querySelectorAll(".name");
const usernames = document.querySelectorAll(".username");

//Remove info from local storage and redirect to login page
backButton.onclick = function() {
    window.location.replace("feed_page.html");
}

//Redirect to followers/followings page
followersButton.onclick = function() {
    window.location.replace("followings_page.html");
}

//On tweet modal click on the button to post a tweet
addTweetButton.onclick = function(){
    //Empty modal content after posting
    postTweet(tweetInput.value, addedImage);
    tweetModal.style.display = "none";
    tweetInput.value = "";
    addedImage.value = "";
}

modalButton.onclick = function() {
    editProfileModal.style.display = "block";
}
  
// When the user clicks on x, close the modal
closeEdit.onclick = function() {
    editProfileModal.style.display = "none";
}

const postTweet = (tweet, addedImage) => {
    //Save added image value and files
    const addedImageValue = addedImage.value;
    const [file] = addedImage.files;

    //If tweet is empty, do nothing
    if(tweet == "" && addedImageValue == ""){
        return
    }

    //If tweet length is more than 280, do nothing
    if(tweet.length > 280){
        return
    }

    //Send post tweet request to the server
    const sendTweetRequest = (base64Image) => {
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
                //Do nothing
                return
            }
    
            //Copy tweet modal and display it
            let originalTweet = document.getElementById("tweet");
            let clone = originalTweet.cloneNode(true);
            clone.style.display ="flex";
            clone.id= data.tweet_id;
            clone.classList.add("tweet");

            //Set the profile picture to the user's
            let profile = clone.querySelector(".profile_pic");
            profile.src = localStorage.getItem("profile_picture");
    
            //Change paragraph data according to tweet
            let paragraph = clone.querySelector(".tweet_text");
            paragraph.textContent = tweet;
            let image = clone.querySelector(".tweet_image");
    
            //If the tweet includes an image, show it
            if(addedImageValue == ""){
                image.style.display = "none";
            }
            else{
                if(file){
                    image.src = URL.createObjectURL(file);
                }
            }

            //Add tweet after the original clone
            originalTweet.after(clone);
        })
    }

    const reader = new FileReader();
    reader.addEventListener("load", (event) => {
        // convert image file to base64 string
        sendTweetRequest(event.currentTarget.result)
    }, false);

    if(file){
        //If the tweet includes an image, add it
        reader.readAsDataURL(file);
    }
    else{
        //If the tweet doesn't include an image, send null
        sendTweetRequest(null);
    }
}

const viewTweets = () =>{
    // Send the data to the database using POST method
    fetch(getUserTweetsAPI)
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
                profilePic.src = "data:image/png;base64," + data[i].profile_picture;
            }

            //Get the image if avaiable and show it
            let image = clone.querySelector(".tweet_image");
            if(data[i].image == null){
                image.style.display = "none";
            }
            else{
                image.src = "data:image/png;base64," + data[i].image;
            }
            
            //Get likes
            let likes = clone.querySelector(".likes_number");
            likes.textContent = data[i].likes_count;
            likes.id = data[i].id;

            //Get like buttons, and save the tweet id as an attribute
            let likeButton = clone.querySelector(".like_btn");
            likeButton.setAttribute('data', data[i].tweet_id);

            //Check if the post is liked or not
            fetch(likedTweetAPI + data[i].id)
            .then(response => response.json())
            .then(data =>{

                //Save the result of the tweet is liked or not, change the button accordingly
                likeButton.setAttribute('isLiked', data);
                likeButton.querySelector("#like_image").src = data ? "images/redheart.png" : "images/heart.png";

                //When like button is clicked, send a request to the server
                likeButton.addEventListener('click', (event) => {
                    let tweet_id = event.currentTarget.getAttribute('data');
                    let isLiked = event.currentTarget.getAttribute('isLiked') === "true";
                    const tweetApi = isLiked ? unlikeTweetAPI : likeTweetAPI;

                    //Send data to the server using fetch
                    fetch(tweetApi + tweet_id)
                    .then(response=>response.json())
                    .then(data =>  {

                        if (data.error !== undefined) {
                            //Do nothing
                            return
                        }

                        //Change the number of likes on like/unlike
                        likes.textContent = isLiked? parseInt(likes.textContent) -1 : parseInt(likes.textContent) + 1;

                        //Change button image on click
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

const getUserInfo = () =>{
    //Get request to get the users info from the server
    fetch(getInfoAPI)
    .then(response => response.json())
    .then(data =>{
        if (data.error !== undefined) {
            //Do nothing
            return
        }

        //If a profile image is assigned, save it in the local storage
        if(data.profile_picture != null){
            profilePictures.forEach(profilePicture => profilePicture.src = 'data:image/jpeg;base64,' + data.profile_picture);
        }

        //If a name is assigned, save it in the local storage
        if(data.name != null){
            userNames.forEach(userName => userName.textContent = data.name);
        }

        //If a bio is assigned, save it in the local storage
        if(data.bio != null){
            userNames.forEach(userName => userName.textContent = data.bio);
        }

        //If a cover image is assigned, save it in the local storage
        if(data.cover_picture != null){
            coverImages.forEach(coverImage => coverImage.src = 'data:image/jpeg;base64,' + data.cover_picture);
            localStorage.setItem("cover", 'data:image/jpeg;base64,' + data.cover_picture)
        }

        //Save username in local storage
        usernames.forEach(username => username.textContent = "@" + data.username);
    })
}

viewTweets();
getUserInfo();

