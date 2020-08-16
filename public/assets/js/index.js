// state saver of user
// for this particular page

let state = {};


// global urls
let liveRoot = document.getElementById("live-root").href;
if(liveRoot[liveRoot.length - 1] !== "/") {
    liveRoot += "/";
}

let indexUrl = liveRoot + "index.php";
let loginUrl = liveRoot + "user/login.php";
let signupUrl = liveRoot + "user/signup.php";

let userProfileBaseUrl = liveRoot + "user/profile.php";
let universeViewBaseUrl = liveRoot + "universe/view.php";

let userProfileParamPrefixUrl = userProfileBaseUrl + "?user=";
let universeViewParamPrefixUrl = universeViewBaseUrl + "?universe=";


// pull posts url
let pullPostBaseUrl = liveRoot + "x/post/pull.php";
// pull comments url
let pullCommentBaseUrl = liveRoot + "x/comment/pull.php";
// pull replies url
let pullReplyBaseUrl = liveRoot + "x/reply/pull.php";

// url for update upvote/downvote
let postVoteBaseUrl = liveRoot + "x/post/vote.php";
let commentVoteBaseUrl = liveRoot + "x/comment/vote.php";
let replyVoteBaseUrl = liveRoot + "x/reply/vote.php";

// cell templates
let postCellTemplate = document.getElementById("post-cell-template");
let commentCellTemplate = document.getElementById("comment-cell-template");
let replyCellTemplate = document.getElementById("reply-cell-template");


// form variables
let postFormTitle = document.getElementById("post-form-title");
let postFormDescription = document.getElementById("post-form-description");
let postFormUniverses = document.getElementById("post-form-universes");
let postFormContentErrorMsg = document.getElementById("post-form-content-error-msg");
let postFormUniversesErrorMsg = document.getElementById("post-form-universes-error-msg");


//******************************* utility functions *************************//
// this function resets post form values
// that is, it resets post form items values
// to their default value
function postFormReset() {
    postFormTitle.value = "";
    postFormDescription.value = "";
    postFormUniverses.value = "";
    // resetting error messages
    postFormContentErrorMsg.textContent = "";
    postFormUniversesErrorMsg.textContent = "";
}

function commentFormReset(commentForm) {
    commentForm.querySelector(".comment-form-text").value = "";
}
function replyFormReset(replyForm) {
    replyForm.querySelector(".reply-form-text").value = "";
    replyForm.querySelector(".reply-at-user").textContent = "";
    replyForm.querySelector(".reply-at-id").textContent = "";
}

function voteIconReset(voteIcon, isPost) {
    voteIcon.classList.remove("--kuhu-fill-theme", "--kuhu-stroke-1-8");
    if(!isPost) {
        voteIcon.classList.remove("--kuhu-stroke-theme");
    }
}
function voteIconLightup(voteIcon, isPost) {
    voteIcon.classList.add("--kuhu-fill-theme", "--kuhu-stroke-1-8");
    if(!isPost) {
        voteIcon.classList.add("--kuhu-stroke-theme");
    }
}
// vote icon coloring helper
function voteIconColorHelper(upvoteIcon, downvoteIcon, vote, isPost) {
    if(vote !== "u" && vote != "d") {
        return;
    }

    // check for previous vote
    let prevVote = ""; // default value
    if(upvoteIcon.classList.contains("--kuhu-fill-theme")) {
        prevVote += "u";
    }
    if(downvoteIcon.classList.contains("--kuhu-fill-theme")) {
        prevVote += "d";
    }
    // check for error
    if(prevVote === "ud") {
        console.log("error: both vote icon are enlighted simultaneously");
    }

    // now, determine voteAction
    // i.e. which icon should light up or just switch off
    //      after providing vote
    let voteAction = vote;
    if(prevVote !== "") {
        // when prevVote exists
        if(vote === prevVote) {
            // user undo his/her attempts
            voteAction = "";
        } else {
            // user choose to vote opposite
            voteAction = vote;
        }
    }

    // first reset all icons
    // then just light up the proper one if necessary
    voteIconReset(upvoteIcon, isPost);
    voteIconReset(downvoteIcon, isPost);

    if(voteAction === "u") {
        voteIconLightup(upvoteIcon, isPost);
    }
    if(voteAction === "d") {
        voteIconLightup(downvoteIcon, isPost);
    }
}


