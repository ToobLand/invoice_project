<?php

use function views\header\render_header;
echo render_header('Klanten overzicht');
?>
<div class="container">

<h1 class='title'>Offertes (projecten):</h1>


    <section>
    <!--span data-toggle="tooltip" data-placement="top" title='soms zijn de errors erg vaag van google. Authorization refreshen van je google account verhelpt de problemen.'>
      Ik ervaar problemen met aanmaken van offertes: <a target='_blank' href='/googleauth'>google autorisatie refreshen</a></span-->
   
    <div class='create_new' style='float:right;color:darkGreen;'>
    <a style='display: inline-block;' class="" href="/offer/new"><i style='font-size: 35px; color: #00d024;' class="fa-solid fa-circle-plus"></i></a>

    </div>
    
  
    <table class="table table-striped">
  <thead>
    <tr>
      <th class='mobile_collapse' scope="col">Titel</th>
      <th class='mobile_collapse' scope="col">datum aangemaakt</th>
      <th class='mobile_collapse' scope="col">Edit</th>
      <th class='mobile_collapse' scope="col">PDF</th>
    </tr>
  </thead>
  <tbody>
  <?php 

echo " <tr style='background-color:".($_SESSION['user']->sheet_template=='' ? "#fbeed3;":"#e4fbd3;") . "'>
        <td class='mobile_collapse'>Template voor offertes</td>
        <td class='mobile_collapse'></td>
        <td>";
        if($_SESSION['user']->sheet_template==''){
          echo "<button id='create_template'>Template maken</button>";
        }else{
          echo "<a target='_BLANK' href='https://docs.google.com/spreadsheets/d/{$_SESSION['user']->sheet_template}/edit'>Edit template</a>";
        }
        echo "</td>
        <td></td>
      </tr>";

  if(count($offers)>0){
    foreach($offers as $o){
        echo " <tr>
        <td class='mobile_collapse'>$o->title</td>
        <td class='mobile_collapse'>$o->date_created</td>
        ";
        if($o->google_id!=''){
            echo "<td><a target='_BLANK' href='https://docs.google.com/spreadsheets/d/$o->google_id/edit'>Edit spreadsheet</a></td>
            <td><a href='https://docs.google.com/spreadsheets/d/$o->google_id/export?format=pdf&portrait=true&size=A4'>download als pdf</a></td>";
        }else{
          echo "<td>geen spreadsheet aangemaakt</td>
          <td>Geen spreadsheet aangemaakt</td>";
        }
        echo "</tr>";
       
    }
  }
    ?>
  </tbody>
</table>
    
    <section>
        
</div>

    <script>
        $(document).ready(function(){

          $('[data-toggle="tooltip"]').tooltip();

          $('#create_template').on('click',function(e){
            $(this).prop("disabled", true);
            e.preventDefault();
            $.post('/offer/template_ajax',function(response){
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