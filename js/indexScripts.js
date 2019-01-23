
  var regToggleFlag = 0;
  function toggleRegistration(){
    if(regToggleFlag == 1){
      document.getElementById('registerForm').style.display = 'none';
      document.getElementById('loginForm').style.display = 'block';
      document.getElementById('regToggleButton').innerHTML = 'Register';
      regToggleFlag = 0;
    } else {
      document.getElementById('loginForm').style.display = 'none';
      document.getElementById('registerForm').style.display = 'block';
      document.getElementById('regToggleButton').innerHTML = 'Sign In';
      regToggleFlag = 1;
    }
  }
  function closeRegistration(){
  }
  function goToFeed(){
    var url = "mainFeed.php";
    window.open(url,'_self');
  }
