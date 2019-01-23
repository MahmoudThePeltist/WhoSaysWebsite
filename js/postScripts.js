
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
    var commentHTML = '<div class="commentContainer"><div class="commentUserImageSpace">'+
      '<img class="userImage commentUserImage" src="'+userImageSrc+'"></div>'+
      '<div class="commentContainerContainer"><div class="commentUserName">'+userNameValue+'</div>'+
      '<div class="commentDate">just now</div><br><hr><div class="commentText"><b>'+commentTextValue+
      '</b></div></div></div>';
    $(document).ready(function(){
      $.ajax({
         url: 'postComment.php',
         type: 'POST',
         data: {
           postIdCom: postIdValue,
           userIdCom: userIdValue,
           commentText: commentTextValue,
         }
       });
    });
    commentsHTML = commentHTML + commentsHTML;
    document.getElementById(commentsId).innerHTML = commentsHTML;
    document.getElementById(inputId).value = "";
  }
}

//this needs to be a global variable so we can save the likes and dislikes
var likeFlags = [];
function addPoint(e){
  setCookie("", cvalue, exdays);
  textElement = e.target.children[0];
  value = textElement.innerHTML;
  var btnTypeVar = e.target.id;
  var postIdVar = e.target.value;
  var btnNameVar = e.target.name;
  var flagNumber = postIdVar + "" + btnNameVar;
  //New values
  if(!likeFlags[flagNumber]){
    textElement.innerHTML = ++value;
    var newValueVar = "add";
    likeFlags[flagNumber] = 1;
    e.target.style.filter = "invert(100%)";
  } else {
    textElement.innerHTML = --value;
    var newValueVar = "remove";
    likeFlags[flagNumber] = 0;
    e.target.style.filter = "invert(0%)";
  }
  //ajax operation
  $(document).ready(function(){
    $.ajax({
       url: 'emoticon.php',
       type: 'POST',
       data: {
         changeType: newValueVar,
         btnType: btnTypeVar,
         postId: postIdVar,
       }
     });
  });
 }

function generatePosts(postsArray){
  var posts = "";// <= Variable to hold all the posts
  for(var i=0;i<postsArray.length;i++){
    thisPost = postsArray[i];
    //create post comments html
    var comments = "";// <= Variable to hold all the comments for this post
    for(var j=0;j<thisPost[8].length;j++){
      //this comment variables
      var thisComment = thisPost[8];
      //create comment HTML
      console.log("creating comment:" + thisComment[j]);
      var comment = ''+
        '<div class="commentContainer">'+
          '<div class="commentUserImageSpace">'+
            '<img class="userImage commentUserImage" src="'+thisComment[j][1]+'">'+
          '</div>'+
          '<div class="commentContainerContainer">'+
            '<div class="commentUserName">'+thisComment[j][2]+'</div>'+
            '<div class="commentDate">'+thisComment[j][0]+'</div><br>'+
            '<hr><div class="commentText"><b>'+thisComment[j][3]+'</b></div>'+
          '</div>'+
        '</div>';
      //add this comment to comments list
      comments = comment + comments;
    }
    //Create trashcan HTML if allowed
    if(thisPost[4]){
      trashCan = ''+
      '<form method="POST">'+
        '<button class="trashBtn" name="trashBtn" value="' + thisPost[0] + '">'+
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
            '<a href= "profile.php?username=' + thisPost[3] + '">'+
              '<img class="userImage" src="' + thisPost[2] + '">'+
            '</a>'+
          '</div>'+
          trashCan +
          '<a href= "profile.php?username=' + thisPost[3] + '">'+
            '<h2 class="postUserName">' + thisPost[3] + ' Posted:</h2>'+
          '</a>'+
          '<h3 class="categoryLabelInPost">' + thisPost[5] + '</h3>'+
          '<h3>' + thisPost[1] + '</h3>'+
        '</div>'+
        '<div class="postText">'+
          '<p>' + thisPost[6] + '</p>'+
        '</div>'+
        '<div class="buttonHolder">'+
          '<button class="emotiBtns" id="likes" name="1" value="'+ thisPost[0] +'">👍'+
            '<sup class="emotiBtnText">' + thisPost[7][0] + '</sup>'+
          '</button>'+
          '<button class="emotiBtns" id="hates" name="2" value="'+  thisPost[0] +'">👎'+
            '<sup class="emotiBtnText">' + thisPost[7][1] + '</sup>'+
          '</button>'+
          '<button class="emotiBtns" id="angers" name="3" value="'+ thisPost[0] +'">🙊'+
            '<sup class="emotiBtnText">' + thisPost[7][2] + '</sup>'+
          '</button>'+
          '<button class="emotiBtns" id="deads" name="4" value="'+ thisPost[0] +'">🤣'+
            '<sup class="emotiBtnText">' + thisPost[7][3] + '</sup>'+
          '</button>'+
          '<button class="commentBtn" name="commentBtn" name="5" value="'+ thisPost[0] +'">'+
            '💬'+
          '</button>'+
        '<div class="commentsSection" id="commentsSect'+ thisPost[0] +'">'+
          '<div class="commentsCreation">'+
            '<input class="commentInput" id="commentInput'+ thisPost[0] +'" type="input" required>'+
            '<button class="commentSubmitBtn" id="submitComment'+ thisPost[0] +'" value="'+ thisPost[0] +'" name="submitComment">Submit</button>'+
          '</div>'+
          '<div id="commentsHolder'+ thisPost[0] +'">'+
            comments +
          '</div>'+
        '</div>'+
      '</div>'+
    '</div>';
    //add this post to post list so that it's at the top of
    //a new post list which is added to the feed.
    posts = post + posts;
   }
   //add the created html to page
   document.getElementById("postsHolderID").innerHTML = posts;
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
