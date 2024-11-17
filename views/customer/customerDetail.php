<?php

use function views\header\render_header;
echo render_header('Klanten overzicht');
?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Kilometers toevoegen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body travel_content">
      <table class="table table-striped">
        <tr><td>01-01-2022</td><td>200 km</td><td><i class="fa-solid fa-trash-can"></i></td></tr>
        <tr><td><input type='date'></td><td><input type='number'></td><td><i class="fa-solid fa-floppy-disk"></i></td></tr>
</table>
<i class="fa-solid fa-circle-plus"></i>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="container">

<h1 class='title'><?php echo $customer->firstname . " " . $customer->middlename . " " .$customer->lastname; ?>:</h1>
<div class='row'>
<div class="col-sm" style='text-align:center;'>
<a href='/offer/new#customer:<?php echo $customer->id; ?>'><i style='margin:auto auto; font-size: 150px; color: #cccccc;' class="fa-solid fa-folder-plus"></i></a>
</div>
<?php
if(count($offers)>0){
    $count=1; //(eerste is nieuwe offerte aanmaken, dus starten op 1 ipv 0)
    $colors=['linear-gradient(40deg,#ff6ec4,#7873f5)','linear-gradient(40deg,#ffd86f,#fc6262)','linear-gradient(40deg,#45cafc,#303f9f)'];
    foreach($offers as $offer){
        if($count==0){
            echo "<div class='row'>
            ";
        }
        echo '<div class="col-sm"><div class="card h-100">
        <div class="detail_header" style="background: '.$colors[$count].' !important;">
        <span class="detail_title">'.$offer[0]->title.'</span></div>
        <div class="card-body">
        <h5 class="card-title"><a>Kosten</a></h5>
        <a class="detail_icon" href="/cost/new#customer:'.$customer->id.'&offer:'.$offer[0]->id.'">
          <i class="fa-solid fa-circle-plus"></i>
        </a>
        <p class="card-text">'.$offer[1].'
        </p>
      <h5 class="card-title"><a>reiskosten</a></h5>
      <a class="detail_icon btn_travel" id="'.$offer[0]->id.'" style="cursor:pointer;" data-toggle="modal" data-target="#exampleModal">
      <i class="fa-solid fa-pencil"></i>
</a>
        <p class="card-text">'.$offer[2].' km
     </p>
      <h5 class="card-title"><a>Uren</a></h5>
      <a class="detail_icon btn_hour" id="'.$offer[0]->id.'" style="cursor:pointer;" data-toggle="modal" data-target="#exampleModal">
      <i class="fa-solid fa-pencil"></i>
</a>
<p class="card-text">'.$offer[3].' uur 
     </p>
     <h5 class="card-title"><a>Facturen</a></h5>
     <a class="detail_icon" href="/invoice/customer/'.$customer->id.'/offer/'.$offer[0]->id.'">
     <i class="fa-solid fa-pencil"></i>
</a>
<p class="card-text">'.$offer[4].' incl btw</p>
<h5 class="card-title"><a>Offerte</a></h5>
        <a href="https://docs.google.com/spreadsheets/d/'.$offer[0]->google_id.'/edit" class="btn btn-primary"><i class="fa-solid fa-table"></i></a>
        <a href="https://docs.google.com/spreadsheets/d/'.$offer[0]->google_id.'/export?format=pdf&portrait=true&size=A4" class="btn btn-primary"><i class="fa-solid fa-file-pdf"></i></a>
      </div></div></div>
        ';
        if($count==2){
            echo "</div>";
            $count=0;
        }else{
            $count++;
        }
    }
    if($count!=0){
        echo "</div>";
    }
}
?>
        
