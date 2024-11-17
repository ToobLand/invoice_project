<?php

use function views\header\render_header;
echo render_header('Klanten overzicht');
?>
<div class="container">

<h1 class='title'>Klanten:</h1>
    <section>

    <div class='create_new' style='float:right;color:darkGreen;'>
    <a style='display: inline-block;' class="" href="/customer/new"><i style='font-size: 35px; color: #00d024;' class="fa-solid fa-circle-plus"></i></a>

    </div>
    
  
    <table class="table table-striped">
  <thead>
    <tr>
      <th class='mobile_collapse' scope="col">Voornaam</th>
      <th class='mobile_collapse' scope="col">Tussenvoegsels</th>
      <th scope="col">Achternaam</th>
      <th scope="col">email</th>
      <th scope="col">telefoon</th>
      <th scope="col">Projecten</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    foreach($customers as $c){
        echo " <tr class='clickable' id='{$c->id}'>
        <td class='mobile_collapse'>$c->firstname</td>
        <td class='mobile_collapse'>$c->middlename</td>
        <td>$c->lastname</td>
        <td><a href='mailto:$c->email'>$c->email</a></td>
        <td>$c->telephone</td>
        <td><a href='/customer/$c->id'>Projecten</a></td>
      </tr>";
       
    }
    ?>
  </tbody>
</table>
    
    <section>
        
</div>

    <script>
        $(document).ready(function(){
          $('.clickable').on('click',function(){
              window.location.replace('/customer/'+$(this).attr('id'));
          });
        });

        

</script>




</body>

</html>