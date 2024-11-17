<?php
use function App\Helpers\Functions\is_local;
use App\Models\User;
if(is_local()){

}else{
  $url = $_SERVER['HTTP_HOST'];
  if (preg_match('/www/', $_SERVER['HTTP_HOST'])) {
    // there is www in the url. (imprtant for redirect with google API)
  } else {
    //no www. in url
    header("Location: https://www.tlandman.nl/login");
    die();
  }
  if ($_SERVER['REQUEST_SCHEME'] == 'http') {
    // http
    header("Location: https://www.tlandman.nl/login");
    die();
  } else {
    // nice, https.
  }
}

  $user= new User();
  if($user->login_with_cookie()){
    if(is_local()){
      header("Location: /customer");
    }else{
      header("Location: https://www.tlandman.nl/customer");
    }
  }


?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Inlogguh</title>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Datepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="/styling.css" />
</head>

<body class="text-center">
  <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3500" style="border: 1px solid rgba(0,0,0,.1);
  box-shadow: 0 0.25rem 1rem rgb(0 0 0 / 10%);
  background: #0497fb;
  color: white;
  text-align: center;
  border-radius: 0;height:auto;">
    <div class="toast-body">
      <span id="notification_text">Succesvolle login</span>
    </div>
  </div>
  <script>
    function message(text) {
      $("#notification_text").html(text);
      $(".toast").toast("show");
    }
  </script>
  <form class="form-signin" style='max-width:500px;min-width:300px; width:50%;margin-left:auto;margin-right:auto;margin-top:10%;'>
    <img class="mb-4" src="/resources/logo_icon.png" alt="" width="130" height="130">
    <h1 class="h3 mb-3 font-weight-normal">Geldige inlog vereist</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
    <br>
    <label for="inputKeepLogin" class="">Lange tijd ingelogd blijven op dit apparaat</label>
    <input style='display:inline-block;height:15px;width:15px;margin-left:20px;' type="checkbox" id="inputKeepLogin" name='keep_login'>
    <br><br>
    <button class="btn btn-lg btn-primary btn-block" id='login' type="submit">inloggen</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
  </form>
  <script>
    $(document).ready(function() {
      $('#login').on('click', function(e) {
        e.preventDefault();
        const email = $('#inputEmail').val();
        const password = $('#inputPassword').val();
        const keep_login=$('#inputKeepLogin').prop("checked") ? 1 : 0;
        
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (!filter.test(email)) {
          alert('invalid email address');
        } else {
          if (password != '') {
            $.post('/login_ajax', {
              email: email,
              password: password,
              keep_login: keep_login
            }, function(response, status) {
              if (response == 'gelukt') {
                message('Succesvolle login');
                setTimeout(function() {
                  window.location.replace("/customer");
                }, 1500);
              } else {
                message('Ongeldige inloggegevens');
              }

            });
          } else {
            alert('please fill in both input fields');
          }
        }
      });
    });
  </script>
</body>

</html>