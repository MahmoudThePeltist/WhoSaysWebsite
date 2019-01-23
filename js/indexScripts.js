function toggleRegistration(){
  registerForm = document.getElementById('registerForm');
  loginForm = document.getElementById('loginForm');
  regToggleButton = document.getElementById('regToggleButton');

  if(registerForm.style.display == 'block'){
    registerForm.style.display = 'none';
    loginForm.style.display = 'block';
    regToggleButton.innerHTML = 'Register';
  } else {
    registerForm.style.display = 'block';
    loginForm.style.display = 'none';
    regToggleButton.innerHTML = 'Sign In';
  }
}

function goToFeed(){
  var url = "mainFeed.php";
  window.open(url,'_self');
}
