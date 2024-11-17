<?php 
namespace Views\header;
function render_header($title='T.Landman'){
$output_html='
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ZZP administratie">
    <meta name="author" content="Landman development">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" href="/resources/logo_icon.png">
    <title>'.$title.'</title>
<!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" 
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
        crossorigin="anonymous"></script>
<!-- Bootstrap -->        
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Datepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

<link rel="stylesheet" href="/styling.css" />
<link href="/assets/fontawesome/css/fontawesome.css" rel="stylesheet">
<link href="/assets/fontawesome/css/solid.css" rel="stylesheet">
<style>

</style>

  </head>

<body>
  

  <div class="nav_wrapper">
    <div class="left-side" id="">
      <a class="logo " href="/">
      <img class="mb-4" src="/resources/logo_icon.png" alt="" width="35">
      </a>
      <ul class="menu_wrapper">
        <li class="menu_button" style="margin-top: 8px;">
          <a class="btn btn-primary" href="/customer">Klanten</a>
        </li>
        <li class="menu_button">
          <a class="" href="/cost">Kosten</a>
        </li>
        <li class="menu_button">
          <a class="" href="/invoice">Facturen</a>
        </li>
        <li class="menu_button">
          <a class="" href="/offer">Projecten</a>
        </li>
        <li class="menu_button">
          <a class="" href="/hour">Uren</a>
        </li>
        <li class="menu_button">
          <a class="" href="/travel">Reiskosten</a>
        </li>
        <li class="menu_button">
          <a class="" href="/report">Overzichten</a>
        </li>
        <li class="menu_button mobile_view">
          <a class="" href="/report">Uitloggen</a>
        </li>
      </ul>
    </div>

    <div class="right-side">
      <!-- Icon -->
      <div class="right_stuff logout_button" style=""><a href="/logout">Uitloggen</a></div>
      <div class="right_stuff logged_in" style="">Ingelogd als: '. (isset($_SESSION['loggedin']) ? $_SESSION['user']->firstname : "Niet ingelogd") .'</div>
      <div class="right_stuff hamburger" style=""><i class="fa-solid fa-bars"></i></div>
    </div>
  </div>
  <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3500"
  style="border: 1px solid rgba(0,0,0,.1);
  box-shadow: 0 0.25rem 1rem rgb(0 0 0 / 10%);
  background: #0497fb;
  color: white;
  text-align: center;
  border-radius: 0;height:auto;"
  >
  <div class="toast-body">
    <span id="notification_text">Succesvolle login</span>
  </div>
</div>
  <script>
  $(document).ready(function(){
    $(".hamburger").click(function(){
      console.log($(".menu_wrapper").attr("data-active"));
        if($(".menu_wrapper").attr("data-active")=="visible"){
          console.log("slide up");
          $(".menu_wrapper").slideUp(function(){
            $(this).attr("style",""); 
          }).attr("data-active","hidden");
        }else{
          console.log("slide down");
          $(".menu_wrapper").slideDown().attr("data-active","visible");
        }
        
    });
  
  });
  function message(text){
    $("#notification_text").html(text);
    $(".toast").toast("show");
  }
  </script>
  
  
  ';
return $output_html;
}
