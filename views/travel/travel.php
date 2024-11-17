<?php

use function views\header\render_header;
echo render_header('Klanten overzicht');
?>

<div class="container">

<h1 class='title'>Reiskosten:</h1>
    <section>

    <div class='create_new' style='float:right;color:darkGreen;'>
    <a style='display: inline-block;' class="" href="/travel/new"><i style='font-size: 35px; color: #00d024;' class="fa-solid fa-circle-plus"></i></a>

    </div>
    <ul class="nav nav-tabs">
  
  <li class="nav-item">
<select class='custom-select filter_year'>
  <option value='0'>Alle</option>
  <?php 
    $curr = (int) date("Y");
    // minimum date = 2022. is launch year of this app :) 
    $difference= $curr - 2022;
    
      for($i=0;$i<=$difference;$i++){
        echo "<option value='".(2022+$i)."'>".(2022+$i)."</option>";
      }
    
  ?>
</select>
  </li>
  
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==1 ? "active":""); ?>" href="<?php echo "/travel/{$year}/1";?>">Q1</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==2 ? "active":""); ?>" href="<?php echo "/travel/{$year}/2";?>">Q2</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==3 ? "active":""); ?>" href="<?php echo "/travel/{$year}/3";?>">Q3</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==4 ? "active":""); ?>" href="<?php echo "/travel/{$year}/4";?>">Q4</a>
  </li>
  
  
</ul> 
    <?php
 $total=0;
 foreach($travels as $travel){
   $total+= $travel->km;
 }
echo "<table class='table table-striped' style='margin-bottom:50px;'>
    <thead><tr><th>Totaal km</th></tr></thead>
    <tbody><tr><td>{$total}</td></tr></tbody>
    </table>";?>
    <table class="table table-striped">
  <thead>
    <tr>
      <th class='' scope="col">Uren</th>
      <th class='' scope="col">datum</th>
      <th scope="col">klant</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    foreach($travels as $t){
        echo " <tr>
        <td class=''>$t->km</td>
        <td class=''>$t->date</td>
        <td>" . (isset($customer_names[$t->id_customer]) ? $customer_names[$t->id_customer] : "nvt") . "</td>
      </tr>";
       
    }
    ?>
  </tbody>
</table>
    
    <section>
        
</div>

    <script>
        $(document).ready(function(){
            $('.filter_year option[value="<?php echo $year; ?>"]').prop("selected", true);
           <?php if($year==0){ ?>
            $('.nav-link').addClass("disabled");
          <?php }else{ ?>
            $('.nav-link').removeClass("disabled");
         <?php  } ?>
           
            $('.filter_year').on('change',function(){
                const value= $(this).val();
                if(value==0){
                    window.location.href="/travel";
                }else{
                    window.location.href="/travel/"+value;
                }
            });
        });

        

</script>




</body>

</html>