//********************************* reply section begins ********************************/
function initializeReplyData(replyData, reply) {
    let replyByNode = replyData.querySelector(".reply-by a");
    replyByNode.textContent = reply["by"];
    replyByNode.href = userProfileParamPrefixUrl + reply["by"];

    let replyDatetimeNode = replyData.querySelector(".reply-datetime");
    replyDatetimeNode.textContent = reply["datetime"];


    let replyTextNode = replyData.querySelector(".reply-text");
    replyTextNode.textContent = reply["text"];

    // showing replyAt if exists
    let replyAtUsername = "";
    // first findout the parent reply node
    let replyAtParentReplyNode = document.getElementById("reply-" + reply["reply-at"]);
    if(replyAtParentReplyNode !== null) {
        replyAtUsername = replyAtParentReplyNode.querySelector(".reply-by a").textContent;
    }
    if(replyAtUsername !== "") {
        let spanNode = document.createElement("span");
        spanNode.textContent = "@" + replyAtUsername;
        spanNode.setAttribute("class", "--kuhu-display-inline --kuhu-padding-right-5px --kuhu-font-700 --kuhu-font-13px");
        replyTextNode.insertAdjacentElement("afterbegin", spanNode);
    }

    let replyVoteNode = replyData.querySelector(".reply-vote-count");
    replyVoteNode.textContent = reply["vote-count"];

    let upvoteIcon = replyData.querySelector(".reply-upvote svg g");
    let downvoteIcon = replyData.querySelector(".reply-downvote svg g");
    voteIconColorHelper(upvoteIcon, downvoteIcon, reply["self-vote"], false);

    let replyUpvoteBtn = replyData.querySelector(".reply-upvote");
    replyUpvoteBtn.addEventListener("click", replyUpvoteBtnClickEvent);

    let replyDownvoteBtn = replyData.querySelector(".reply-downvote");
    replyDownvoteBtn.addEventListener("click", replyDownvoteBtnClickEvent);

    let replyReplyBtn = replyData.querySelector(".reply-reply");
    replyReplyBtn.addEventListener("click", replyReplyBtnClickEvent);
}
function getReplyCell(reply) {
    let replyCell = replyCellTemplate.cloneNode(true);
    replyCell["id"] = "reply-" + reply["id"];

    let replyData = replyCell.querySelector(".reply-data");
    initializeReplyData(replyData, reply);

    return replyCell;
}
function addRepliesToContainer(container, replies, isPulled) {
    //now add replies to reply container
    let replyViewBox = container.querySelector(".reply-container-view-box");
    
    let addedCount = 0;

    for(let reply of replies) {
        if(state["reply"][reply["id"]] === undefined) {
            let replyCell = getReplyCell(reply);
            replyViewBox.appendChild(replyCell);
    
            state["reply"][reply["id"]] = reply;
            state["reply"][reply["id"]]["pulled"] = isPulled;
            // state["comment"][comment["id"]]["isReplyLoaded"] = (replyAddedCount > 0);
            // state["comment"][comment["id"]]["loadedReplyCount"] = replyAddedCount;
            // state["comment"][comment["id"]]["pulledReplyCount"] = ((isPulled) ? replyAddedCount : 0);
            addedCount++;
        }
    }

    // // now, check if more replies left in database or not
    // let replyViewMore = container.querySelector(".reply-container-view-more");
    // // show view more if there are more replies
    // if(replies["hasMore"] === true) {
    //     replyViewMore.hidden = false;
    // } else { // else just hide it
    //     replyViewMore.hidden = true;
    // }

    return addedCount;
}
function pullReplies(commentCell) {
    
    let commentID = commentCell.id.split("-")[1];

    let pullReplyUrl = pullReplyBaseUrl + "?comment-id=" + commentID + "&offset=" + state["comment"][commentID]["pulledReplyCount"];
    

    let xhr = new XMLHttpRequest();
    xhr.open("GET", pullReplyUrl);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let requestHeader = xhr.getResponseHeader("Content-Type");
            console.log("requestHeader: " + requestHeader);
            console.log("responseText: " + xhr.responseText);

            let response = JSON.parse(xhr.responseText);
            
            console.log("request: " + response["request"]);
            
            if(response["request"] === "success") {
                
                console.log("status: " + response["status"]);

                let replies = response["replies"];

                if(response["status"] === "done") {
                    let replyContainer = commentCell.querySelector(".reply-container");

                    let pulledAddedReply = addRepliesToContainer(replyContainer, replies, true);

                    if(pulledAddedReply > 0) {
                        replyContainer.hidden = false;
                    }

                    state["comment"][commentID]["pulledReplyCount"] += pulledAddedReply;
                    state["comment"][commentID]["loadedReplyCount"] += pulledAddedReply;


                    // now decision about showing view more replys or not
                    let replyCountTotal = state["comment"][commentID]["reply-count"];
                    let pulledRepliesTillNow = state["comment"][commentID]["pulledReplyCount"];

                    let viewMoreReplies = commentCell.querySelector(".reply-container-view-more");
                    
                    if(pulledRepliesTillNow < replyCountTotal) {
                        viewMoreReplies.hidden = false;
                    } else {
                        viewMoreReplies.hidden = true;
                    }

                } else {
                    console.log("message: " + response["message"]);
                    
                    console.log("error:");
                    let error = response(["error"]);
                    for(err of error) {
                        console.log(err + ": " + error[err]);
                    }
                }

            } else {
                console.log("message: " + response["message"]);
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-url-encoded");
    xhr.send();
}

// events functions
function replyViewMoreBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let commentCell = target.closest(".comment-cell");
    let commentID = commentCell.id.split("-")[1];

    console.log("action: reply view more btn clicked");
    console.log("comment-id: " + commentID);
    console.log("");

    pullReplies(commentCell);
}
function replyVoteRequestSenderHelper(replyCell, url, vote) {
    let upvoteIcon = replyCell.querySelector(".reply-upvote svg g");
    let downvoteIcon = replyCell.querySelector(".reply-downvote svg g");
    voteIconColorHelper(upvoteIcon, downvoteIcon, vote, false);

    let xhr = new XMLHttpRequest();
    xhr.open("GET", url);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let responseHeader = xhr.getResponseHeader("Content-Type");
            console.log("responseHeader: " + responseHeader);
            console.log("responseText: " + xhr.responseText);

            let response = JSON.parse(xhr.responseText);

            console.log("request: " + response["request"]);
            if(response["request"] === "success") {
                console.log("status: " + response["status"]);

                if(response["status"] === "success") {
                    let vote = response["vote"];
                    console.log("vote-count: " + vote["vote-count"]);
                    console.log("received-vote: " + vote["received-vote"]);

                    let replyVoteNode = replyCell.querySelector(".reply-vote-count");
                    replyVoteNode.textContent = vote["vote-count"];
                } else {
                    console.log("message: " + response["message"]);
                    let error = response["error"];
                    for(let err in error) {
                        console.log(err + ": " + error[err]);
                    }
                }
            } else if(response["request"] === "failure") {
                console.log("message: " + response["message"]);
            } else {
                // authentication error
                window.location = loginUrl;
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-url-encoded");
    xhr.send();
}
function replyUpvoteBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let replyCell = target.closest(".reply-cell");
    // reply id is written as id attribute of reply-cell; format: id="reply-xxx"
    let replyID = replyCell.id.split("-")[1];

    console.log("action: reply upvote btn click");
    console.log("reply id: " + replyID);
    console.log("");

    // now send data to server
    let url = replyVoteBaseUrl + "?vote=u&reply-id=" + replyID;
    replyVoteRequestSenderHelper(replyCell, url, "u");
}
function replyDownvoteBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let replyCell = target.closest(".reply-cell");
    // reply id is written as id attribute of reply-cell; format: id="reply-xxx"
    let replyID = replyCell.id.split("-")[1];

    console.log("action: reply downvote btn click");
    console.log("reply id: " + replyID);
    console.log("");

    // now send data to server
    let url = replyVoteBaseUrl + "?vote=d&reply-id=" + replyID;
    replyVoteRequestSenderHelper(replyCell, url, "d");
}
function replyReplyBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let replyCell = target.closest(".reply-cell");
    // reply id is written as id attribute of reply-cell; format: id="reply-xxx"
    let replyID = replyCell.id.split("-")[1];
    let replyAtUsername = replyCell.querySelector(".reply-by a").textContent;

    console.log("action: reply-reply btn clicked");
    console.log("reply id: " + replyID);
    console.log("reply at: " + replyAtUsername);
    console.log("");

    // now, we must make reply form visible
    let replyUtility = target.closest(".reply-utility");
    let replyForm = replyUtility.querySelector(".reply-form");
    replyForm.hidden = false;
    
    let replyFormText = replyForm.querySelector(".reply-form-text");
    replyFormText.focus();

    let replyAtBox = replyForm.querySelector(".reply-at-box");
    replyAtBox.hidden = false;

    let replyAtUserNode = replyForm.querySelector(".reply-at-user");
    replyAtUserNode.hidden = false;
    replyAtUserNode.textContent = "@" + replyAtUsername;

    let replyAtIDNode = replyForm.querySelector(".reply-at-id");
    replyAtIDNode.textContent = replyID;
}
function replyAddBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let replyForm = target.closest(".reply-form");
    // it's a bit oddly designed
    // it has proper reason
    // first select the box
    // then, select the form itself
    // the box is there, cause we need to hide the
    // form by default, it'll only be shown when
    // reply btn is clicked
    // in future,
    // we'll save the url in global vairiable
    // then, we won't be needing "form"
    // "form" been used only for "form.action" retrieval
    let form = replyForm.querySelector("form");

    // get repy text
    let text = replyForm.querySelector(".reply-form-text").value;
    // get replyAt id
    let replyAtID = replyForm.querySelector(".reply-at-id").textContent;
    if(replyAtID === null || replyAtID === undefined) {
        replyAtID = "";
    }

    let commentCell = target.closest(".comment-cell");
    let commentID = commentCell.id.split("-")[1];

    console.log("action: reply add btn click");
    console.log("text: " + text);
    console.log("comment-id: " + commentID);
    console.log("");

    // now send data to server
    let formData = "";
    formData += "comment-id=" + commentID;
    formData += "&reply-at=" + replyAtID;
    formData += "&reply-form-text=" + text;

    let url = form.action;

    let xhr = new XMLHttpRequest();
    
    xhr.open("POST", url);
    
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let responseHeader = xhr.getResponseHeader("Content-Type");
            console.log("responseHeader: " + responseHeader);
            console.log("responseText: " + xhr.responseText);

            let response = JSON.parse(xhr.responseText);

            console.log(response);

            console.log("request: " + response["request"]);
           
            if(response["request"] === "success") {
                
                console.log("status: " + response["status"]);
                
                if(response["status"] === "done") {
                    
                    let reply = response["reply"];
                    
                    for(field in reply) {
                        console.log(field + ": " + reply[field]);
                    }

                    // now, just reset the reply form value
                    replyFormReset(replyForm);
                    // ok, done

                    let replyContainer = commentCell.querySelector(".reply-container");
                    // let viewMoreRepliesNode = replyContainer.querySelector(".reply-container-view-more");

                    let replies = [ reply ];
                    
                    let createdAddedCount = addRepliesToContainer(replyContainer, replies, false);

                    if(createdAddedCount === 1) {
                        replyContainer.hidden = false;
                        state["comment"][commentID]["loadedReplyCount"] += 1;
                    }

                } else if(response["status"] === "undone") {
                    console.log("message: " + response["message"]);
                    
                    console.log("error: " + response["error"]);

                } else {
                    console.log("message: " + response["message"]);
                    let error = response["error"];
                    for(field in error) {
                        console.log(field + ": " + error[field]);
                    }

                    if(error["reply-form-text-error-msg"] !== undefined || error["reply-form-text-error-msg"] !== null) {
                        let replyFormTextErrorMsg = commentCell.querySelector(".reply-form-text-error-msg");
                        replyFormTextErrorMsg.textContent = error["reply-form-text-error-msg"];
                    }
                }
            } else if(response["request"] === "failure") {
                console.log("message: " + response["message"]);
            } else {
                // authentication failure
                // so, redirect to login page
                window.location = loginUrl;
            }
        }
    };

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(formData);
}
function initializeReplyUtility(replyUtility) {
    // by default reply container is hidden
    // it'll only be shown if and only if
    // replies are added to container
    let replyContainer = replyUtility.querySelector(".reply-container");
    replyContainer.hidden = true;

    // associating reply view more with proper event
    let replyViewMoreBtn = replyUtility.querySelector(".reply-container-view-more");
    replyViewMoreBtn.addEventListener("click", replyViewMoreBtnClickEvent);

    // by default replyForm should always be hidden
    // until reply btn of comment or reply is clicked
    let replyForm = replyUtility.querySelector(".reply-form");
    replyForm.hidden = true;

    // add event listener to reply form add btn
    let replyAddBtn = replyForm.querySelector(".reply-form-btn");
    replyAddBtn.addEventListener("click", replyAddBtnClickEvent);
}

