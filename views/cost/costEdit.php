<?php

use function views\header\render_header;

echo render_header('Update kosten');

$customer = [];
foreach ($customers as $c) {
    $customer[] = ['value' => $c->id, 'title' => $c->firstname . " " . $c->middlename . " " . $c->lastname];
}
?>
<div class="container">
    <h1>Kosten updaten:</h1>
    <section>
        <div id='breadcrumb'></div>


        <form>
            <a href='/uploads/costs/<?php echo $cost->image; ?>' target='_blank'>
                <img style='margin: auto auto;
    position: relative;
    display: block;
    border: 3px solid white;
    box-shadow: 3px 3px 10px rgb(0 0 0 / 30%);
    max-width:1024px;
    max-height:500px;' src='/uploads/costs/<?php echo $cost->image; ?>'></a><br>
            <div class="form-group">
                <label for="description">Datum</label>
                <input type="date" value='<?php echo date('Y-m-d', strtotime($cost->date)); ?>' class="form-control" id="date" placeholder="Selecteer datum van aankoop">
            </div>
            <div class="form-group">
                <label for="description">BTW tarief</label>
                <div class="form-check">
                    <input class="form-check-input btw_radio" type="radio" name="btw" value='0' id="flexRadioDefault1" <?php echo ($cost->btw == 0 ? "checked" : ""); ?>>
                    <label class="form-check-label" for="flexRadioDefault1">
                        0%
                    </label>


                    <input style='position: relative;
    margin-left: 10px;' class="form-check-input btw_radio" type="radio" name="btw" value='9' id="flexRadioDefault2" <?php echo ($cost->btw == 9 ? "checked" : ""); ?>>
                    <label class="form-check-label" for="flexRadioDefault2">
                        9%
                    </label>


                    <input style='position: relative;
    margin-left: 10px;' class="form-check-input btw_radio" type="radio" name="btw" value='21' id="flexRadioDefault3" <?php echo ($cost->btw == 21 ? "checked" : ""); ?>>
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
                    <input type="number" value='<?php echo number_format((float)$cost->price, 2, '.', ''); ?>' step="0.01" class="form-control" id="price" placeholder="0,00">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Excl btw</span>
                    </div>
                    <?php
                    $excl_btw = round(($cost->price / (($cost->btw + 100) / 100)), 2)
                    ?>
                    <input type="number" value='<?php echo number_format((float)$excl_btw, 2, '.', ''); ?>' step="0.01" class="form-control" id="price_excl_btw" placeholder="0,00">
                </div>
            </div>
            <div class="form-group">
                <label for="description">Beschrijving</label>
                <input type="text" value='<?php echo $cost->description; ?>' class="form-control" id="description" aria-describedby="descriptionHelp" placeholder="Beschrijving">
                <small id="descriptionHelp" class="form-text text-muted">Beschrijving van kostenpost.</small>
            </div>
            <div class="form-group">
                <label for="description">Categorie</label>
                <Select class='custom-select category' id='category'>
                    <?php foreach ($categories as $catObject) {
                        echo "<option value='{$catObject->id}' " . ($cost->id_costcategory == $catObject->id ? "selected" : "") . ">{$catObject->title}</option>";
                    } ?>
                </Select>
            </div>


            <div class="form-group">
                <label for="input_0">Kosten horen bij klant:</label>
                <Select class="custom-select customer" id="input_0">
                    <option value='0' selected>Kostenpost niet gelinkt aan klant</option>
                    <?php

                    foreach ($customer as $c) {
                        echo "<option value='" . $c['value'] . "' " . ($cost->id_customer == $c['value'] ? "selected" : "") . ">" . $c['title'] . "</option>";
                    }
                    ?>
                </Select>
            </div>
            <div class="form-group">
                <label for="input_1">Kosten horen bij offerte:</label>
                <Select class="custom-select offerte" id="input_1">
                    <option value='0' selected>Kostenpost niet gelinkt aan offerte</option>
                    <?php
                    if (count($offers) > 0) {
                        foreach ($offers as $o) {
                            echo "<option value='" . $o->id . "' " . ($cost->id_offer == $o->id ? "selected" : "") . ">" . $o->title . "</option>";
                        }
                    } ?>
                </Select>
            </div>
            <div class="form-group">
                <label for="input_2">Kosten afschrijven?</label>
                <div class="form-check">
                    <input class="form-check-input write_off" type="radio" name="write_off" value='0' id="flexRadioDefaultwriteoff" <?php echo ($cost->write_off != 1 ? "checked" : ""); ?>>
                    <label class="form-check-label" for="flexRadioDefaultwriteoff">
                        Nee
                    </label>
                    <input style='position: relative;
    margin-left: 10px;' class="form-check-input write_off" type="radio" name="write_off" value='1' id="flexRadioDefaultwriteoff2" <?php echo ($cost->write_off == 1 ? "checked" : ""); ?>>
                    <label class="form-check-label" for="flexRadioDefaultwriteoff2">
                        Ja
                    </label>
                </div>
                <br>
                <div id='write_off_wrapper' style='<?php echo ($cost->write_off != 1 ? "display:none;" : ""); ?>'>
                    <div class="form-group">
                        <label for="write_off_years">In hoeveel jaar afschrijven?</label>
                        <input type="number" step="1" class="form-control" id="write_off_years" value='<?php echo $cost->write_off_years; ?>' placeholder="5">
                    </div>

                    <div class="form-group">
                        <label for="write_off_rest">Restwaarde na afschrijvingsperiode (excl btw)</label>
                        <input type="number" step="0.01" class="form-control" id="write_off_rest" value='<?php echo $cost->write_off_rest; ?>' placeholder="0,00">
                    </div>
                </div>
            </div>
            <div>
                <button style='float:right;' type="submit" id='save_cost' class="btn btn-primary">Kosten opslaan</button>
            </div>
        </form>



        <section>
