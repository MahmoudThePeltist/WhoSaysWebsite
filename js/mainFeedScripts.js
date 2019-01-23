//display box:
var userName = "<?php echo $currentUserName; ?>";

//continue:
var inputActive = false;
// make post enable/disable

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