//********************************* comment section begins ********************************/
function initializeCommentData(commentData, comment) {
    let commentByNode = commentData.querySelector(".comment-by a");
    commentByNode.textContent = comment["by"];
    commentByNode.href = userProfileParamPrefixUrl + comment["by"];

    let commentDatetimeNode = commentData.querySelector(".comment-datetime");
    commentDatetimeNode.textContent = comment["datetime"];

    let commentTextNode = commentData.querySelector(".comment-text");
    commentTextNode.textContent = comment["text"];

    let commentVoteNode = commentData.querySelector(".comment-vote-count");
    commentVoteNode.textContent = comment["vote-count"];

    // now color, if upvoted or downvoted
    let upvoteIcon = commentData.querySelector(".comment-upvote svg g");
    let downvoteIcon = commentData.querySelector(".comment-downvote svg g");
    voteIconColorHelper(upvoteIcon, downvoteIcon, comment["self-vote"], false);

    let commentUpvoteBtn = commentData.querySelector(".comment-upvote");
    commentUpvoteBtn.addEventListener("click", commentUpvoteBtnClickEvent);

    let commentDownvoteBtn = commentData.querySelector(".comment-downvote");
    commentDownvoteBtn.addEventListener("click", commentDownvoteBtnClickEvent);

    let commentReplyBtn = commentData.querySelector(".comment-reply");
    commentReplyBtn.addEventListener("click", commentReplyBtnClickEvent);
}
function getCommentCell(comment) {
    let commentCell = commentCellTemplate.cloneNode(true);
    commentCell["id"] = "comment-" + comment["id"];
    
    let commentData = commentCell.querySelector(".comment-data");
    initializeCommentData(commentData, comment);

    let replyUtility = commentCell.querySelector(".reply-utility");
    initializeReplyUtility(replyUtility, comment["replies"]);
    
    return commentCell;
}