</div>
<script>
    let hash = false;
    let customer, offer;
    $(document).ready(function() {


        $('#breadcrumb').html("<a class='crumb' href='/cost'>Kosten</a> <i class='fa-solid fa-angle-right'></i> " +
            "<span class='crumb'>Edit kosten</span>");

        $('#price').on('input',function(){
            change_excl_btw();
        });
        $('#price_excl_btw').on('input',function(){
            change_incl_btw();
        });
        $('.btw_radio').on('change',function(){
            change_excl_btw();
        });
        function change_excl_btw(){
            const incl = parseFloat($('#price').val().replace(',','.'));
            const btw = $('.btw_radio:checked').val();
            let excl = incl/((parseInt(btw)+100)/100);
            excl= excl.toFixed(2);
            $('#price_excl_btw').val(excl);
        }
        function change_incl_btw(){
            const excl = parseFloat($('#price_excl_btw').val().replace(',','.'));
            const btw = $('.btw_radio:checked').val();
            let incl = excl*((parseInt(btw)+100)/100);
            incl= incl.toFixed(2);
            $('#price').val(incl);
        }

        $(".customer").on('change', function() {
            let id_c = $(this).val();
            $.post('/cost/new/get_offer_ajax', {
                id_customer: id_c
            }, function(result) {
                $('.offerte').find('option').remove();
                result = jQuery.parseJSON(result);
                $('.offerte').append($('<option>', {
                    value: '0',
                    text: 'Niet gelinkt aan offerte'
                }));
                for (let i = 0; i < result.length; i++) {

                    $(".offerte").append($('<option>', {
                        value: result[i].id,
                        text: result[i].title
                    }));
                }


            });
        });


        $('.write_off').on('change', function() {
            if ($(this).val() == '1') {
                $('#write_off_wrapper').fadeIn();
            } else {
                $('#write_off_wrapper').fadeOut();
            }

        });
    });

    $('#save_cost').click(function(e) {
        e.preventDefault();
        let submit_button = $(this);
        submit_button.html('<i class="fa-solid fa-spinner"></i>').prop('disabled', true);

        let formData = new FormData();

        formData.append('date', $('#date').val());
        formData.append('id', <?php echo $id; ?>);


        const cost_data = {
            description: $('.container').find('#description').val(),
            price: $('.container').find('#price').val(),
            btw: $('.container').find('.btw_radio:checked').val(),
            category: $('.container').find("#category option:selected").val(),
            id_customer: $('.container').find("#input_0 option:selected").val(),
            id_offerte: $('.container').find("#input_1 option:selected").val(),
            write_off: $('.container').find('.write_off:checked').val(),
            write_off_years: $('.container').find('#write_off_years').val(),
            write_off_rest: $('.container').find('#write_off_rest').val()
        };


        formData.append('costs', JSON.stringify(cost_data));
        $.ajax({
            url: '/cost/edit_ajax',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                submit_button.html('<i class="fa-solid fa-circle-check"></i>');
                if (response != 0) {
                    message(response);
                    setTimeout(function() {

                        window.location.replace("/cost");

                    }, 2000);

                } else {
                    message("Excuus er ging iets fout.");
                }
            },
        });
    });
</script>




</body>

</html>