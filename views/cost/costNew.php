<?php

use function views\header\render_header;

echo render_header('Nieuwe kosten invoeren');

$customer = [];
foreach ($customers as $c) {
  $customer[] = ['value' => $c->id, 'title' => $c->firstname . " " . $c->middlename . " " . $c->lastname];
}

?>
<div class="container">
  <h1>Kosten invoeren:</h1>
  <section>
    <div id='breadcrumb'></div>


    <form>
      <div class="form-group uploadwrapper">
        <div class="custom-file">
          <input class='custom-file-input' type="file" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" capture="environment" id='image' />
          <label class="custom-file-label label_upload" for="image">Foto bon maken</label>
          Verander upload methode (mobile only): <button id='switch_upload'>verander naar uploaden</button>
        </div>
      </div>
      <div class="form-group">
        <label for="description">Datum</label>
        <input type="date" class="form-control" id="date" placeholder="Selecteer datum van aankoop">
      </div>


      <div class='' id='new_cost'>
        <div class="form-group">
          <label for="description">BTW tarief</label>
          <div class="form-check">
            <input class="form-check-input btw_radio" type="radio" name="btw" value='0' id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">
              0%
            </label>


            <input style='position: relative;
    margin-left: 10px;' class="form-check-input btw_radio" type="radio" name="btw" value='9' id="flexRadioDefault2">
            <label class="form-check-label" for="flexRadioDefault2">
              9%
            </label>


            <input style='position: relative;
    margin-left: 10px;' class="form-check-input btw_radio" type="radio" name="btw" value='21' id="flexRadioDefault3" checked>
            <label class="form-check-label" for="flexRadioDefault3">
              21%
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="description">Prijs</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">Incl btw</span>
            </div>
            <input type="number" step="0.01" class="form-control incl_btw" id="price" placeholder="0,00">
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">Excl btw</span>
            </div>
            <input type="number" step="0.01" class="form-control excl_btw" id="price_excl_btw" placeholder="0,00">
          </div>
        </div>
        <div class="form-group">
          <label for="description">Beschrijving</label>
          <input type="text" class="form-control" id="description" aria-describedby="descriptionHelp" placeholder="Beschrijving">
          <small id="descriptionHelp" class="form-text text-muted">Beschrijving van kostenpost.</small>
        </div>
        <div class="form-group">
          <label for="description">Categorie</label>
          <Select class='custom-select category' id='category'>
            <?php foreach ($categories as $catObject) {
              echo "<option value='{$catObject->id}' " . ($catObject->id == 11 ? "selected" : "") . ">{$catObject->title}</option>";
            } ?>
          </Select>
        </div>


        <div class="form-group">
          <label for="input_0">Kosten horen bij klant:</label>
          <Select class="custom-select customer" id="input_0">
            <option value='0' selected>Kostenpost niet gelinkt aan klant</option>
            <?php

            foreach ($customer as $c) {
              echo "<option value='" . $c['value'] . "'>" . $c['title'] . "</option>";
            }
            ?>
          </Select>
        </div>
        <div class="form-group">
          <label for="input_1">Kosten horen bij offerte:</label>
          <Select class="custom-select offerte" id="input_1">
            <option value='0' selected>Kostenpost niet gelinkt aan offerte</option>
          </Select>
        </div>
        <div class="form-group">
          <label for="input_2">Kosten afschrijven?</label>
          <div class="form-check">
            <input class="form-check-input write_off" type="radio" name="write_off" value='0' id="flexRadioDefaultwriteoff" checked>
            <label class="form-check-label" for="flexRadioDefaultwriteoff">
              Nee
            </label>
            <input style='position: relative;
    margin-left: 10px;' class="form-check-input write_off" type="radio" name="write_off" value='1' id="flexRadioDefaultwriteoff2">
            <label class="form-check-label" for="flexRadioDefaultwriteoff2">
              Ja
            </label>
          </div>
          <br>
          <div id='write_off_wrapper' style='display:none;'>
            <div class="form-group">
              <label for="write_off_years">In hoeveel jaar afschrijven?</label>
              <input type="number" step="1" class="form-control" id="write_off_years" value='0' placeholder="5">
            </div>

            <div class="form-group">
              <label for="write_off_rest">Restwaarde na afschrijvingsperiode (excl btw)</label>
              <input type="number" step="0.01" class="form-control" id="write_off_rest" value='0' placeholder="0,00">
            </div>
          </div>
        </div>



      </div>
      <div class='' id='show_costs'>
      </div>
      <div>
        <button style='float:left; background-color:#00d024;border-color:#00d024;' id='add_cost' class="btn btn-primary">Post toevoegen <i class='fa-solid fa-circle-plus'></i></button>
        <button style='float:right;' type="submit" id='save_cost' class="btn btn-primary">Kosten opslaan</button>
      </div>
    </form>



    <section>