function addCommentsToContainer(container, comments, isPulled) {
    let commentViewBox = container.querySelector(".comment-container-view-box");
    
    let addedCount = 0;
    
    for(let comment of comments) {
        if(state["comment"][comment["id"]] === undefined) {
            let commentCell = getCommentCell(comment);
            commentViewBox.appendChild(commentCell);
            
            let replyContainer = commentCell.querySelector(".reply-container");
            let replyAddedCount = 0;
            if(isPulled) {
                replyAddedCount = addRepliesToContainer(replyContainer, comment["replies"], isPulled);
            }

            state["comment"][comment["id"]] = comment;
            state["comment"][comment["id"]]["pulled"] = isPulled;
            state["comment"][comment["id"]]["isReplyLoaded"] = (replyAddedCount > 0);
            state["comment"][comment["id"]]["loadedReplyCount"] = replyAddedCount;
            state["comment"][comment["id"]]["pulledReplyCount"] = ((isPulled) ? replyAddedCount : 0);

            // decision about reply container
            if(replyAddedCount > 0) {
                // i.e. show reply container
                //      if reply is pulled/added
                replyContainer.hidden  = false;
            }

            // decision about reply view more btn
            let pulledRepliesTillNow = state["comment"][comment["id"]]["pulledReplyCount"];
            let replyCountTotal = state["comment"][comment["id"]]["reply-count"];

            let replyViewMore = commentCell.querySelector(".reply-container-view-more");
            if(pulledRepliesTillNow < replyCountTotal) {
                replyViewMore.hidden = false;
            } else {
                replyViewMore.hidden = true;
            }

            addedCount++;
        }
    }
    
    // let commentViewMore = container.querySelector(".comment-container-view-more");
    
    // if(comments["hasMore"] === true) {
    //     commentViewMore.hidden = false;
    // } else {
    //     commentViewMore.hidden = true;
    // }

    return addedCount;
}
function pullComments(postCell) {
    
    let postID = postCell["id"].split("-")[1];

    let pullCommentUrl = pullCommentBaseUrl + "?post-id=" + postID + "&offset=" + state["post"][postID]["pulledCommentCount"];

    // now send a get request to fetch comments
    let xhr = new XMLHttpRequest();
    
    xhr.open("GET", pullCommentUrl);

    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let requestHeader = xhr.getResponseHeader("Content-Type");
            console.log("requestHeader: " + requestHeader);
            console.log("responseText: " + xhr.responseText);

            let response = JSON.parse(xhr.responseText);
            
            console.log("request: " + response["request"]);
            
            if(response["request"] === "success") {
                
                console.log("status: " + response["status"]);

                let comments = response["comments"];

                if(response["status"] === "done") {
                    let commentContainer = postCell.querySelector(".comment-container");

                    let pulledAddedComment = addCommentsToContainer(commentContainer, comments, true);

                    if(pulledAddedComment > 0) {
                        commentContainer.hidden = false;
                    }

                    state["post"][postID]["pulledCommentCount"] += pulledAddedComment;
                    state["post"][postID]["loadedCommentCount"] += pulledAddedComment;


                    // now decision about showing view more comments or not
                    let commentCountTotal = state["post"][postID]["comment-count"];
                    let pulledCommentTillNow = state["post"][postID]["pulledCommentCount"];

                    let viewMoreComments = postCell.querySelector(".comment-container-view-more");
                    
                    if(pulledCommentTillNow < commentCountTotal) {
                        viewMoreComments.hidden = false;
                    } else {
                        viewMoreComments.hidden = true;
                    }

                } else {
                    console.log("message: " + response["message"]);
                    
                    console.log("error:");
                    let error = response(["error"]);
                    for(err of error) {
                        console.log(err + ": " + error[err]);
                    }
                }

            } else {
                console.log("message: " + response["message"]);
            }
        }
    }

    xhr.setRequestHeader("Content-Type", "application/x-www-form-url-encoded");
    xhr.send();
    // just for testing purposes
}

