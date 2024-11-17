<?php

use function views\header\render_header;
echo render_header('Factuur overzicht');
?>
<div class="container">

<h1 class='title'>Facturen:</h1>
    <section>

    <div class='create_new' style='float:right;color:darkGreen;'>
    <a style='display: inline-block;' class="" href="/invoice/new"><i style='font-size: 35px; color: #00d024;' class="fa-solid fa-circle-plus"></i></a>

    </div>
    <?php if(isset($id) && isset($id_offer)){ ?>
      <div id='breadcrumb'>
      <a class='crumb' href='/customer/<?php echo $id; ?>'><?php echo $customer->firstname ." " .$customer->middlename." " . $customer->lastname; ?></a> <i class='fa-solid fa-angle-right'></i>
      <span class='crumb'><?php echo $offer->title; ?></span> <i class='fa-solid fa-angle-right'></i> <span class='crumb'>Facturen</span>
    </div>
      <?php }else{ ?>
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
    
  ?></select>
  </li>
  
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==1? "active":"disabled"); ?>" href="<?php echo "/invoice/{$year}/1";?>">Q1</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==2? "active":"disabled"); ?>" href="<?php echo "/invoice/{$year}/2";?>">Q2</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($q==3? "active":"disabled"); ?>" href="<?php echo "/invoice/{$year}/3";?>">Q3</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==4? "active":"disabled"); ?>" href="<?php echo "/invoice/{$year}/4";?>">Q4</a>
  </li>
  
  
</ul>
  <?php } 

 $total_incl_btw=0;
 $total_excl_btw=0;
 $total_btw=0;
 foreach($invoice as $in){
   $total_excl_btw+= $in->price_excl_btw;
   $total_incl_btw+= $in->price_incl_btw;
   $total_btw+=($in->price_incl_btw - $in->price_excl_btw);
 }
echo "<table class='table table-striped' style='margin-bottom:50px;'>
    <thead><tr><th style='border-top:none;' >Totaal excl btw</th><th style='border-top:none;'>Totaal incl btw</th><th style='border-top:none;'>Totaal verrekend BTW</th></tr></thead>
    <tbody><tr><td>{$total_excl_btw}</td><td>{$total_incl_btw}</td><td>{$total_btw}</td></tr></tbody>
    </table>";?>
    <table class="table table-striped">
  <thead>
    <tr>
      <th class='mobile_collapse' scope="col">Nummer</th>
      <th scope="col">Klant</th>
      <th class='mobile_collapse' scope="col">Prijs excl btw</th>
      <th scope="col">Prijs(+btw)</th>
      <th scope="col">datum</th>
      <th scope="col">Download</th>
      <th scope="col">Edit</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    foreach($invoice as $i){
        echo " <tr>
        <td class='mobile_collapse'>$i->number</td>
        <td >{$i->customer->firstname} {$i->customer->middlename} {$i->customer->lastname}</td>
        <td class='mobile_collapse'>$i->price_excl_btw</td>
        <td>$i->price_incl_btw</td>
        <td>".date('d-m-Y' ,strtotime($i->date_send))."</td>
        <td><a href='".($i->file_link==='' ? "/invoice/pdf/{$i->id}":"/uploads/invoice/{$i->file_link}" )."'>
        <i class='fa-solid fa-cloud-arrow-down'></i></a></td>
        <td><a href='/invoice/edit/{$i->id}'>
        <i class='fa-solid fa-pencil'></i></a></td>
       
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
                    window.location.href="/invoice";
                }else{
                    window.location.href="/invoice/"+value;
                }
            });
          
        
            
        });

        

</script>




</body>

</html>