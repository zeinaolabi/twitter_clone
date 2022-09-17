const getTweetsAPI = "http://localhost/twitter_test/get_tweets.php"


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
           
            //Add div after the original tweet
            originalTweet.after(clone);
        }
    })
}