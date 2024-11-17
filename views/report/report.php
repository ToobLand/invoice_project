<?php

use function views\header\render_header;
use Google\Client;
use Google\Service\Docs;
use function App\Helpers\Functions\is_local;

/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */
echo render_header('Overzichten');
?>
<div class="container">

  <h1 class='title'>Overzichten</h1>

  <div class="card-deck">
    <div class="card">
      <img class="card-img-top" src="https://source.unsplash.com/joqWSI9u_XM/300x150" alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">Kosten & inkomsten</h5>
        <p class="card-text">Overzicht voor belasting.
          <br><br>Selecteer het jaar:<br>
          <select class='custom-select filter_year_profit'>
            <option disabled selected>Kies kalenderjaar</option>
            <?php
            $curr = (int) date("Y");
            // minimum date = 2022. is launch year of this app :) 
            $difference = $curr - 2022;

            for ($i = 0; $i <= $difference; $i++) {
              echo "<option value='" . (2022 + $i) . "'>" . (2022 + $i) . "</option>";
            }

            ?>
          </select><br><br><button id='profit_report' class="btn btn-primary" disabled>openen</button>
        </p>
      </div>
    </div>
    <div class="card">
      <img class="card-img-top" src="https://source.unsplash.com/Q1p7bh3SHj8/300x150" alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">Kosten uitprinten</h5>
        <p class="card-text">Alle kosten van een jaar onderelkaar om als pdf of print te bewaren
        <br><br>Selecteer het jaar:<br>
          <select class='custom-select filter_year_costs'>
            <option disabled selected>Kies kalenderjaar</option>
            <?php
            $curr = (int) date("Y");
            // minimum date = 2022. is launch year of this app :) 
            $difference = $curr - 2022;

            for ($i = 0; $i <= $difference; $i++) {
              echo "<option value='" . (2022 + $i) . "'>" . (2022 + $i) . "</option>";
            }

            ?>
          </select><br><br><button id='costs_report' class="btn btn-primary" disabled>openen</button>


        </p>
      </div>
    </div>
    <div class="card">
      <img class="card-img-top" src="https://source.unsplash.com/xhD49fKOzw0/300x150" alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">Facturen uitprinten</h5>
        <p class="card-text">Alle uitgestuurde facturen onderelkaar om als pdf of print te bewaren</p>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.filter_year_profit').on('change', function() {
      if (parseInt($(this).val()) >= 1) {
        $('#profit_report').removeAttr('disabled').attr('data_go','go');
      } else {
        $('#profit_report').attr('disabled','disabled').removeAttr('data_go');
      }
    });
$('#profit_report').click(function(){
  if($(this).attr('data_go')=='go'){
    window.location.href='/report/profit/'+$('.filter_year_profit').val();
  }
});

$('.filter_year_costs').on('change', function() {
      if (parseInt($(this).val()) >= 1) {
        $('#costs_report').removeAttr('disabled').attr('data_go','go');
      } else {
        $('#costs_report').attr('disabled','disabled').removeAttr('data_go');
      }
    });
$('#costs_report').click(function(){
  if($(this).attr('data_go')=='go'){
    window.location.href='/report/costs/'+$('.filter_year_costs').val();
  }
});


  });
</script>
</body>

</html>