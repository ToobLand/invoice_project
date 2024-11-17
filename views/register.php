<?php

use function views\header\render_header;
use function App\Helpers\Functions\build_form;
if(!in_array($_SERVER['REMOTE_ADDR'],['204.168.160.31','84.105.234.216'])){
    die('Verboden toegang'); exit;
}
echo render_header('admin account aanmaken');
?>
<div class="container">
    <h1>Admin aanmaken:</h1>
    <section>
        <?php $array = array(
            array(
                'type' => 'text',
                'label' => 'voornaam',
                'placeholder' => 'voornaam',
                'post' => 'voornaam'
            ),
            array(
                'type' => 'text',
                'label' => 'tussenvoegsels',
                'placeholder' => 'tussenvoegsels',
                'post' => 'tussenvoegsels'
            ), array(
                'type' => 'text',
                'label' => 'achternaam',
                'placeholder' => 'achternaam',
                'post' => 'achternaam'
            ), array(
                'type' => 'text',
                'label' => 'email',
                'placeholder' => 'email',
                'post' => 'email'
            ), array(
                'type' => 'text',
                'label' => 'password',
                'placeholder' => 'password',
                'post' => 'password'
            ), array(
                'type' => 'text',
                'label' => 'street',
                'placeholder' => 'straat',
                'post' => 'street'
            ), array(
                'type' => 'text',
                'label' => 'huisnummer',
                'placeholder' => 'huisnummer',
                'post' => 'housenumber'
            ), array(
                'type' => 'text',
                'label' => 'stad',
                'placeholder' => 'stad',
                'post' => 'city'
            ), array(
                'type' => 'text',
                'label' => 'postcode',
                'placeholder' => 'postcode',
                'post' => 'postalcode'
            ), array(
                'type' => 'text',
                'label' => 'btw nummer',
                'placeholder' => 'btw nummer',
                'post' => 'btw'
            ), array(
                'type' => 'text',
                'label' => 'kvk nummer',
                'placeholder' => 'kvk nummer',
                'post' => 'kvk'
            ), array(
                'type' => 'text',
                'label' => 'Iban',
                'placeholder' => 'iban',
                'post' => 'iban'
            ), array(
                'type' => 'text',
                'label' => 'bedrijfsnaam',
                'placeholder' => 'bedrijfsnaam',
                'post' => 'company'
            )
        );
        echo build_form('register_ajax', '/login', $array, 'opslaan');
        ?>

    </section>
</div>
</body>

</html>