// comment events
function commentViewMoreBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let postCell = target.closest(".post-cell");
    let postID = postCell.id.split("-")[1];

    console.log("action: comment view more btn clicked");
    console.log("post-id: " + postID);
    console.log("");
    // pull comments and then show comments
    
    pullComments(postCell);
}
function commentVoteRequestSenderHelper(commentCell, url, vote) {
    // now update icon color as necessary
    let upvoteIcon = commentCell.querySelector(".comment-upvote svg g");
    let downvoteIcon = commentCell.querySelector(".comment-downvote svg g");
    voteIconColorHelper(upvoteIcon, downvoteIcon, vote, false);

    let xhr = new XMLHttpRequest();
    xhr.open("GET", url);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let responseHeader = xhr.getResponseHeader("Content-Type");
            console.log("responseHeader: " + responseHeader);
            console.log("responseText: " + xhr.responseText);

            let response = JSON.parse(xhr.responseText);

            console.log("request: " + response["request"]);
            if(response["request"] === "success") {
                console.log("status: " + response["status"]);

                if(response["status"] === "success") {
                    let vote = response["vote"];
                    console.log("vote-count: " + vote["vote-count"]);
                    console.log("received-vote: " + vote["received-vote"]);

                    let commentVoteNode = commentCell.querySelector(".comment-vote-count");
                    commentVoteNode.textContent = vote["vote-count"];
                } else {
                    console.log("message: " + response["message"]);
                    let error = response["error"];
                    for(let err in error) {
                        console.log(err + ": " + error[err]);
                    }
                }
            } else if(response["request"] === "failure") {
                console.log("message: " + response["message"]);
            } else {
                // authentication error
                window.location = loginUrl;
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-url-encoded");
    xhr.send();
}
function commentUpvoteBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let commentCell = target.closest(".comment-cell");
    let commentID = commentCell.id.split("-")[1];

    console.log("action: comment upvote btn clicked");
    console.log("comment-id: " + commentID);
    console.log("");

    // now send data to server
    let url = commentVoteBaseUrl + "?vote=u&comment-id=" + commentID;
    commentVoteRequestSenderHelper(commentCell, url, "u");
}
function commentDownvoteBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let commentCell = target.closest(".comment-cell");
    let commentID = commentCell.id.split("-")[1];

    console.log("action: comment downvote btn clicked");
    console.log("comment-id: " + commentID);
    console.log("");

    // now send data to server
    let url = commentVoteBaseUrl + "?vote=d&comment-id=" + commentID;
    commentVoteRequestSenderHelper(commentCell, url, "d");
}
function commentReplyBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let commentCell = target.closest(".comment-cell");
    // comment id is written as id attribute of comment-cell; format: id="comment-xxx"
    let commentID = commentCell.id.split("-")[1];

    console.log("action: comment-reply btn clicked");
    console.log("comment-id: " + commentID);
    console.log("");

    let replyUtility = commentCell.querySelector(".reply-utility");
    replyUtility.hidden = false;

    let replyForm = commentCell.querySelector(".reply-form");
    replyForm.hidden = false;

    let replyFormText = replyForm.querySelector(".reply-form-text");
    replyFormText.focus();

    let replyAtBox = replyForm.querySelector(".reply-at-box");
    replyAtBox.hidden = true;

    let replyAtUserNode = replyForm.querySelector(".reply-at-user");
    replyAtUserNode.hidden = false;
    replyAtUserNode.textContent = "";

    // set replyAtID to empty string
    // note: empty string means reply to comment
    //       not reply to reply
    //       only a reply to a reply contains
    //       replyAtID
    let replyAtIDNode = replyForm.querySelector(".reply-at-id");
    replyAtIDNode.textContent = ""; // empty string
}
function commentAddBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let form = target.closest("form");
    let text = form.querySelector(".comment-form-text").value;
    
    let postCell = target.closest(".post-cell");
    let postID = postCell.id.split("-")[1];

    console.log("action: comment add btn click");
    console.log("text: " + text);
    console.log("post-id: " + postID);
    console.log("");

    // now send data to server
    let formData = "";
    formData += "post-id=" + postID;
    formData += "&comment-form-text=" + text;

    let url = form.action;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let responseHeader = xhr.getResponseHeader("Content-Type");
            console.log("responseHeader: " + responseHeader);
            console.log("responseText: " + xhr.responseText);

            let response = JSON.parse(xhr.responseText);

            console.log(response);

            console.log("request: " + response["request"]);
            if(response["request"] === "success") {
                console.log("status: " + response["status   "]);
                if(response["status"] === "done") {
                    let comment = response["comment"];
                    for(field in comment) {
                        console.log(field + ": " + comment[field]);
                    }

                    // now, just reset the comment form value
                    commentFormReset(form);
                    // ok, done

                    let commentContainer = postCell.querySelector(".comment-container");

                    // make comments array
                    let comments = [ comment ];

                    console.log("comments: ");
                    console.log(comments);

                    // note: here "false", the third parameter denotes that
                    //       this comment is not pulled from server
                    //       rather it is just created
                    let createdAddedCount = addCommentsToContainer(commentContainer, comments, false);
                    
                    if(createdAddedCount === 1) {
                        commentContainer.hidden = false;
                        state["post"][postID]["loadedCommentCount"] += 1;
                    }

                } else if(response["status"] === "undone") {
                    console.log("message: " + response["message"]);
                    
                    console.log("error: " + response["error"]);

                } else {
                    console.log("message: " + response["message"]);
                    let error = response["error"];
                    for(field in error) {
                        console.log(field + ": " + error[field]);
                    }

                    if(error["comment-form-text-error-msg"] !== undefined || error["comment-form-text-error-msg"] !== null) {
                        let commentFormTextErrorMsg = postCell.querySelector(".comment-form-text-error-msg");
                        commentFormTextErrorMsg.textContent = error["comment-form-text-error-msg"];
                    }
                }
            } else if(response["request"] === "failure") {
                
                console.log("message: " + response["message"]);

            } else {
                // authentication failure
                // so, redirect to login page
                window.location = loginUrl;
            }
        }
    };

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(formData);
}
function initializeCommentUtility(commentUtility) {
    // by default comment utility should be hidden
    // it should be shown/toggled only when post-comment(of post) btn is clicked
    commentUtility.hidden = true;
    
    // comment container would be hidden if no comment is made
    // it'll only be shown if comment(s) are added to container
    let commentContainer = commentUtility.querySelector(".comment-container");
    commentContainer.hidden = true;

    // associating comment view more btn with proper event
    let commentViewMoreBtn = commentUtility.querySelector(".comment-container-view-more");
    commentViewMoreBtn.addEventListener("click", commentViewMoreBtnClickEvent);

    // note: we don't need to hide comment form like we did
    //       in reply. Cause when post-comment btn is clicked
    //       we're going to show both comments and as well as
    //       comment form
    let commentAddBtn = commentUtility.querySelector(".comment-form-btn");
    commentAddBtn.addEventListener("click", commentAddBtnClickEvent);
}

