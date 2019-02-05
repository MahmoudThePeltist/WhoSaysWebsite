function toggleRegistration(){
  registerForm = document.getElementById('registerForm');
  loginForm = document.getElementById('loginForm');
  regToggleButton = document.getElementById('regToggleButton');
  newPasswordForm = document.getElementById('newPasswordForm');

  if(registerForm.style.display == 'block'){
    registerForm.style.display = 'none';
    newPasswordForm.style.display = 'none';
    loginForm.style.display = 'block';
    regToggleButton.innerHTML = 'Register';
  } else {
    registerForm.style.display = 'block';
    newPasswordForm.style.display = 'none';
    loginForm.style.display = 'none';
    regToggleButton.innerHTML = 'Sign In';
  }
}

function toggleChangePassword(){
  newPasswordForm = document.getElementById('newPasswordForm');
  loginForm = document.getElementById('loginForm');
  passToggleButton = document.getElementById('passToggleButton');

  if(newPasswordForm.style.display == 'block'){
    registerForm.style.display = 'none';
    newPasswordForm.style.display = 'none';
    loginForm.style.display = 'block';
  } else {
    registerForm.style.display = 'none';
    newPasswordForm.style.display = 'block';
    loginForm.style.display = 'none';
  }
}

function goToFeed(){
  var url = "mainFeed.php";
  window.open(url,'_self');
}
