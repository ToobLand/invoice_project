<?php

use App\Database\Mysql;
use Dompdf\Dompdf;
    use Dompdf\Options;

    $options = new Options();
    $options->set('defaultFont', 'Roboto');
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->setIsRemoteEnabled(true);
    $dompdf = new Dompdf($options);

function get_base64_img(){
    $path = SITE_ROOT . "/resources/" . $_SESSION['user']->logo;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);

	$base64 = "";
	if ($type == "svg") {
		$base64 = "data:image/svg+xml;base64,".base64_encode($data);
	} else {
		$base64 = "data:image/". $type .";base64,".base64_encode($data);
	}
	return $base64;
}



    function get_posts_for_invoice( int $id ){
        $posts=new Mysql();
        return $posts->fetchData("SELECT * FROM `invoicepost` WHERE id_invoice=?",[$id]);
    }


$html="<html>
<header>
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');
body,html{
    font-family: 'Roboto', sans-serif;
}
</style>
</header><body><div style='margin-left:20px;'>
<div style='width:100%;height:200px;'>
        <div style='float:left;width:500px; height:150px;font-color:#ffffff;font-size:45px;'>
        <img src='".get_base64_img()."' height='150px'>
        </div>
       
        <div style='width:250px;float:right;'>
        {$_SESSION['user']->company}<br>
        {$_SESSION['user']->street} {$_SESSION['user']->housenumber}<Br>
        {$_SESSION['user']->postalcode} {$_SESSION['user']->city}<br><br>
        BTW: {$_SESSION['user']->btw}<br>
        KVK: {$_SESSION['user']->kvk}<br>
        IBAN: {$_SESSION['user']->iban}
        </div>
</div>

<div style='width:300px;'>
{$invoice->customer->firstname} {$invoice->customer->middlename} {$invoice->customer->lastname}<br>
{$invoice->customer->street} {$invoice->customer->housenumber}<br>
{$invoice->customer->postalcode} {$invoice->customer->city}<br><br>
</div>

<div style='width:100%;height:100px;'>
        <div style='float:left;width:200px;'>
        <b>Factuur</b><br>
        Factuurnummer: {$invoice->number}<br>
        </div>

        <div style='float:right;width:250px;'>
        Factuurdatum: " . ( date("d-m-Y",strtotime($invoice->date_send)) ) . "<br>
        Vervaldatum: " . ( date("d-m-Y",strtotime('+14 days',strtotime($invoice->date_send))) ) . "<br>
        </div>
</div>
<div style='width:90%;padding-right:50px;'>
    <div style='padding-left:20px;padding-right:20px;background:#333333;width:100%;height:45px'>
        <div style='width:100px;float:left;height:20px;color:white;padding-top:10px;'>Aantal</div>
        <div style='width:300px;float:left;height:20px;color:white;padding-top:10px;'>Omschrijving</div>
        <div style='width:50px;float:left;height:20px;color:white;padding-top:10px;'>BTW</div>
        <div style='width:120px;float:right;height:20px;color:white;padding-top:10px;'>Prijs excl BTW</div>
    </div>
";
$posts=get_posts_for_invoice($invoice->id);
foreach($posts as $post){
    $html.="<div style='padding-top:10px;padding-left:20px;padding-right:20px;width:100%;height:35px;border-bottom:1px solid #666666;'>
    <div style='width:100px;float:left;height:20px;'>{$post['amount']}</div>
    <div style='width:300px;float:left;height:20px;'>{$post['title']}</div>
    <div style='width:50px;float:left;height:20px;'>{$post['btw']}%</div>
    <div style='width:120px;float:right;height:20px;'>&euro;".number_format((float)$post['price_excl_btw'], 2, ',', '.')."</div>
</div>";
}
$btw_total=$invoice->price_incl_btw - $invoice->price_excl_btw;
if($_SESSION['user']->kor!=1){ 
$html.="<div style='padding-top:10px;padding-left:20px;padding-right:20px;width:100%;height:30px;'>
        <div style='width:450px;float:left;height:30px;text-align:right;'>Subtotaal</div>
        <div style='width:120px;float:right;height:30px;'>&euro;".number_format((float)$invoice->price_excl_btw, 2, ',', '.')."</div>
    </div>";
    
$html.="<div style='padding-top:5px;padding-left:20px;padding-right:20px;width:100%;height:30px;'>
        <div style='width:450px;float:left;height:30px;text-align:right;'>BTW</div>
        <div style='width:120px;float:right;height:30px;'>&euro;".number_format((float)$btw_total, 2, ',', '.')."</div>
    </div>";
}
$html.="<div style='padding-top:5px;padding-left:20px;padding-right:20px;width:100%;height:35px;'>
        <div style='width:450px;float:left;padding-top:5px;height:35px;text-align:right;font-weight:bold;'>Totaal</div>
        <div style='width:120px;float:right;padding-top:5px;height:35px;" . ($_SESSION['user']->kor!=1 ? 'border-top:1px solid black;':'') . "'>&euro;".number_format((float)$invoice->price_incl_btw, 2, ',', '.')."</div>
    </div>";
    if($_SESSION['user']->kor==1){ 
        $html.="<div style='padding-top:10px;padding-left:20px;padding-right:20px;width:100%;height:35px;'>
        
       
            <div style='width:210px;float:right;height:35px;font-size:12px;'><i>Vrijgesteld van BTW</i></div>
            </div>";
       }
    $html.="
</div>
<div style='width:90%;padding:20px;border:1px dotted #666666;margin-top:50px;'>
Wij verzoeken u vriendelijk het bedrag van &euro;".number_format((float)$invoice->price_incl_btw, 2, ',', '.')." binnen 14 dagen 
over te maken naar rekeningnummer {$_SESSION['user']->iban} ten name van {$_SESSION['user']->company} onder vermelding van het factuurnummer {$invoice->number}.
</div>
</div>
</body>
</html>
";

    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();
    $output = $dompdf->output();
    $file_name="Factuur_" . $_SESSION['user']->id . "_" . $invoice->number.".pdf";

    if (!file_exists(SITE_ROOT . "/uploads/invoice")) {
        mkdir(SITE_ROOT . "/uploads/invoice", 0755, true);
    }

    file_put_contents(SITE_ROOT . "/uploads/invoice/".$file_name, $output);
    $invoice->file_link=$file_name;
    $invoice->save();
    // Output the generated PDF to Browser
    $dompdf->stream($file_name);
