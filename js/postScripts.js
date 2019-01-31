function showBox(boxId,backgroundId){
  document.getElementById(boxId).style.display = 'block';
  for (var i=0; i<100;i++){
    setTimeout(function(){document.getElementById(boxId).style.opacity = i/100},100);
    document.getElementById(backgroundId).style.display = 'block';
    document.getElementById(backgroundId).addEventListener("click", function(){hideBox(boxId,backgroundId);});
  }
}

function hideBox(boxId,backgroundId){
    document.getElementById(boxId).style.display = 'none';
    document.getElementById(boxId).style.opacity = 0;
    document.getElementById(backgroundId).style.display = 'none';
    inputActive = false;
}

function toggleComments(e){
  commentsBtnId = e.currentTarget.value;
  commentsId = "commentsSect" + commentsBtnId;
  commentsDisplay = document.getElementById(commentsId).style.display;
  if(commentsDisplay == 'none'){
    document.getElementById(commentsId).style.display = 'block';
  } else {
    document.getElementById(commentsId).style.display = 'none';
  }
}

function setTheme(){
  var themeA = document.getElementById('themeSelectA').value;
  var themeB = document.getElementById('themeSelectB').value;
  $.ajax({
    url:'phpconnect.php',
    type:'post',
    data:{
      themeA: themeA,
      themeB: themeB,
      setTheme: 1,
    },
    success: function(rep){
      console.log("Theme changed. reply: " + rep);
      document.getElementById("page_theme").innerHTML = rep;
    },
    error: function(rep){
      console.log("Theme change error.");
    },
  });
}

function editProfileText(){
  var textBox = document.getElementById("profileText");
  var textBoxText = document.getElementById("profileTextText");
  var editBox = document.getElementById("profileTextEdit");
  var editBoxInputArea = document.getElementById("profileTextarea");
  if(textBox.style.display == 'block'){
    editBoxInputArea.innerHTML = textBoxText.innerHTML;
    editBox.style.display = 'block';
    textBox.style.display = 'none';
  } else {
    var newProfileText = editBoxInputArea.value;
    editBox.style.display = 'none';
    textBox.style.display = 'block';
    $.ajax({
      url:"phpconnect.php",
      type:"post",
      data:{
        newProfileText : newProfileText,
        profileUserId: dataUserID,
        editProfileText : 1,
      },
      success:function(reply){
        console.log("success: " + reply);
        editBox.style.display = 'none';
        textBox.style.display = 'block';
        textBoxText.innerHTML = reply;
      },
      error:function(reply){
        console.log("success: " + reply);
      },
    });
  }
}

function toggleFollow(button){
  if(button.innerHTML == "Follow"){
    $.ajax({
      url: "phpconnect.php",
      type: "post",
      data: {
        currentUserId: currentUserID,
        profileUserId: dataUserID,
        toggleFollow : 1,
      },
      success:function(reply){
        console.log("success: " + reply);
        button.innerHTML = "Unfollow";
      },
      error:function(reply){
        console.log("error: " + reply);
      },
    });
  } else if(button.innerHTML == "Unfollow") {
    $.ajax({
      url: "phpconnect.php",
      type: "post",
      data: {
        currentUserId: currentUserID,
        profileUserId: dataUserID,
        toggleFollow : 2,
      },
      success:function(reply){
        console.log("success: " + reply);
        button.innerHTML = "Follow";
      },
      error:function(reply){
        console.log("error: " + reply);
      },
    });
  } else {
    button.innerHTML = "Follow";
  }
}