</div>
<script>
  let hash = false;
  let customer, offer;
  $(document).ready(function() {



    function change_excl_btw(elem_id) {
      const incl = parseFloat($(elem_id).find('#price').val().replace(',', '.'));
      const btw = $(elem_id).find('.btw_radio:checked').val();
      let excl = incl / ((parseInt(btw) + 100) / 100);
      excl = excl.toFixed(2);
      $(elem_id).find('#price_excl_btw').val(excl);
    }

    function change_incl_btw(elem_id) {
      const excl = parseFloat($(elem_id).find('#price_excl_btw').val().replace(',', '.'));
      const btw = $(elem_id).find('.btw_radio:checked').val();
      let incl = excl * ((parseInt(btw) + 100) / 100);
      incl = incl.toFixed(2);
      $(elem_id).find('#price').val(incl);
    }


    const hashString = location.hash.replace(/^.*?#/, '');
    if (hashString != '') {
      hash = true;
      const pairs = hashString.split('&');
      customer = pairs[0].split(':')[1];
      offer = pairs[1].split(':')[1];

      $.post('/cost/new/preselect_ajax', {
        id_customer: customer,
        id_offer: offer
      }, function(response) {
        response = jQuery.parseJSON(response);
        $('#new_cost #input_0').prop('disabled', 'disabled').find('option').remove();
        $('#new_cost #input_0').append('<option value="' + response[0].id + '" selected>' + response[0].firstname + ' ' + response[0].lastname + '</option>');
        $('#new_cost #input_1').prop('disabled', 'disabled').find('option').remove();
        $('#new_cost #input_1').append('<option value="' + response[1].id + '" selected>' + response[1].title + '</option>');

        $('#breadcrumb').html("<a class='crumb' href='/customer'>Klanten</a> <i class='fa-solid fa-angle-right'></i> " +
          "<a class='crumb' href='/customer/" + response[0].id + "'>" + response[0].firstname + ' ' + response[0].middlename + " " + response[0].lastname + "</a> <i class='fa-solid fa-angle-right'></i> " +
          "<span class='crumb'>Nieuwe kosten</span>");

      });


    } else {
      $('#breadcrumb').html("<a class='crumb' href='/cost'>Kosten</a> <i class='fa-solid fa-angle-right'></i> " +
        "<span class='crumb'>Nieuwe kosten</span>");
    }



    // filename doesn't get updated in input field. so need to do with jQuery
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    $('#switch_upload').on('click', function(e) {
      e.preventDefault();
      var attr = $(".custom-file-input").attr('capture');
      console.log(attr);
      // For some browsers, `attr` is undefined; for others,
      // `attr` is false.  Check for both.
      if (typeof attr == 'undefined' || attr == false) {
        $(".custom-file-input").attr('capture', 'environment');
        $(this).text("verander naar uploaden");
        $('.label_upload').text('Foto van bon maken');
      } else {
        $(".custom-file-input").removeAttr('capture');
        $(this).text("verander naar foto maken");
        $('.label_upload').text('Bon uploaden');
      }



    });
    let count_costs = 0;
    $('#add_cost').click(function(e) {
      e.preventDefault();
      count_costs++;
      let grab = $('#new_cost').html();
      $('#show_costs').append("<div class='extra_cost' id='" + count_costs + "_new_cost'>" + grab + "</div>")
        .ready(function() {

          if (hash) {
            $('#' + count_costs + "_new_cost .customer").find('option[value="' + customer + '"]').prop('selected', true);
          }

          $('#' + count_costs + "_new_cost").find('.btw_radio').each(function() {
            $(this).attr('name', count_costs + 'radio');
          });

          $('#' + count_costs + "_new_cost").find('.incl_btw').on('input', function() {
            change_excl_btw('#' + count_costs + "_new_cost");
          });
          $('#' + count_costs + "_new_cost").find('.excl_btw').on('input', function() {
            change_incl_btw('#' + count_costs + "_new_cost");
          });
          $('#' + count_costs + "_new_cost").find('.btw_radio').on('change', function() {
            change_excl_btw('#' + count_costs + "_new_cost");
          });

          $('#' + count_costs + "_new_cost .customer").on('change', function() {
            let id_c = $(this).val();
            $.post('/cost/new/get_offer_ajax', {
              id_customer: id_c
            }, function(result) {
              $('#' + count_costs + "_new_cost .offerte").find('option').remove();
              result = jQuery.parseJSON(result);
              $('#' + count_costs + "_new_cost .offerte").append($('<option>', {
                value: '0',
                text: 'Niet gelinkt aan offerte'
              }));
              for (let i = 0; i < result.length; i++) {

                $('#' + count_costs + "_new_cost .offerte").append($('<option>', {
                  value: result[i].id,
                  text: result[i].title
                }));
              }


            });
          });


          $('#' + count_costs + "_new_cost .write_off").on('change', function() {
            if ($(this).val() == '1') {
              $('#' + count_costs + "_new_cost #write_off_wrapper").fadeIn();
            } else {
              $('#' + count_costs + "_new_cost #write_off_wrapper").fadeOut();
            }

          });
        });
    });

    $('#save_cost').click(function(e) {
      e.preventDefault();
      let submit_button = $(this);
      submit_button.html('<i class="fa-solid fa-spinner"></i>').prop('disabled', true);
      if (typeof $('#image').prop('files')[0] !== 'undefined') {
        let formData = new FormData();

        formData.append('file', $('#image').prop('files')[0]);
        formData.append('date', $('#date').val());
        cost_data = [];

        $('.extra_cost').each(function() {

          cost_data.push({
            description: $(this).find('#description').val(),
            price: $(this).find('#price').val(),
            btw: $(this).find('.btw_radio:checked').val(),
            category: $(this).find("#category option:selected").val(),
            id_customer: $(this).find("#input_0 option:selected").val(),
            id_offerte: $(this).find("#input_1 option:selected").val(),
            write_off: $(this).find('.write_off:checked').val(),
            write_off_years: $(this).find('#write_off_years').val(),
            write_off_rest: $(this).find('#write_off_rest').val()
          });
        });

        formData.append('costs', JSON.stringify(cost_data));
        $.ajax({
          url: '/cost/new_ajax',
          type: 'post',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            submit_button.html('<i class="fa-solid fa-circle-check"></i>');
            if (response != 0) {
              message(response);
              setTimeout(function() {
                if (hash) {
                  window.location.replace("/customer/" + customer);
                } else {
                  window.location.replace("/cost");
                }
              }, 3500);

            } else {
              message("Excuus er ging iets fout.");
            }
          },
        });
      } else {
        message("Maak een foto of upload een bestand aub.");
      }
    });

  });
</script>




</body>

</html>