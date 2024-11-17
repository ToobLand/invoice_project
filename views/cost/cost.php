<?php

use function views\header\render_header;
echo render_header('Kosten overzicht');
?>
<div class="container">

<h1 class='title'>Kosten:</h1>
    <section>
    <div class='create_new' style='float:right;'>
    <a style='display: inline-block;' class="" href="/cost/new"><i style='font-size: 35px; color: #00d024;' class="fa-solid fa-circle-plus"></i></a>
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
    <a class="nav-link disabled <?php echo ($q==1 ? "active":""); ?>" href="<?php echo "/cost/{$year}/1";?>">Q1</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==2 ? "active":""); ?>" href="<?php echo "/cost/{$year}/2";?>">Q2</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==3 ? "active":""); ?>" href="<?php echo "/cost/{$year}/3";?>">Q3</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled <?php echo ($q==4 ? "active":""); ?>" href="<?php echo "/cost/{$year}/4";?>">Q4</a>
  </li>
  
  
</ul>
<?php
 $total_incl_btw=0;
 $total_excl_btw=0;
 $total_btw=0;
 foreach($costs as $cost){
  $excl_btw= round(($cost->price/(($cost->btw+100) / 100)),2);
   $total_excl_btw+= $excl_btw;
   $total_incl_btw+= $cost->price;
   $total_btw+=$cost->price - $excl_btw;
 }
echo "<table class='table table-striped' style='margin-bottom:50px;'>
    <thead><tr><th>Totaal excl btw</th><th>Totaal incl btw</th><th>Totaal betaalde BTW</th></tr></thead>
    <tbody><tr><td>{$total_excl_btw}</td><td>{$total_incl_btw}</td><td>{$total_btw}</td></tr></tbody>
    </table>";?>
    <table class="table table-striped" style='font-size:12px;'>
  <thead>
    <tr>
      <th scope="col">Bon</th>
      <th scope="col" style='word-break:break-word;'>Beschrijving</th>
      <th scope="col">prijs</th>
      <th scope="col">datum</th>
      <th class='mobile_collapse' scope="col">btw</th>
      <th class='mobile_collapse' scope="col">klant</th>
      <th class='' scope="col">wijzig</th>
      <th class='' scope="col">verwijder</th>
      
    </tr>
  </thead>
  <tbody>
  <?php 
    foreach($costs as $c){
        echo " <tr>
        <th scope='row'><i data-image='{$c->image}' class='fa-solid fa-image enlarge'></i></th>
        <td></i>$c->description</td>
        <td>".number_format((float)$c->price, 2, ',', '.')."</td>
        <td>".date('d-m-Y',strtotime($c->date))."</td>
        <td class='mobile_collapse'>{$c->btw}%</td>
        <td>".(isset($customer_names[$c->id_customer]) ? $customer_names[$c->id_customer] : "nvt") ."</td>
        <td class='mobile_collapse'><a href='/cost/edit/{$c->id}'><i  data-id='{$c->id}' class='fa-solid fa-pencil edit_cost'></i></a></td>
        <td class='mobile_collapse'><i  data-id='{$c->id}' class='fa-solid fa-trash-can delete_cost'></i></td>
        
      </tr>";
       
    }
    ?>
  </tbody>
</table>
    
    <section>
        
</div>
<div class="modal fade bd-example-modal-lg" id='myModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Bon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <script>
        $(document).ready(function(){

          $('.delete_cost').on('click',function(e){
            e.preventDefault();
            const id=parseInt($(this).attr('data-id'));
            if(confirm("Weet je zeker dat je deze kostenpost wilt verwijderen?")){
              $.post('/cost/delete_ajax',{id:id},function(res){
                message(res);
                setTimeout(function(){
                  window.location.reload();
                },2500);
              });
            }
          });

           $('.enlarge').click(function(){
            var file= $(this).attr('data-image');
            var extension = file.substr( (file.lastIndexOf('.') +1) );
                $('#myModal').modal();
                if(extension =='pdf'){
                  $('.modal-body').html( "<iframe width='100%' style='height:80vh;' src='/uploads/costs/"+ $(this).attr('data-image')+"'></iframe>" );
                }else{
                  $('.modal-body').html( "<img style='max-width:100%;' src='/uploads/costs/"+ $(this).attr('data-image')+"'>" );
                }
           });
           $('.filter_year option[value="<?php echo $year; ?>"]').prop("selected", true);
           <?php if($year==0){ ?>
            $('.nav-link').addClass("disabled");
          <?php }else{ ?>
            $('.nav-link').removeClass("disabled");
         <?php  } ?>
           
            $('.filter_year').on('change',function(){
                const value= $(this).val();
                if(value==0){
                    window.location.href="/cost";
                }else{
                    window.location.href="/cost/"+value;
                }
            });
          
        
            
        });

        

</script>




</body>

</html>