function submitComment(e){
  var postIdValue = e.currentTarget.value;
  var inputId = "commentInput" + postIdValue;
  var userIdValue = dataUserID;
  var userNameValue = dataUserName;
  var userImageSrc = dataUserImageSrc;
  var commentTextValue = document.getElementById(inputId).value;
  //get all the comments
  var commentsId = "commentsHolder" + postIdValue;
  var commentsHTML = document.getElementById(commentsId).innerHTML;
  if(commentTextValue == ""){
    document.getElementById(inputId).style = "background-color: pink";
  }
  else{
    var commentHTML = buildCommentHtml(userImageSrc,userNameValue,"Just now",commentTextValue);
    $(document).ready(function(){
      $.ajax({
         url: 'phpconnect.php',
         type: 'POST',
         data: {
           postIdCom: postIdValue,
           userIdCom: userIdValue,
           commentText: commentTextValue,
           postComment: 1,
         },
         success: function(resp){
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
  currentTarget = e.currentTarget;
  textElement = currentTarget.children[1];
  value = textElement.innerHTML;
  //get the post id and button name for the AJAX part
  var postIdVar = currentTarget.value;
  var btnTypeVar = currentTarget.name;
  //ajax operation to record the operation
  $(document).ready(function(){
    $.ajax({
       url: 'phpconnect.php',
       type: 'POST',
       data: {
         userId: dataUserID,
         postId: postIdVar,
         btnType: btnTypeVar,
         postEmoticon: 1,
       },
       success: function(resp){
         if(resp == 1){
           textElement.innerHTML = ++value;
           currentTarget.style = "background-color: white; color:black;";
         } else if(resp == 0) {
           textElement.innerHTML = --value;
           currentTarget.style = "background-color: transparent; color:white;";
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
      if(emotiArray[i][j] == 1){
        document.getElementById(btnIdArray[i][j]).style = "background-color: white; color:black;";
      } else {
        document.getElementById(btnIdArray[i][j]).style = "background-color: transparent; color:white;";
      }
    }
  }
}

function makePost(){
  //function to make post using AJAX, first we need to get the inputs
  textBoxInput = document.getElementById("makePostInput");
  imageUploadInput = document.getElementById("imageUpload");
  categoryInput = document.getElementById("categorySelect");
  //get rid of white spaces in input text to check if there is any text
  var checkTextInput = textBoxInput.value.replace(/\s/g,"");
  //check to see if either the text or and image has been entered
  if(checkTextInput.length){
    if(imageUploadInput.value){
      var postType = 3;
      var errorText = "Text and Image posted.";
      //upload the image in the input and get it's server URL
      ajaxImageUpload("#imageUpload",postType,textBoxInput.value,categoryInput.value,errorText);
    } else {
      var postType = 1;
      var errorText = "Text posted.";
      //ajax operation to upload the text
      ajaxPost(postType,textBoxInput.value, "NONE",categoryInput.value,errorText);
    }
  } else {
    if(imageUploadInput.value){
      var postType = 2;
      var errorText = "Image posted.";
      //upload the image using AJAX
      ajaxImageUpload("#imageUpload",postType,"NONE",categoryInput.value,errorText);
    } else {
      document.getElementById("errorBox").innerHTML = "<p class='postBoxErrorText'>Please enter text or upload image.</p>";
    }
  }
}

function ajaxPost(postType,textValue,imageURL,category,errorText){
  $.ajax({
    url:'phpconnect.php',
    type:'post',
    data:{
      userIdCom:dataUserID,
      postType:postType,
      postText:textValue,
      postImage:imageURL,
      postcategory:category,
      postPost: 1,
    },
    success: function(resp){
      document.getElementById("errorBox").innerHTML = "<p class='postBoxErrorText'>" + errorText + "</p>";
      document.getElementById("makePostInput").value = "";
      console.log("Post success response = " +  resp);
      // Post the post if successfull
      respArr = JSON.parse(resp);
      currentPosts = document.getElementById("postsHolderID").innerHTML;
      newPost = buildPostHtml(1, respArr[0], dataUserImageSrc, dataUserName, respArr[1], "just now", textValue, [0,0,0,0], [], postType, imageURL);
      currentPosts = newPost + currentPosts;
      document.getElementById("postsHolderID").innerHTML = currentPosts;
      //NEXT WE ADD JAVASCRIPT EVENT LISTENERS TO THE NEW HTML CODE
      setListeners();
    },
    error: function(resp){
      console.log("Post AJAX Error = " + resp);
    },
  });
}

function ajaxImageUpload(imageUploadID,postType,textValue,category,errorText){
  // Getting the properties of file from file field
  var file_data = $(imageUploadID).prop("files")[0];
  console.log("file_data: " + file_data);
  // Creating object of FormData class
  var form_data = new FormData();
  // Appending parameter named file with properties of file_field to form_data
  form_data.append("file", file_data);
  // Adding extra parameters to form_data
  form_data.append("user_id", dataUserID);
  $.ajax({
    url: "phpconnect.php",
    dataType: 'script',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(resp){
      console.log("Image upload success reponse: " + resp);
      imageURL = resp;
      ajaxPost(postType, textValue, imageURL, category, errorText);
      document.getElementById("imageUpload").value = "";
    },
    error: function(resp){
      console.log("Image upload error response: " + resp);
    },
  });
}

function ajaxUserImageUpload(e){
  // Getting the properties of file from file field
  var file_data = $(e).prop("files")[0];
  console.log("file_data: " + file_data);
  // Creating object of FormData class
  var form_data = new FormData();
  // Appending parameter named file with properties of file_field to form_data
  form_data.append("fileInput", file_data);
  // Adding extra parameters to form_data
  form_data.append("user_id", dataUserID);
  $.ajax({
    url: "phpconnect.php",
    dataType: 'script',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(resp){
      console.log("Success: " + resp);
      imgElements = document.getElementsByClassName("userImage");
      for (let imgElement of imgElements){
        imgElement.src = resp;
      }
    },
    error: function(resp){
      console.log("Error: " + resp);
    },
  });
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
    post = buildPostHtml(thisPost[4], thisPost[0], thisPost[2], thisPost[3], thisPost[5], thisPost[1], thisPost[6], thisPost[7], comments, thisPost[10], thisPost[11]);
    posts = post + posts;
   }
   //add the created html to page
   document.getElementById("postsHolderID").innerHTML = posts;
   //set the emoti btns based on the user's history
   setEmotis(btnIdArrays, btnEmotisArrays);
   //NEXT WE ADD JAVASCRIPT EVENT LISTENERS TO THE NEW HTML CODE
   setListeners();
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

function buildPostHtml(trashCan,postId,userImageSrc,userName, postCategory, postDate, postText, emotiArray, comments, postType, postImageURL){
  //create post content based on post btnType
  if(postType == 1){
    postContents = '' +
    '<div class="postText">'+
      '<p>' + postText + '</p>'+
    '</div>';
  } else if(postType == 2){
    postContents = '' +
    '<div class="postText">' +
      '<img class="postImage" src="' + postImageURL + '">'+
    '</div>';
  } else if(postType == 3) {
    postContents = '' +
    '<div class="postText">'+
      '<p>' + postText + '</p>'+
      '<img class="postImage" src="' + postImageURL + '">'+
    '</div>';
  } else {
    postContents = 'POST TYPE ' + postType + ' ERROR';
  }
  //Create trashcan HTML if allowed
  if(trashCan){
    trashCan = ''+
    '<button class="trashBtn" name="trashBtn" value="' + postId + '">'+
      '<i class="far fa-trash-alt"></i>'+
    '</button>';
  } else {
    trashCan = '';
  }
  //create post HTML
  var post = ''+
    '<div class="postHolder" id="postHolder' + postId + '">'+
      '<div class="postTitle">'+
        '<div class="postUserImageSpace">'+
          '<a href= "profile.php?username=' + userName + '">'+
            '<img class="userImage" src="' + userImageSrc + '">'+
          '</a>'+
        '</div>'+
        trashCan +
        '<a href= "profile.php?username=' + userName + '">'+
          '<h2 class="postUserName">' + userName + '</h2>'+
        '</a>'+
        '<h3 class="categoryLabelInPost">' + postCategory + '</h3>'+
        '<h3 class="postDate">' + postDate + '</h3>'+
      '</div>' + postContents +
      '<div class="buttonHolder">'+
        '<button class="emotiBtns" id="likes'+postId+'" name="likes" value="'+ postId +'">'+
          '<i class="far fa-arrow-alt-circle-up"></i>'+
          '<sup class="emotiBtnText">' + emotiArray[0] + '</sup>'+
        '</button>'+
        '<button class="emotiBtns" id="hates'+postId+'" name="hates" value="'+ postId +'">'+
          '<i class="far fa-arrow-alt-circle-down"></i>'+
          '<sup class="emotiBtnText">' + emotiArray[1] + '</sup>'+
        '</button>'+
        '<button class="emotiBtns" id="angers'+postId+'" name="angers" value="'+ postId +'">'+
          '<i class="far fa-angry"></i>'+
          '<sup class="emotiBtnText">' + emotiArray[2] + '</sup>'+
        '</button>'+
        '<button class="emotiBtns" id="deads'+postId+'" name="deads" value="'+ postId +'">'+
          '<i class="far fa-laugh-squint"></i>'+
          '<sup class="emotiBtnText">' + emotiArray[3] + '</sup>'+
        '</button>'+
      '</div>'+
      '<button class="commentBtn" name="commentBtn" name="5" value="'+ postId +'">'+
        '<i class="far fa-comments"></i>'+
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
    '</div>';
  return post;
 }

function setListeners(){
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
  //clicking trash btn:
  var trashBtns = document.getElementsByClassName("trashBtn");
  for(i=0;i<trashBtns.length;i++){
    trashBtns[i].addEventListener("click",deletePost);
  }
}

function deletePost(e){
  var postId = e.currentTarget.value;
  var postBoxId = "postHolder" + e.currentTarget.value;
  $.ajax({
    url:'phpconnect.php',
    type:'post',
    data:{
      postId:postId,
      trashBtn:1,
    },
    success: function(rep){
      document.getElementById(postBoxId).innerHTML = "<h2>Deleted</h2>";
      console.log("Success = "+ rep);
    },
    error: function(rep){
      console.log("Error = " + rep);
    },
  });
}

function getCategoryDropdown(url,categoriesArray){
    //loop over the array
    points = '<a href="'+url+'?category=0">All</a>';
    for(var i=0;i<categoriesArray.length;i++){
      point = '<a href=' + url + "category=" + categoriesArray[i][0]+'>'+
              categoriesArray[i][1]+'</a>';
      points = points + point;
    }
    document.getElementById("dropDownHolder").innerHTML = points;
}

function getCategorySelect(categoriesArray){
    //loop over the array
    points = '<select name="postcategory" id="categorySelect">';
    for(var i=0;i<categoriesArray.length;i++){
      point = ''+
      '<option value="' + categoriesArray[i][0] + '">'+
        categoriesArray[i][1] +
      '</option>';
      points = points + point;
    }
    points = points + '</select>';
    document.getElementById("selectDropdownHolder").innerHTML = points;
}