//********************************* post section begins ********************************/
function preparePostDescription(descriptionNode, descriptionBlocks) {
    // create description blocks
    for(let blockContent of descriptionBlocks) {

        // assuming all the contents are texts
        let descriptionBlock = document.createElement("div");

        descriptionBlock.setAttribute("class", "post-description-block --kuhu-white-space-pre-wrap --kuhu-margin-bottom-4px");

        descriptionBlock.textContent = blockContent;
        
        descriptionNode.appendChild(descriptionBlock);
    }
}
function preparePostUniverses(universesNode, universes) {
    // creating universes node to display all universes properly
    let addSeperatorPrefix = false;
    for(let universe of universes) {
        let universeNode = document.createElement("div");
        let universeNodeLink = document.createElement("a");
        
        universeNodeLink.textContent = universe;
        universeNodeLink.setAttribute("href", universeViewParamPrefixUrl + universe);
        universeNodeLink.setAttribute("class", "--kuhu-color-app-general --kuhu-hover-text-decoration-underline --kuhu-hover-color-theme");

        if(addSeperatorPrefix) {
            let commaNode = document.createElement("span");
            commaNode.textContent = ", ";
            universeNode.appendChild(commaNode);
        }
        universeNode.appendChild(universeNodeLink);
        universesNode.appendChild(universeNode);
        if(addSeperatorPrefix === false) {
            addSeperatorPrefix = true;
        }
    }
}
function initializePostData(postData, post) {
    let postByNode = postData.querySelector(".post-by a");
    postByNode.textContent = post["by"];
    postByNode.href = userProfileParamPrefixUrl + post["by"];

    let postDatetimeNode = postData.querySelector(".post-datetime");
    postDatetimeNode.textContent = post["datetime"];

    let postTitleNode = postData.querySelector(".post-title");
    postTitleNode.textContent = post["title"];

    let postDescriptionNode = postData.querySelector(".post-description");
    preparePostDescription(postDescriptionNode, post["descriptionBlocks"]);

    let postUniversesNode = postData.querySelector(".post-universes");
    preparePostUniverses(postUniversesNode, post["universes"]);

    let postVoteNode = postData.querySelector(".post-vote-count");
    postVoteNode.textContent = post["vote-count"];

    // now color, if upvoted or downvoted
    let upvoteIcon = postData.querySelector(".post-upvote svg g");
    let downvoteIcon = postData.querySelector(".post-downvote svg g");
    voteIconColorHelper(upvoteIcon, downvoteIcon, post["self-vote"], true);

    let postCommentNode = postData.querySelector(".post-comment-count");
    postCommentNode.textContent = post["comment-count"];

    // note: .post-share-count is a bit different
    //       to know the reason consult template file
    let postShareNode = postData.querySelector(".--kuhu-post-share-count");
    postShareNode.textContent = post["share-count"];


    let postUpvoteBtn = postData.querySelector(".post-upvote");
    postUpvoteBtn.addEventListener("click", postUpvoteBtnClickEvent);

    let postDownvoteBtn = postData.querySelector(".post-downvote");
    postDownvoteBtn.addEventListener("click", postDownvoteBtnClickEvent);

    let postCommentBtn = postData.querySelector(".post-comment");
    postCommentBtn.addEventListener("click", postCommentBtnClickEvent);
}
function getPostCell(post) {
    let postCell = postCellTemplate.cloneNode(true);
    postCell["id"] = "post-" + post["id"];

    let postData = postCell.querySelector(".post-data");
    initializePostData(postData, post);
    
    let commentUtility = postCell.querySelector(".comment-utility");
    initializeCommentUtility(commentUtility);
    
    return postCell;
}
function addPostToContainerTop(container, post) {
    // this function add a post to container top
    // i.e. as the first post
    let postViewBox = container.querySelector(".post-container-view-box");

    let addCount = 0;
    
    if(state["post"][post["id"]] === undefined) {
        let postCell = getPostCell(post);
        postViewBox.insertAdjacentElement("afterbegin", postCell);
        
        state["post"][post["id"]] = post;
        state["post"][post["id"]]["pulled"] = false;
        state["post"][post["id"]]["isCommentLoaded"] = false;
        state["post"][post["id"]]["loadedCommentCount"] = 0;
        state["post"][post["id"]]["pulledCommentCount"] = 0;

        addCount++;
    }

    return addCount;
}
function addPostsToContainer(container, posts) {
    let postViewBox = container.querySelector(".post-container-view-box");
    
    let addCount = 0;

    let blockedByCreatedPost = 0;

    for(let post of posts) {
        if(state["post"][post["id"]] === undefined) {
            let postCell = getPostCell(post);
            postViewBox.appendChild(postCell);
            
            state["post"][post["id"]] = post;
            state["post"][post["id"]]["pulled"] = true;
            state["post"][post["id"]]["isCommentLoaded"] = false;
            state["post"][post["id"]]["loadedCommentCount"] = 0;
            state["post"][post["id"]]["pulledCommentCount"] = 0;
            
            addCount++;
        } else {
            if(state["post"][post["id"]]["pulled"] ===  false) {
                blockedByCreatedPost++;
            }
        }
    }

    // let postViewMore = container.querySelector(".post-container-view-more");

    // if(posts["hasMore"] === true) {
    //     postViewMore.hidden = false;
    // } else {
    //     postViewMore.hidden = true;
    // }

    // if all pulled posts are same as newly created posts
    // then, we don't need to assume that posts in database are finished
    // so, return a number -1 which
    // indicates that pulledposts are blocked by created post
    if(posts.length != 0 && blockedByCreatedPost === posts.length) {
        return -1;
    }

    return addCount;
}
function pullPosts(filterString) {
    // if nothing to load, then just return
    if(state["noNewPostToLoad"] === true) {
        return;
    }

    let url = pullPostBaseUrl + filterString;
    
    let xhr = new XMLHttpRequest();
    xhr.open("GET", url);
    xhr.onreadystatechange = function() {
        // do something when response returned from server
        if(xhr.readyState === 4 && xhr.status === 200) {
            let responseHeader = xhr.getResponseHeader("Content-Type");
            console.log("response header: " + responseHeader);
            console.log("resoponseText: " + xhr.responseText);

            response = JSON.parse(xhr.responseText);
            
            console.log("reuqest: " + response["request"]);
            if(response["request"] === "success") {

                console.log("status: " + response["status"]);
                
                if(response["status"] === "done") {
                    // posts = JSON.parse(response["posts"]);
                    let posts = response["posts"];

                    let postContainer = document.getElementById("post-container");
                    let postViewMore = postContainer.querySelector(".post-container-view-more");

                    let pulledAddedCount = addPostsToContainer(postContainer, posts);

                    // check for special case
                    // which is:
                    // if all pulled posts are blocked by
                    // newly created posts of this user
                    if(pulledAddedCount === -1) {
                        // just simply return
                        // no need to change anything
                        return;
                    }

                    if(pulledAddedCount === 0) {
                        if(state["isPostLoaded"] === false) {
                            displayNoPostMsg(postContainer);
                        } else {
                            postViewMore.hidden = true;
                            state["noNewPostToLoad"] = true;
                        }
                    } else {
                        postViewMore.hidden = false;
                        if(state["isPostLoaded"] === false) {
                            let noPostMsg = postContainer.querySelector(".no-post-msg");
                            if(noPostMsg !== null && noPostMsg !== undefined) {
                                container.removeChild(noPostMsg);
                            }
                            state["isPostLoaded"] = true;
                        }
                        state["loadedPostCount"] += pulledAddedCount;
                        state["pulledPostCount"] += pulledAddedCount;
                    }

                } else {
                    console.log("message: " + response["message"]);
                    
                    console.log("error: " + response["error"]);
                }

            } else {
                console.log("request: " + response["request"]);
                console.log("message: " + response["message"]);
            }
        }
    };

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send();
}

