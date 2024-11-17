<?php

use function views\header\render_header;
use function App\Helpers\Functions\build_form;
echo render_header('Nieuwe kosten invoeren');
?>
<div class="container">
<h1>Klanten:</h1>
<section>
<?php 
$array=array( 
        array(
        'type' => 'text',
        'label'=>'voornaam',
        'placeholder'=>'voornaam',
        'post'=>'voornaam'
        ),
        array(
            'type' => 'text',
            'label'=>'tussenvoegsels',
            'placeholder'=>'tussenvoegsels',
            'post'=>'tussenvoegsels'
            ),
        array(
                'type' => 'text',
                'label'=>'achternaam',
                'placeholder'=>'achternaam',
                'post'=>'achternaam'
                ),
        array(
                    'type' => 'text',
                    'label'=>'straat',
                    'placeholder'=>'straat',
                    'post'=>'straat'
                    ),
        array(
                        'type' => 'text',
                        'label'=>'huisnummer',
                        'placeholder'=>'huisnummer',
                        'post'=>'huisnummer'
                        ),
                        array(
                            'type' => 'text',
                            'label'=>'postcode',
                            'placeholder'=>'postcode',
                            'post'=>'postcode'
                            ),
                            array(
                                'type' => 'text',
                                'label'=>'stad',
                                'placeholder'=>'stad',
                                'post'=>'stad'
                                ),
                                array(
                                    'type' => 'text',
                                    'label'=>'land',
                                    'placeholder'=>'land',
                                    'post'=>'land'
                                    ),
                                    array(
                                        'type' => 'text',
                                        'label'=>'email',
                                        'placeholder'=>'email',
                                        'post'=>'email'
                                        ),
                                        array(
                                            'type' => 'text',
                                            'label'=>'telefoon',
                                            'placeholder'=>'telefoon',
                                            'post'=>'telefoon'
                                            )
        
);
echo build_form('/customer/new_ajax','/customer', $array, 'Klant opslaan');
?>

</section>
</div></body>
</html>