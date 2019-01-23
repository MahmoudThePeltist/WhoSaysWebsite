
function toggleComments(e){
  commentsBtnId = e.target.value;
  commentsId = "commentsSect" + commentsBtnId;
  commentsDisplay = document.getElementById(commentsId).style.display;
  if(commentsDisplay == 'none'){
    document.getElementById(commentsId).style.display = 'block';
  } else {
    document.getElementById(commentsId).style.display = 'none';
  }
}

function submitComment(e){
  var postIdValue = e.target.value;
  var inputId = "commentInput" + postIdValue;
  var userIdValue = dataUserID;
  var userNameValue = dataUserName;
  var userImageSrc = dataUserImageSrc;
  var commentTextValue = document.getElementById(inputId).value;
  //get all the comments
  var commentsId = "commentsHolder" + postIdValue;
  var commentsHTML = document.getElementById(commentsId).innerHTML;
  if(commentTextValue == ""){
    alert("please enter a comment :)");
  }
  else{
    var commentHTML = buildCommentHtml(userImageSrc,userNameValue,"Just now",commentTextValue);
    $(document).ready(function(){
      $.ajax({
         url: 'postComment.php',
         type: 'POST',
         data: {
           postIdCom: postIdValue,
           userIdCom: userIdValue,
           commentText: commentTextValue,
         },
         success: function(resp){
           console.log(resp);
           commentsHTML = commentHTML + commentsHTML;
           document.getElementById(commentsId).innerHTML = commentsHTML;
           document.getElementById(inputId).value = "";
         },
         error: function(resp){
           console.log(resp);
         },
       });
    });
  }
}

function addPoint(e){
  //get the button's current value for the JS part
  textElement = e.target.children[0];
  value = textElement.innerHTML;
  //get the post id and button name for the AJAX part
  var postIdVar = e.target.value;
  var btnTypeVar = e.target.name;
  //ajax operation to record the operation
  $(document).ready(function(){
    $.ajax({
       url: 'emoticon.php',
       type: 'POST',
       data: {
         userId: dataUserID,
         postId: postIdVar,
         btnType: btnTypeVar,
       },
       success: function(resp){
         console.log(resp);
         if(resp == 1){
           console.log("adding");
           textElement.innerHTML = ++value;
           e.target.style.filter = "invert(100%)";
         } else if(resp == 0) {
           console.log("removing");
           textElement.innerHTML = --value;
           e.target.style.filter = "invert(0%)";
         }
       },
       error: function(resp){
         console.log("Error :( " + resp);
       },
     });
  });
 }

function setEmotis(btnIdArray, emotiArray){
  for(var i=0;i<btnIdArray.length;i++){
    for(var j=0;j<btnIdArray[i].length;j++){
      console.log(emotiArray[i][j]);
      if(emotiArray[i][j] == 1){
        document.getElementById(btnIdArray[i][j]).style.filter = "invert(100%)";
      } else {
        document.getElementById(btnIdArray[i][j]).style.filter = "invert(0%)";
      }
    }
  }
}


function generatePosts(postsArray){
  var posts = "";// <= Variable to hold all the posts
  var btnIdArrays = [];// <= variable to hold arrays of the ids of all the emoti btns
  var btnEmotisArrays = [];// <= variable to hold the emotis arrays of all the btns
  for(var i=0;i<postsArray.length;i++){
    thisPost = postsArray[i];
    //create post comments html
    var comments = "";// <= Variable to hold all the comments for this post
    for(var j=0;j<thisPost[8].length;j++){
      //this comment variables
      var thisComment = thisPost[8];
      //create comment HTML\
      var comment = buildCommentHtml(thisComment[j][1],thisComment[j][2],thisComment[j][0],thisComment[j][3]);
      //add this comment to comments list
      comments = comment + comments;
    }
    //collect array of btn ids
    var btnIdArray = ['likes'+thisPost[0],'hates'+thisPost[0],'angers'+thisPost[0],'deads'+thisPost[0]];
    //save the arrrays
    btnIdArrays[i] = btnIdArray;
    btnEmotisArrays[i] = thisPost[9];
    //add this post to post list so that it's at the top of
    //a new post list which is added to the feed.
    post = buildPostHtml(thisPost[4], thisPost[0], thisPost[2], thisPost[3], thisPost[5], thisPost[1], thisPost[6], thisPost[7], comments)
    posts = post + posts;
   }
   //add the created html to page
   document.getElementById("postsHolderID").innerHTML = posts;
   //set the emoti btns based on the user's history
   setEmotis(btnIdArrays, btnEmotisArrays);
   //NEXT WE ADD JAVASCRIPT EVENT LISTENERS TO THE NEW HTML CODE
   //Reaction buttons:
   var emotiBtns = document.getElementsByClassName("emotiBtns");
   for(let i = 0; i < emotiBtns.length; i++){
     emotiBtns[i].addEventListener("click",addPoint);
   }
   //comment section open:
   var commentButtons = document.getElementsByClassName("commentBtn");
   for(i=0;i<commentButtons.length;i++){
     commentButtons[i].addEventListener("click",toggleComments);
     commentsId = "commentsSect" + commentButtons[i].value;
     document.getElementById(commentsId).style.display = 'none';
   }
   //comment submit handling:
   var commentSubmitBtns = document.getElementsByClassName("commentSubmitBtn");
   for(i=0;i<commentSubmitBtns.length;i++){
     commentSubmitBtns[i].addEventListener("click",submitComment);
   }

 }