// post events
function postViewMoreBtnClickEvent(event) {
    event.preventDefault();

    // pull posts and then show posts
    let filterString = preparePullPostFilterString();
    pullPosts(filterString);
}
function postVoteRequestSenderHelper(postCell, url, vote) {
    // check for previous vote
    let upvoteIconNode = postCell.querySelector(".post-upvote svg g");
    let downvoteIconNode = postCell.querySelector(".post-downvote svg g");
    // handle icon colors for newly created vote action
    voteIconColorHelper(upvoteIconNode, downvoteIconNode, vote, true);

    // now send request
    let xhr = new XMLHttpRequest();
    xhr.open("GET", url);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let responseHeader = xhr.getResponseHeader("Content-Type");
            console.log("responseHeader: " + responseHeader);
            console.log("responseText: " + xhr.responseText);

            let response = JSON.parse(xhr.responseText);

            console.log("request: " + response["request"]);
            if(response["request"] === "success") {
                console.log("status: " + response["status"]);

                if(response["status"] === "success") {
                    let vote = response["vote"];
                    console.log("vote-count: " + vote["vote-count"]);
                    console.log("received-vote: " + vote["received-vote"]);
                    console.log("post-id: " + vote["post-id"]);

                    let postVoteNode = postCell.querySelector(".post-vote-count");
                    postVoteNode.textContent = vote["vote-count"];
                } else {
                    console.log("message: " + response["message"]);
                    let error = response["error"];
                    for(let err in error) {
                        console.log(err + ": " + error[err]);
                    }
                }
            } else if(response["request"] === "failure") {
                console.log("message: " + response["message"]);
            } else {
                // authentication error
                window.location = loginUrl;
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-url-encoded");
    xhr.send();
}
function postUpvoteBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let postCell = target.closest(".post-cell");
    let postID = postCell.id.split("-")[1];

    console.log("action: post upvote btn click");
    console.log("post id: " + postID);
    console.log("");

    // now send data to server

    let url = postVoteBaseUrl + "?post-id=" + postID + "&vote=u";
    postVoteRequestSenderHelper(postCell, url, "u");
}
function postDownvoteBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let postCell = target.closest(".post-cell");
    let postID = postCell.id.split("-")[1];

    console.log("action: post downvote btn click");
    console.log("post id: " + postID);
    console.log("");

    // now send data to server
    let url = postVoteBaseUrl + "?post-id=" + postID + "&vote=d";
    postVoteRequestSenderHelper(postCell, url, "d");
}
function postCommentBtnClickEvent(event) {
    event.preventDefault();

    let target = event.target;
    let postCell = target.closest(".post-cell");
    // post id is written as id attribute of post-cell; format: id="post-xxx"
    let postID = postCell.id.split("-")[1];

    console.log("action: post's comment btn clicked");
    console.log("post id: " + postID);
    console.log("");

    let commentUtility = postCell.querySelector(".comment-utility");
    commentUtility.hidden = !commentUtility.hidden;

    if(commentUtility.hidden === true) {
        return;
    }

    let commentForm = postCell.querySelector(".comment-form");

    let commentFormText = commentForm.querySelector(".comment-form-text");
    commentFormText.focus();

    // and also if no comment is loaded yet
    // send request to server and add some comments in the container
    if(state["post"][postID]["isCommentLoaded"] === false) {
        pullComments(postCell);
        state["post"][postID]["isCommentLoaded"] = true;
    }
}
function postAddBtnClickEvent(event) {
    event.preventDefault();

    console.log("create post btn clicked");

    let title = postFormTitle.value;
    let description = postFormDescription.value;
    let universes = postFormUniverses.value;

    title = title.trim();
    description = description.trim();
    universes = universes.trim();
    if(title === "" && description === "") {
        document.getElementById("post-form-content-error-msg").textContent = "both title and description cannot be empty";
        return;
    }

    // preparing i.e. urlencoding form data to send
    let formData = "";
    formData += "post-form-title=" + title;
    formData += "&post-form-description=" + description;
    formData += "&post-form-universes=" + universes;

    // now we've to send the data to the server
    let form = document.getElementById("post-form");
    // console.log(form.action);
    let url = form.action; // retrieving url, where to send data
    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            let responseHeader = xhr.getResponseHeader("Content-Type");
            console.log("response header: " + responseHeader);
            console.log("resoponseText: " + xhr.responseText);
            
            response = JSON.parse(xhr.responseText);
            // console.log(response);
            if(response["request"] === "success") {
                if(response["status"] === "done") {
                    // so, post has been created successfully
                    // now, we may clear textarea(s) of post form
                    postFormReset();

                    // showing in console
                    console.log("done");
                    
                    let post = response["post"];
                    
                    // add post to container
                    let postContainer = document.getElementById("post-container");
                    
                    let createdAddedCount = addPostToContainerTop(postContainer, post);

                    if(createdAddedCount === 1) {
                        if(state["isPostLoaded"] === false) {
                            let noPostMsg = postContainer.querySelector(".no-post-msg");
                            if(noPostMsg !== undefined && noPostMsg !== null) {
                                postContainer.removeChild(noPostMsg);
                            }
                            state["isPostLoaded"] = true;
                        }
                        
                        state["loadedPostCount"]++;

                    } else {
                        console.log("error: couldn't add post to client browser");
                    }

                } else if(response["status"] === "undone") {
                    console.log("message: " + response["message"]);
                    console.log("error: " + response["error"]);
                } else {
                    // showing errors in console
                    let error = JSON.parse(response["error"]);
                    console.log("error:");
                    for(err in error) {
                        console.log(err + ": " + error[err]);
                    }
                    
                    // showing error msg in html forms
                    if(error.postFormContentErrorMsg !== null && error.postFormContentErrorMsg !== undefined) {
                        document.getElementById("post-form-content-error-msg").textContent = error["postFormContentErrorMsg"];
                    }
                    if(error.postFormUniversesErrorMsg !== null && error.postFormUniversesErrorMsg !== undefined) {
                        document.getElementById("post-form-universes-error-msg").textContent = error["postFormUniversesErrorMsg"];
                    }
                }
            } else {
                console.log("invalid request type");
            }
        }
    };

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(formData);
}

