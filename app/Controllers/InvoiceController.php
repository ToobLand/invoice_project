<?php 

namespace App\Controllers;

use App\Models\Invoice;
use App\Models\Invoicepost;
use App\Models\Customer;
use App\Models\Offer;
use App\Database\Mysql;
use Symfony\Component\Routing\RouteCollection;

class InvoiceController
{

    public function showList($year,$q,RouteCollection $routes)
	{
      $invoice=new Invoice();
      if($year>0 && $q>0){
        $month="01";
        if($q==1){$month="01";$month2="03";}
        if($q==2){$month="04";$month2="06";}
        if($q==3){$month="07";$month2="09";}
        if($q==4){$month="10";$month2="12";}
        $date = new \DateTime($year.'-'.$month.'-01');
        $from= $date->getTimestamp();
        $date = new \DateTime($year.'-'.$month2.'-31');
        $till= $date->getTimestamp();
        $invoice=$invoice->get_from_till($from,$till,"date_send");
      }elseif($year>0){
        $date = new \DateTime($year.'-01-01');
        $from= $date->getTimestamp();
        $date = new \DateTime($year.'-12-31');
        $till= $date->getTimestamp();
        $invoice=$invoice->get_from_till($from,$till,"date_send");
      }else{
        $invoice=$invoice->get_all();
      }
      require_once APP_ROOT . '/views/invoice/invoice.php';
	}
  public function showListCustomer($id,$id_offer,RouteCollection $routes)
	{
      $invoice=new Invoice();
      $customer=new Customer($id);
      $offer=new Offer($id_offer);
      $invoice=$invoice->get_customer_offer($id,$id_offer);
      
      require_once APP_ROOT . '/views/invoice/invoice.php';
	}
    // Show the product attributes based on the id.
	public function showNew(RouteCollection $routes)
	{
      $customers=new Customer();
      $customers=$customers->get_all();  
      require_once APP_ROOT . '/views/invoice/invoiceNew.php';
	}
    public function ajaxNew(RouteCollection $routes)
	{
        $data=json_decode($_POST['data'], true);
        
        $invoice= new Invoice();
        // build invoice number
        $conn = new Mysql();
        //$res=$conn->fetchData("SELECT MAX(id) as maxId  FROM `{$invoice->table}`",[]);
        $res=$conn->fetchData("SELECT COUNT(id) as aantal  FROM `{$invoice->table}` WHERE id_user={$_SESSION['user']->id}",[]);
        
        
        $new_id= (int) $res[0]['aantal'] + 1;
        $number = $new_id . date("Y") . $data['klant'];
        
		// save main invoice in db
        $invoice->id_customer=$data['klant'];
        $invoice->id_offer=$data['offer'];
        $invoice->date_send=strtotime(str_replace('/','-',$data['datum']) ." 00:00:00 GMT");
        $invoice->number=$number;
        $invoice->save();

        $total_incl_btw=0;
        $total_excl_btw=0;
        // save posts of invoice in db
        foreach($data['posts'] as $post){
            $invoice_post=new Invoicepost();
            $invoice_post->id_invoice=$invoice->id;
            $invoice_post->title=$post['description'];
            $invoice_post->btw=$post['btw'];
            $invoice_post->amount=$post['amount'];
            $invoice_post->price_incl_btw=$post['price'];
            $invoice_post->price_excl_btw=round(($invoice_post->price_incl_btw/(($invoice_post->btw+100) / 100)),2);
            $invoice_post->save();

            $total_excl_btw+= $invoice_post->price_excl_btw;
            $total_incl_btw+= $invoice_post->price_incl_btw;
        }
        $invoice->price_incl_btw=$total_incl_btw;
        $invoice->price_excl_btw=$total_excl_btw;
        $invoice->save();
        echo 'success';
      

    }
    public function showPdf($id,RouteCollection $routes)
	{
      $invoice=new Invoice($id);
      require_once APP_ROOT . '/views/invoice/invoicePdf.php';
    }
    public function showEdit($id, RouteCollection $routes)
	{
      $invoice=new Invoice($id);
      if($invoice->id_offer>0){
        $offer=new Offer($invoice->id_offer);
      }else{
        $offer=false;
      }
      $customer=new Customer($invoice->id_customer);
      $offers=$customer->offer;
      $customers=new Customer();
      $customers=$customers->get_all();  
      $invoiceposts=$invoice->invoicepost;
      require_once APP_ROOT . '/views/invoice/invoiceEdit.php';
	}
  public function ajaxEdit(RouteCollection $routes)
	{
        $data=json_decode($_POST['data'], true);
        
        $invoice= new Invoice($data['id_invoice']);
        $invoice->id_customer=$data['klant'];
        $invoice->id_offer=$data['offer'];
        $invoice->date_send=strtotime(str_replace('/','-',$data['datum']) ." 00:00:00 GMT");
        $invoice->save();

        $total_incl_btw=0;
        $total_excl_btw=0;
        // save posts of invoice in db
        foreach($data['posts'] as $post){
          if($post['id']>0){
            $invoice_post=new Invoicepost($post['id']);
            if($post['deleted']=='1'){
              $invoice_post->delete();
              continue;
            }
          }else{
            $invoice_post=new Invoicepost();
          }
            
            $invoice_post->id_invoice=$invoice->id;
            $invoice_post->title=$post['description'];
            $invoice_post->btw=$post['btw'];
            $invoice_post->amount=$post['amount'];
            $invoice_post->price_incl_btw=$post['price'];
            $invoice_post->price_excl_btw=round(($invoice_post->price_incl_btw/(($invoice_post->btw+100) / 100)),2);
            $invoice_post->save();

            $total_excl_btw+= $invoice_post->price_excl_btw;
            $total_incl_btw+= $invoice_post->price_incl_btw;
        }
        $invoice->price_incl_btw=$total_incl_btw;
        $invoice->price_excl_btw=$total_excl_btw;
        $invoice->file_link="";
        $invoice->save();
        echo 'success';
      

    }
}