</div>

    <script>
        $(document).ready(function(){

          $('[data-toggle="tooltip"]').tooltip();

          $('.btn_travel').on('click',function(){
            const id_offer=$(this).attr('id');
            $('.travel_content').html('');
            $.post('/customer/<?php echo $id; ?>/getTravel_ajax',{id_offer:id_offer},function(content){
              content =jQuery.parseJSON(content);
              let output='<table class="table table-striped">';
              for(let i = 0; i< content.length; i++){
                output += '<tr><td>'+content[i].date+'</td><td>'+content[i].km+' km</td><td><i id="'+content[i].id+'" class="fa-solid fa-trash-can delete_travel"></i></td></tr>';
              }
              output+='<tr><td><input id="date" type="date"></td><td><input id="km" type="number"></td><td><a style="cursor:pointer;" class="save_travel"><i style="font-size:30px;" class="fa-solid fa-floppy-disk"></i></a></td></tr>';
              output += '</table>';
              $('.travel_content').html(output);

              $('.save_travel').on('click',function(){
                let km = $('#km').val();
                let date = $('#date').val();
                if(km>0){
                  $.post('/customer/<?php echo $id; ?>/saveTravel_ajax',{id_offer:id_offer,km:km,date:date},function(response){
                    location.reload();
                  });
                }
              });
              $('.delete_travel').on('click',function(){
                const id=$(this).attr('id');
                if(id>0){
                  if(confirm("Weet je zeker dat je deze reiskosten wilt verwijderen?")){
                    $.post('/customer/<?php echo $id; ?>/deleteTravel_ajax',{id:id},function(response){
                      location.reload();
                    });
                }
                }
              });

            });
          });

          $('.btn_hour').on('click',function(){
            const id_offer=$(this).attr('id');
            $('.travel_content').html('');
            $.post('/customer/<?php echo $id; ?>/getHour_ajax',{id_offer:id_offer},function(content){
              content =jQuery.parseJSON(content);
              let output='<table class="table table-striped">';
              for(let i = 0; i< content.length; i++){
                output += '<tr><td>'+content[i].date+'</td><td>'+content[i].hour+' uren</td><td><i id="'+content[i].id+'" class="fa-solid fa-trash-can delete_hour"></i></td></tr>';
              }
              output+='<tr><td><input id="date" type="date"></td><td><input id="hour" type="number"></td><td><a style="cursor:pointer;" class="save_hour"><i style="font-size:30px;" class="fa-solid fa-floppy-disk"></i></a></td></tr>';
              output += '</table>';
              $('.travel_content').html(output);

              $('.save_hour').on('click',function(){
                let hour = $('#hour').val();
                let date = $('#date').val();
                if(hour>0){
                  $.post('/customer/<?php echo $id; ?>/saveHour_ajax',{id_offer:id_offer,hour:hour,date:date},function(response){
                    location.reload();
                  });
                }
              });
              $('.delete_hour').on('click',function(){
                const id=$(this).attr('id');
                if(id>0){
                  if(confirm("Weet je zeker dat je deze uren wilt verwijderen?")){
                    $.post('/customer/<?php echo $id; ?>/deleteHour_ajax',{id:id},function(response){
                      location.reload();
                    });
                }
                }
              });

            });
          });

          $('#create_template').on('click',function(e){
            $(this).prop("disabled", true);
            e.preventDefault();
            $.post('/customer/<?php echo $id; ?>/offer/template_ajax',function(response){
                  if(response=='success'){
                    message("Aanmaken van spreadsheet is gelukt. Gebruik deze als template voor al je offertes.");
                    setTimeout(function(){
                                window.location.reload();
                              },2500);
                }else if(response=='error'){
                    message("Error. Mislukt om spreadsheet aan te maken. Probeer opnieuw.");
                    $('#create_template').prop("disabled", false);
                }else if(response=='authorization'){
                    message("Authenticatie bij google is nodig. Je wordt automatisch doorgestuurd");
                    
                    setTimeout(function(){
                                $('<a href="/googleauth" target="blank"></a>')[0].click(); 
                                //window.location.replace("/googleauth");
                              },2600);
                }else if(response=='error?'){
                    message("Geen response, blijkbaar iets fout gegaan");
                    
                }
            });
          });
        });

        

</script>




</body>

</html>