<?php

use function views\header\render_header;
use function App\Helpers\Functions\build_form;

echo render_header('Nieuwe factuur invoeren');
?>
<div class="container">
    <h1>factuur maken:</h1>
    <div id='breadcrumb'>
    <a class='crumb' href='/invoice'>Facturen</a> <i class='fa-solid fa-angle-right'></i><span class='crumb'>Nieuwe factuur</span>
            </div>

    <section>
        <?php
        $customer = [];
        foreach ($customers as $c) {
            $customer[] = ['value' => $c->id, 'title' => $c->firstname . " " . $c->middlename . " " . $c->lastname];
        }
        ?>
        <div class="form-group">
            <label for="input_0">Welke klant</label>
            <Select class="custom-select category" id="input_0">
                <option disabled selected>Kies een klant</option>
                <?php

                foreach ($customer as $c) {
                    echo "<option value='" . $c['value'] . "'>" . $c['title'] . "</option>";
                }
                ?>
            </Select>
        </div>
        <div class="form-group">
            <label for="input_offer">Welke offerte</label>
            <Select class="custom-select category" id="input_offer">
                <option value='0'>Niet gelinkt aan offerte</option>
            </Select>
        </div>
        <div class="form-group">
            <label for="input_1">datum</label>
            <input type="date" class="form-control" id="input_1" placeholder="datum">
        </div>
        <table class='table'>
            <tr>
                <td width="150px">Aantal</td>
                <td width="400px">Beschrijving</td>
                <td width="90px">BTW</td>
                <td width="120px">Prijs excl btw</td>
                <td width="120px">Prijs incl btw</td>
                <td width="60px">Delete</td>
            </tr>

            <?php
            $counter = 1;
            ?>
            <tr data-counter='0' class='0_post post' id='0'>
                <td class='0_amount'>
                    <input data-counter='0' type='text' class='form-control amount' id='amount' placeholder='Nvt of X uur'>
                </td>
                <td class='0_title'>
                    <input data-counter='0' type='text' class='form-control title_text' id='title' placeholder='Bijv: Kosten materiaal, arbeid of advies'>
                </td>
                <td class='0_btw'>
                    <select data-counter='0' class='btw_select 0_btw_select'>
                        <option data-counter='0' value='21' selected>21%</option>
                        <option data-counter='0' value='9'>9%</option>
                        <option data-counter='0' value='0'>0%</option>
                    </select>
                </td>
                <td class='0_excl_btw'>
                    <input data-counter='0' type='number' step='0.01' class='form-control excl_btw' id='price_excl_btw' placeholder='0,00'>
                </td>
                <td class='0_incl_btw'>
                    <input data-counter='0' type='number' step='0.01' class='form-control incl_btw' id='price_incl_btw' placeholder='0,00'>
                </td>
                <td></td>
            </tr>
            <tr style='display:none;' class='new_post' id='0'>
                <td class='[new]_amount'>
                    <input data-counter='[new]' type='text' class='form-control amount' id='amount' placeholder='Nvt of X uur'>
                </td>
                <td class='[new]_title'>
                    <input data-counter='[new]' type='text' class='form-control title_text' id='title' placeholder='Bijv: Kosten materiaal, arbeid of advies'>
                </td>
                <td class='[new]_btw'>
                    <select data-counter='[new]' class='btw_select [new]_btw_select'>
                        <option data-counter='[new]' value='21' selected>21%</option>
                        <option data-counter='[new]' value='9'>9%</option>
                        <option data-counter='[new]' value='0'>0%</option>
                    </select>
                </td>
                <td class='[new]_excl_btw'>
                    <input data-counter='[new]' type='number' step='0.01' class='form-control excl_btw' id='price_excl_btw' placeholder='0,00'>
                </td>
                <td class='[new]_incl_btw'>
                    <input data-counter='[new]' type='number' step='0.01' class='form-control incl_btw' id='price_incl_btw' placeholder='0,00'>
                </td>
                <td><i data-counter='[new]' class='fa-solid fa-trash-can [new]_delete delete'></i></td>
            </tr>
        </table>

        <a data-toggle="tooltip" data-placement="right" title='Extra post toevoegen' style='width:40px;margin-top:-20px; cursor:pointer; display: block;' class="add_extra_post"><i style='font-size: 35px; color: #00d024;' class="fa-solid fa-circle-plus"></i></a>
        <br><br>
        <button type='submit' id='send_form' class='btn btn-primary'>Factuur opslaan</button>
        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();
                let counter = <?php echo $counter; ?>;

                function change_excl_btw(counter_value) {
                    const incl = parseFloat($("." + counter_value + "_incl_btw .incl_btw").val().replace(',', '.'));
                    const btw = $("." + counter_value + "_btw_select").val();
                    let excl = incl / ((parseInt(btw) + 100) / 100);
                    excl = excl.toFixed(2);
                    $("." + counter_value + "_excl_btw").find('.excl_btw').val(excl);
                }

                function change_incl_btw(counter_value) {
                    console.log(counter_value);
                    const excl = parseFloat($("." + counter_value + "_excl_btw .excl_btw").val().replace(',', '.'));
                    const btw = $("." + counter_value + "_btw_select").val();
                    let incl = excl * ((parseInt(btw) + 100) / 100);
                    incl = incl.toFixed(2);
                    $("." + counter_value + "_incl_btw").find('.incl_btw').val(incl);
                }


                $('#input_0').on('change', function() {
                    let id_c = $(this).val();
                    $.post('/cost/new/get_offer_ajax', {
                        id_customer: id_c
                    }, function(result) {
                        $('#input_offer').find('option').remove();
                        result = jQuery.parseJSON(result);
                        $('#input_offer').append($('<option>', {
                            value: '0',
                            text: 'Niet gelinkt aan offerte'
                        }));
                        for (let i = 0; i < result.length; i++) {

                            $('#input_offer').append($('<option>', {
                                value: result[i].id,
                                text: result[i].title
                            }));
                        }


                    });
                });

                $('table').find('.incl_btw').on('input', function() {
                    const elem_name = $(this).data('counter');
                    change_excl_btw(elem_name);
                });
                $('table').find('.excl_btw').on('input', function() {
                    const elem_name = $(this).data('counter');
                    change_incl_btw(elem_name);
                });
                $('table').find('.btw_select').on('change', function(e) {
                    const elem_name = $(this).data('counter');

                    change_incl_btw(elem_name);
                });
                $('.delete').click(function(e) {
                    e.preventDefault();
                    let counterpost = $(this).data('counter');
                    $("." + counterpost + '_post').fadeOut().addClass('deleted');
                });

                $('.add_extra_post').click(function(e) {
                    e.preventDefault();
                    let html = $('.new_post').html().replace(/\[new\]/g, counter);
                    $('table').append("<tr class='" + counter + "_post post' id='0' data-counter='" + counter + "'>" + html + "</tr>");

                    $('table').find('.' + counter + '_post .incl_btw').on('input', function() {
                        const elem_name = $(this).data('counter');
                        change_excl_btw(elem_name);
                    });
                    $('table').find('.' + counter + '_post .excl_btw').on('input', function() {
                        const elem_name = $(this).data('counter');
                        change_incl_btw(elem_name);
                    });
                    $('table').find('.' + counter + '_post .btw_select').on('change', function(e) {
                        const elem_name = $(this).data('counter');

                        change_incl_btw(elem_name);
                    });
                    $('.' + counter + '_post .delete').click(function(e) {
                        e.preventDefault();
                        let counterpost = $(this).data('counter');
                        $("." + counterpost + '_post').remove();
                    });

                    counter++;
                });

                $("#send_form").on("click", function(e) {
                    e.preventDefault();
                    let data = {};
                    data["klant"] = $("#input_0 option:selected").val();
                    data["offer"] = $("#input_offer option:selected").val();
                    data["datum"] = $("#input_1").val();
                    let extra_post = [];
                    $('.post').each(function() {
                        let postcounter = $(this).data('counter');
                        extra_post.push({
                            description: $('.' + postcounter + '_title .title_text').val(),
                            price: $('.' + postcounter + '_incl_btw .incl_btw').val(),
                            amount: $('.' + postcounter + '_amount .amount').val(),
                            btw: $('.' + postcounter + '_btw_select').val(),
                        });

                    });

                    data['posts'] = extra_post;
                    data = JSON.stringify(data);
                    $.post("/invoice/new_ajax", {
                        data: data
                    }, function(response, status) {

                        if (status == "success") {
                            $("#send_form").prop("disabled", true);
                        }
                        delete data;

                        message(response);
                        setTimeout(function() {
                            window.location.replace("/invoice");
                        }, 1600);


                    });

                })
            });
        </script>

    </section>
</div>
</body>

</html>