// this message is displays if there are no posts
function displayNoPostMsg(postContainer) {
    let noPostMsgNode = document.createElement("div");
    noPostMsgNode.textContent = "No posts yet...";
    noPostMsgNode.setAttribute("class", "no-post-msg --kuhu-padding-top-32px --kuhu-padding-bottom-32px --kuhu-border-bottom-3px --kuhu-border-dark --kuhu-text-center --kuhu-font-24px --kuhu-font-500 --kuhu-font-italic");
    postContainer.appendChild(noPostMsgNode);
}

function initializePostUtility(postUtility) {
    // we are always showing post form
    // so, nothing to do with that
    // but, we need to add click event listener
    // to post Add btn
    
    // adding proper event to post view more btn
    let postViewMoreBtn = postUtility.querySelector(".post-container-view-more");
    postViewMoreBtn.addEventListener("click", postViewMoreBtnClickEvent);
    postViewMoreBtn.hidden = true;

    // note: if user doesn't login, then
    //       post form wouldn't be sent to client
    //       and in that case postAddBtn would be
    //       null or undefined
    // let postAddBtn = postUtility.querySelector(".post-form-btn");
    let postAddBtn = document.getElementById("post-form-btn");
    if(postAddBtn !== null && postAddBtn !== undefined) {
        postAddBtn.addEventListener("click", postAddBtnClickEvent);
    }
}

//************************************ Handling Events ****************************************//
function preparePullPostFilterString() {
    let url = window.location.toString();
    
    if(url === liveRoot || url === indexUrl) {
        return "";
    }
    if(url.indexOf(userProfileBaseUrl) === 0) {
        let filterType = "user";
        let filterValue = url.split(userProfileParamPrefixUrl)[1];
        // if user in his own page, then it may not contain any
        // parameter
        if(filterValue === null || filterValue === undefined) {
            filterValue = "";
        }
        return "?" + filterType + "=" + filterValue + "&offset=" + state["pulledPostCount"];
    }
    if(url.indexOf(universeViewBaseUrl) === 0) {
        let filterType = "universe";
        let filterValue = url.split(universeViewParamPrefixUrl)[1];
        return "?" + filterType + "=" + filterValue;
    }
    // if page not recognized
    return "?-=-";
}

function windowLoadPullPostEvent(event) {
    event.preventDefault();
    // pull posts event when window is fully loaded
    // note: pull post has the responsibility to add data in post container
    //       cause, pull post is sends asynchronous request
    //       so, the data would be recieved asynchronously
    let pullPostFilterString = preparePullPostFilterString();
    pullPosts(pullPostFilterString);
}

function initApp() {
    state["isPostLoaded"] = false;
    state["loadedPostCount"] = 0;
    state["pulledPostCount"] = 0;
    state["noNewPostToLoad"] = false;
    state["post"] = {};
    state["comment"] = {};
    state["reply"] = {};

    let postUtility = document.getElementById("post-utility");
    // note: postutility is not show in all pages
    //       right now, index i.e. home, user/profile.php and
    //       universes/view.php are only pages those contains
    //       postUtility
    if(postUtility !== null && postUtility !== undefined) {
        initializePostUtility(postUtility);
        window.addEventListener("load", windowLoadPullPostEvent);
    }
}

initApp();