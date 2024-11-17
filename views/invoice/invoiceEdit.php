<?php

use function views\header\render_header;
use function App\Helpers\Functions\build_form;

echo render_header('Factuur wijzigen');
?>
<div class="container">
    <h1>factuur wijzigen: <?php echo $invoice->number; ?></h1>
    <section>
    <div id='breadcrumb'>
    <a class='crumb' href='/invoice'>Facturen</a> <i class='fa-solid fa-angle-right'></i><span class='crumb'>Edit factuur</span>
            </div>
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
                    echo "<option value='" . $c['value'] . "' " . ($invoice->id_customer == $c['value'] ? 'selected' : '') . ">" . $c['title'] . "</option>";
                }
                ?>
            </Select>
        </div>
        <div class="form-group">
            <label for="input_offer">Welke offerte</label>
            <Select class="custom-select category" id="input_offer">
                <?php
                echo "<option value='0' " . ($invoice->id_offer == 0 ? 'selected' : '') . ">Niet gelinkt aan offerte</option>";

                if (count($offers) > 0) {
                    foreach ($offers as $o) {
                        echo "<option value='" . $o->id . "' " . ($o->id == $invoice->id_offer ? 'selected' : '') . ">" . $o->title . "</option>";
                    }
                }
                ?>
            </Select>
        </div>
        <?php
        $date = strtotime($invoice->date_send);
        $days = date('d', $date);
        $month = date('m', $date);
        $year = date('Y', $date);
        ?>
        <div class="form-group">
            <label for="input_1">datum factuur</label>
            <input value='<?php echo $year . '-' . $month . '-' . $days; ?>' type="date" class="form-control" id="input_1" placeholder="datum">
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
            $counter = 0;
            if (count($invoiceposts) > 0) {

                foreach ($invoiceposts as $post) {
                    echo "<tr class='{$counter}_post post' data-counter='{$counter}' id='{$post->id}'><td class='{$counter}_amount'>
            <input data-id='{$post->id}' data-counter='{$counter}' type='text' value='{$post->amount}' class='form-control amount' id='amount' placeholder='Aantal (bijv: 10 uren)'>
            </td><td class='{$counter}_title'>
                <input data-id='{$post->id}' data-counter='{$counter}' type='text' value='{$post->title}' class='form-control title_text' id='title' placeholder='omschrijving werkzaamheden/levering'>
            </td>
            <td class='{$counter}_btw'>
            
            <select data-id='{$post->id}' data-counter='{$counter}' class='btw_select {$counter}_btw_select'>
                <option data-id='{$post->id}' data-counter='{$counter}' value='21' " . ($post->btw == 21 ? "selected" : "") . ">21%</option>
                <option data-id='{$post->id}' data-counter='{$counter}' value='9' " . ($post->btw == 9 ? "selected" : "") . ">9%</option>
                <option data-id='{$post->id}' data-counter='{$counter}' value='0' " . ($post->btw == 0 ? "selected" : "") . ">0%</option>
            </select>
            
               </td><td class='{$counter}_excl_btw'>
                    <input data-id='{$post->id}' data-counter='{$counter}' type='number' step='0.01' value='{$post->price_excl_btw}' class='form-control excl_btw' id='price_excl_btw' placeholder='0,00'>
               </td><td class='{$counter}_incl_btw'>
               <input data-id='{$post->id}' data-counter='{$counter}' type='number' step='0.01' value='{$post->price_incl_btw}' class='form-control incl_btw' id='price_incl_btw' placeholder='0,00'>
           </td><td>" . ($counter > 0 ? "<i data-counter='{$counter}' class='fa-solid fa-trash-can {$counter}_delete delete' ></i>" : "") . "</tr>

        ";
                    $counter++;
                }
            }
            ?>
            <tr style='display:none;' class='new_post' id='0'>
                <td class='[new]_amount'>
                    <input data-counter='[new]' type='text' class='form-control amount' id='amount' placeholder='Aantal (bijv: 10 uren)'>
                </td>
                <td class='[new]_title'>
                    <input data-counter='[new]' type='text' class='form-control title_text' id='title' placeholder='omschrijving werkzaamheden/levering'>
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
        
        <a data-toggle="tooltip" data-placement="right" title='Extra post toevoegen' style='width:40px;margin-top:-20px; cursor:pointer; display: block;' class="add_extra_post" ><i style='font-size: 35px; color: #00d024;' class="fa-solid fa-circle-plus"></i></a>
        <br><br><button type='submit' id='send_form' class='btn btn-primary'>Wijzigingen factuur opslaan</button>
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
                    data["id_invoice"] = <?php echo $invoice->id; ?>;
                    let extra_post = [];
                    $('.post').each(function() {
                        let deleted = 0;
                        let postcounter = $(this).data('counter');
                        if ($(this).hasClass('deleted')) {
                            console.log($(this).attr('id') + " is deleted");
                            deleted = 1;
                        } 

                            extra_post.push({
                                deleted: deleted,
                                id: $(this).attr('id'),
                                description: $('.' + postcounter + '_title .title_text').val(),
                                price: $('.' + postcounter + '_incl_btw .incl_btw').val(),
                                amount: $('.' + postcounter + '_amount .amount').val(),
                                btw: $('.' + postcounter + '_btw_select').val(),
                            });
                        
                    });
                    data['posts'] = extra_post;
                    data = JSON.stringify(data);
                    console.log(data);
                     $.post("/invoice/edit_ajax", {
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