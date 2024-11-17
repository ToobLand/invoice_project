<?php

use function views\header\render_header;
use function App\Helpers\Functions\build_form;

echo render_header('Nieuwe kosten invoeren');
?>
<div class="container">
  <h1>Nieuw project:</h1>
  <section>
    <div class="form-group">
      <label for="input_0">Titel (project titel)</label>
      <input type="text" class="form-control" id="input_0" placeholder="Titel">
    </div>
    <div class="form-group">
      <label for="input_1">Kosten horen bij klant:</label>
      <Select class="custom-select customer" id="input_1" required>
        <option disabled selected>Kies hier een klant, offerte moet aan klant zijn gekoppeld</option>
        <?php

        foreach ($customers as $c) {
          echo "<option value='" . $c->id . "'>" . $c->firstname . " " . $c->middlename . " " . $c->lastname . "</option>";
        }
        ?>
      </Select>

    </div>
    
      Excel spreadsheet aanmaken voor project? (handig voor offerte of kladblok)<br>
      <div class="form-group">   
      <div class="form-check">  
      <input class="form-check-input spreadsheet_radio" type="radio" name="spreadsheet" value='1' id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">
              Ja
            </label><br>
            <input class="form-check-input spreadsheet_radio" type="radio" name="spreadsheet" value='0' id="flexRadioDefault1" checked>
            <label class="form-check-label" for="flexRadioDefault1">
              Nee
            </label>
      </div>
    </div>
    <button type='submit' id='send_form' class='btn btn-primary'>Project aanmaken</button>
    <script>
      let hash=false;
      let customer=0;
      $(document).ready(function() {

        const hashString = location.hash.replace(/^.*?#/, '');
        if (hashString != '') {
          customer = hashString.split(':')[1];
          $("#input_1 option[value='"+customer+"']").prop('selected',true);
          hash=true;
        }

        $("#send_form").on("click", function(e) {
          e.preventDefault();
          let submit_button = $(this);
          submit_button.html('<i class="fa-solid fa-spinner"></i>').prop('disabled', true);
          let data = {};
          data["title"] = $("#input_0").val();
          data["customer"] = $("#input_1").val();
          data["spreadsheet"] = $('.spreadsheet_radio:checked').val();
          if (data.customer > 0) {


            data = JSON.stringify(data);
            $.post("/offer/new_ajax", {
              data: data
            }, function(response, status) {

              submit_button.html('<i class="fa-solid fa-circle-check"></i>');
              delete data;
              if (response == 'success') {
                message("Aanmaken is gelukt. Je wordt terug gestuurd.");
                setTimeout(function() {
                  if(hash){
                    window.location.replace("/customer/"+customer);
                  }else{
                    window.location.replace("/offer");
                  }
                  
                }, 2000);
              } else if (response == 'error') {
                message("Error. Mislukt om spreadsheet aan te maken. Probeer opnieuw.");
                $("#send_form").prop("disabled", false);
              } else if (response == 'authorization') {
                message("Authenticatie bij google is nodig. Je wordt automatisch doorgestuurd");
                setTimeout(function() {
                  //window.location.replace("/googleauth");
                  $('<a href="/googleauth" target="blank"></a>')[0].click();
                  setTimeout(function() {
                    $("#send_form").prop("disabled", false);
                  }, 3000);
                }, 2600);
              } else if (response == 'error?') {
                message("Geen response, blijkbaar iets fout gegaan");
                console.log(response);
                setTimeout(function() {
                  if(hash){
                    window.location.replace("/customer/"+customer);
                  }else{
                    window.location.replace("/offer");
                  }
                }, 1500);
              }





            });
          } else {
            message("Om een offerte (project) aan te maken moet je een klant kiezen");
          }
        })
      });
    </script>
    <br><br>Bij een project wordt automatisch een Google spreadsheet gecreeerd. Daarmee kun je de offerte uitwerken.
    Als er nog geen geldige Google API connectie is gelegd, kan het zijn dat er gevraagd wordt of je met je google account wilt inloggen.
    De aangemaakte spreadsheets worden dan automatisch bij je eigen google account opgeslagen en blijven prive.
    <br><br>
    <?php 
    if($_SESSION['user']->sheet_template==''){
      echo "Je hebt nog geen 'template' aangemaakt voor offertes. 
      Het is mogelijk een (lege) spreadsheet aan te maken waarin je alvast al je opmaak en dergelijke kan plaatsen. 
      Dit spreadsheet wordt dan altijd gekopieerd om een nieuwe offerte aan te maken. <br><br>
      Ga naar <a href='/offer'>Offertes</a> om een template offerte aan te maken.";
    }
    ?>
  </section>
</div>
</body>

</html>