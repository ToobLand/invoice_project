<?php

use function views\header\render_header;
use Google\Client;
use Google\Service\Docs;
use function App\Helpers\Functions\is_local;

/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */
echo render_header('Overzicht kosten, omzet en winst');
?>
<div class="container" style='padding-bottom:0px;padding-top:30px;'>
<button id='print' class='btn btn-secondary'>Printen</button>
</div>
<div class="container" id='section-to-print'>
    <?php
    echo "<div style='margin-bottom:40px; background:#ededed;padding:20px;text-align:right;'>Periode 01-01-{$year} t/m 31-12-{$year}</div>";

    echo "  <table cellpadding='5px' style='width:100%'>
    <tr><td><h4>Opbrengsten</h4></td></tr>";

    echo "<tr><td></td><td>Gefactureerde omzet</td><td>{$total_excl_btw}</td></tr>";
    echo "<tr><td></td><td style='border-top:1px solid #333333;'>Totaal opbrengsten</td><td style='border-top:1px solid #333333;'><b>{$total_excl_btw}</b></td></tr>";
    echo "<tr><td></td></tr>";
    echo "<tr><td><h4>Bedrijfskosten</h4></td></tr>";
    $total = 0;
    if (isset($costs_total) && count($costs_total) > 0) {

        foreach ($costs_total as $key => $val) {
            echo "<tr><td></td><td>$key</td><td>$val</td></tr>";
            $total += $val;
        }
        echo "<tr><td></td></tr>";
        echo "<tr><td></td><td style='border-top:1px solid #333333;'>Totaal kosten</td><td style='border-top:1px solid #333333;'><b>$total</b></td></tr>";
    }
    $profit = round($total_excl_btw - $total,2);
    echo "<tr><td></td><td></td></tr>";
    echo "<tr><td><h4>Resultaat voor belasting</h4></td><td></td></tr>";
    echo "<tr><td></td><td style='border-top:3px double #333333;'>Winst</td><td style='border-top:3px double #333333;'><b>{$profit}</b></td></tr>";

    ?>
    </table>

</div>
<script>
    $(document).ready(function() {
        
        $('#print').click(function() {
            window.print();  
        });
    });
</script>
</body>

</html>