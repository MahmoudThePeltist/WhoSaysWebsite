//display box:
var userName = "<?php echo $currentUserName; ?>";
var inputActive = false;
var likeFlags = [];
document.getElementById("postBtn").addEventListener("click",showBox);
function showBox(){
  if (inputActive == false){
    document.getElementById('makePostBox').style.display = 'block';
    for (var i=0; i<100;i++){
      setTimeout(function(){document.getElementById('makePostBox').style.opacity = i/100},100);
    }
    inputActive = true;
  }else{
    document.getElementById('makePostBox').style.display = 'none';
    document.getElementById('makePostBox').style.opacity = 0;
    inputActive = false;
  }
}
//comment section open:
var commentButtons = document.getElementsByClassName("commentBtn");
for(i=0;i<commentButtons.length;i++){
  commentButtons[i].addEventListener("click",toggleComments);
  commentsId = "commentsSect" + commentButtons[i].value;
  document.getElementById(commentsId).style.display = 'none';
}
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
//comment submit handling:
var commentSubmitBtns = document.getElementsByClassName("commentSubmitBtn");
for(i=0;i<commentSubmitBtns.length;i++){
  commentSubmitBtns[i].addEventListener("click",submitComment);
}
function submitComment(e){
  var postIdValue = e.target.value;
  var inputId = "commentInput" + postIdValue;
  var userIdValue = document.getElementById("mainUserImage").name;
  var userImageSrc = document.getElementById("mainUserImage").src;
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
      '<div class="commentContainerContainer"><div class="commentUserName">'+userName+'</div>'+
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
//Reaction buttons:
var emotiBtns = document.getElementsByClassName("emotiBtns");
for(let i = 0; i < emotiBtns.length; i++){
  emotiBtns[i].addEventListener("click",addPoint);
}
function addPoint(e){
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
