//display box:
var userName = "<?php echo $currentUserName; ?>";

//continue:
var inputActive = false;
//make post enable/disable
// document.getElementById("postBtn").addEventListener("click",showBox);
// function showBox(){
//   if (inputActive == false){
//     document.getElementById('makePostBox').style.display = 'block';
//     for (var i=0; i<100;i++){
//       setTimeout(function(){document.getElementById('makePostBox').style.opacity = i/100},100);
//     }
//     inputActive = true;
//   }else{
//     document.getElementById('makePostBox').style.display = 'none';
//     document.getElementById('makePostBox').style.opacity = 0;
//     inputActive = false;
//   }
// }
//post tabs enable/disable
function openCity(evt, postType) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(postType).style.display = "block";
  evt.currentTarget.className += " active";
}
