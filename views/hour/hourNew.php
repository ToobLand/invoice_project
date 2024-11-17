<?php

use function views\header\render_header;
use function App\Helpers\Functions\build_form;

echo render_header('Nieuwe uren invoeren');
?>
<div class="container">
    <h1>Uren:</h1>
    <section>
        <?php
        $cust_option[] = array('value' => 0, 'title' => 'Niet gekoppeld aan klant');
        foreach ($customers as $c) {
            $cust_option[] = array('value' => $c->id, 'title' => $c->firstname . ' ' . $c->middlename . " " . $c->lastname);
        }
        $offers[] = array('value' => 0, 'title' => 'Niet gekoppeld aan offerte');
        $array = array(
            array(
                'type' => 'number',
                'label' => 'uren',
                'placeholder' => 'aantal',
                'post' => 'hour'
            ),
            array(
                'type' => 'date_multi',
                'label' => 'datum',
                'placeholder' => 'datum',
                'post' => 'date'
            ),
            array(
                'type' => 'select',
                'label' => 'klant',
                'options' => $cust_option,
                'post' => 'customer'
            ),
            array(
                'type' => 'select',
                'label' => 'offerte',
                'options' => $offers,
                'post' => 'offer'
            )
        );
        echo build_form('/hour/new_ajax', '/hour', $array, 'uren opslaan');
        ?>

    </section>
</div>
<script>
    $(document).ready(function() {
        // $.fn.datepicker.defaults.multidate = true;

        $('#input_2').on('change', function() {
            let id_c = $(this).val();
            $.post('/cost/new/get_offer_ajax', {
                id_customer: id_c
            }, function(result) {
                $('#input_3').find('option').remove();
                result = jQuery.parseJSON(result);
                $('#input_3').append($('<option>', {
                    value: '0',
                    text: 'Niet gelinkt aan offerte'
                }));
                for (let i = 0; i < result.length; i++) {

                    $('#input_3').append($('<option>', {
                        value: result[i].id,
                        text: result[i].title
                    }));
                }


            });
        });
    });
</script>

</body>

</html>