function buildCommentHtml(commentImgSrc,commentUserName,commentDate,commentText){
   commentHtml = '' +
   '<div class="commentContainer">'+
   '<div class="commentUserImageSpace">'+
   '<img class="userImage commentUserImage" src="'+commentImgSrc+'">'+
   '</div>'+
   '<div class="commentContainerContainer">'+
   '<div class="commentUserName">'+commentUserName+'</div>'+
   '<div class="commentDate">'+commentDate+'</div><br>'+
   '<hr><div class="commentText"><b>'+commentText+'</b></div>'+
   '</div>'+
   '</div>';
   return commentHtml;
 }

function buildPostHtml(trashCan,postId,userImageSrc,userName, postCategory, postDate, postText, emotiArray, comments){

  //Create trashcan HTML if allowed
  if(trashCan){
    trashCan = ''+
    '<form method="POST">'+
      '<button class="trashBtn" name="trashBtn" value="' + postId + '">'+
        '<i class="fa fa-trash"></i>'+
      '</button>'+
    '</form>';
  } else {
    trashCan = '';
  }
  //create post HTML
  var post = ''+
    '<div class="postHolder">'+
      '<div class="postTitle">'+
        '<div class="postUserImageSpace">'+
          '<a href= "profile.php?username=' + userName + '">'+
            '<img class="userImage" src="' + userImageSrc + '">'+
          '</a>'+
        '</div>'+
        trashCan +
        '<a href= "profile.php?username=' + userName + '">'+
          '<h2 class="postUserName">' + userName + ' Posted:</h2>'+
        '</a>'+
        '<h3 class="categoryLabelInPost">' + postCategory + '</h3>'+
        '<h3>' + postDate + '</h3>'+
      '</div>'+
      '<div class="postText">'+
        '<p>' + postText + '</p>'+
      '</div>'+
      '<div class="buttonHolder">'+
        '<button class="emotiBtns" id="likes'+postId+'" name="likes" value="'+ postId +'">👍'+
          '<sup class="emotiBtnText">' + emotiArray[0] + '</sup>'+
        '</button>'+
        '<button class="emotiBtns" id="hates'+postId+'" name="hates" value="'+ postId +'">👎'+
          '<sup class="emotiBtnText">' + emotiArray[1] + '</sup>'+
        '</button>'+
        '<button class="emotiBtns" id="angers'+postId+'" name="angers" value="'+ postId +'">🙊'+
          '<sup class="emotiBtnText">' + emotiArray[2] + '</sup>'+
        '</button>'+
        '<button class="emotiBtns" id="deads'+postId+'" name="deads" value="'+ postId +'">🤣'+
          '<sup class="emotiBtnText">' + emotiArray[3] + '</sup>'+
        '</button>'+
        '<button class="commentBtn" name="commentBtn" name="5" value="'+ postId +'">'+
          '💬'+
        '</button>'+
      '<div class="commentsSection" id="commentsSect'+ postId +'">'+
        '<div class="commentsCreation">'+
          '<input class="commentInput" id="commentInput'+ postId +'" type="input" required>'+
          '<button class="commentSubmitBtn" id="submitComment'+ postId +'" value="'+ postId +'" name="submitComment">Submit</button>'+
        '</div>'+
        '<div id="commentsHolder'+ postId +'">'+
          comments +
        '</div>'+
      '</div>'+
    '</div>'+
  '</div>';
  return post;
 }
