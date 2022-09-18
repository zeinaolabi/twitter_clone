const getFollowersAPI = "http://localhost/twitter_test/get_followers.php?user_id=" + localStorage.getItem("userID").toString();
const getFollowingsAPI = "http://localhost/twitter_test/get_followings.php?user_id=" + localStorage.getItem("userID").toString();
const userResult = document.getElementById("user_result");
const followersButton = document.getElementById("followers_button");
const followingButton = document.getElementById("following_button");
const blockedButton = document.getElementById("blocked_btn");
const homeButton = document.getElementById("home_button");
const profileButton = document.getElementById("profile_button");

//When the user clicks on the home button, go to feed page
homeButton.onclick = function() {
    window.location.replace("feed_page.html");
}

//When the user clicks on the profile button, redirect to profile page
profileButton.onclick = function() {
    window.location.replace("profile_page.html");
}

const getFollowers = () => {
    fetch(getFollowersAPI)
    .then(response=>response.json())
    .then(data => {
        //Check if there's an error
        if(data.message !== undefined){
            //Do nothing
            return
        }

        for(let i = 0; i < Object.keys(data).length; i++){
            //Create a list item and add it results
            let li = document.createElement("li");
            li.appendChild(document.createTextNode(data[i].username));
            li.id = data[i].user_id
            userResult.innerHTML = '';
            userResult.appendChild(li);
        }
    })
}

const getFollowings = () => {
    fetch(getFollowingsAPI)
    .then(response=>response.json())
    .then(data => {
        //Check if there's an error
        if(data.message !== undefined){
            //Do nothing
            return
        }

        for(let i = 0; i < Object.keys(data).length; i++){
            console.log(data)
            console.log(data[i].username)
            //Create a list item and add it results
            let li = document.createElement("li");
            li.appendChild(document.createTextNode(data[i].username));
            li.id = data[i].user_id
            userResult.innerHTML = '';
            userResult.appendChild(li);
        }
    })
}

followersButton.addEventListener("click", getFollowers)
followingButton.addEventListener("click", getFollowings)
blockedButton.addEventListener("click", getFollowers)