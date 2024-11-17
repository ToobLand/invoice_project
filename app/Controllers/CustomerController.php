<?php 

namespace App\Controllers;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\Cost;
use App\Models\Travel;
use App\Models\Hour;
use App\Models\Invoice;
use Symfony\Component\Routing\RouteCollection;
use function App\Helpers\GoogleApi\go_googleApi;

class CustomerController
{

	public function showList(RouteCollection $routes)
	{
      $customers=new Customer();
      
        $customers=$customers->get_all();
      
      require_once APP_ROOT . '/views/customer/customer.php';
	}

    // Show the product attributes based on the id.
	public function showNew(RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/customer/customerNew.php';
	}
    public function ajaxNew(RouteCollection $routes)
	{
		    // Sanitize and validation of data happens in ABSData.php 
    		// to do: user friendly to show realtime data validation in views. 
			$data=json_decode($_POST['data'], true);
			$customer= new Customer();
			$customer->firstname=$data['voornaam'];
			$customer->middlename=$data['tussenvoegsels'];
			$customer->lastname=$data['achternaam'];
			$customer->street=$data['straat'];
			$customer->postalcode=$data['postcode'];
			$customer->city=$data['stad'];
			$customer->country=$data['land'];
			$customer->housenumber=$data['huisnummer'];
			$customer->telephone=$data['telefoon'];
			$customer->email=$data['email'];
			$customer->save();
			echo "success";
	}
	public function showDetail($id, RouteCollection $routes)
	{
		$customer = new Customer($id);
		$offers=$customer->offer;

		foreach($offers as $index=>$offer){
			$cost=new Cost();
			$travel=new Travel();
			$hour=new Hour();
			$invoice=new Invoice();
			$km_total=$travel->get_total_customer_offer($customer->id, $offer->id);
			$cost_total=$cost->get_total_customer_offer($customer->id, $offer->id);
			$hour_total=$hour->get_total_customer_offer($customer->id, $offer->id);
			$invoice_total=$invoice->get_total_customer_offer($customer->id, $offer->id);
			$offers[$index]=[
					$offers[$index],
					( $cost_total ? $cost_total : "0"),
					( $km_total ? $km_total : "0"),
					( $hour_total ? $hour_total : "0"),
					( $invoice_total ? $invoice_total : "0")
				];

		}

      	require_once APP_ROOT . '/views/customer/customerDetail.php';
	}
	public function ajaxGetTravel($id, RouteCollection $routes)
	{
		$offer=new Offer($_POST['id_offer']);
		$travels=$offer->travel;
		echo json_encode($travels, true); 
	}
	public function ajaxSaveTravel($id, RouteCollection $routes)
	{
		$travel=new Travel();
		$travel->id_customer=$id;
		$travel->id_offer=$_POST['id_offer'];
		$travel->km=$_POST['km'];
		$travel->date=strtotime(str_replace('/','-',$_POST['date']) ." 00:00:00 GMT");;
		$travel->save();
		echo "success";
	}
	public function ajaxDeleteTravel($id, RouteCollection $routes)
	{
		$travel=new Travel($_POST['id']);
		$travel->delete();
		echo 'sucess';
	}
	public function ajaxGetHour($id, RouteCollection $routes)
	{
		$offer=new Offer($_POST['id_offer']);
		$hours=$offer->hour;
		echo json_encode($hours, true); 
	}
	public function ajaxSaveHour($id, RouteCollection $routes)
	{
		$hour=new Hour();
		$hour->id_customer=$id;
		$hour->id_offer=$_POST['id_offer'];
		$hour->hour=$_POST['hour'];
		$hour->date=strtotime(str_replace('/','-',$_POST['date']) ." 00:00:00 GMT");;
		$hour->save();
		echo "success";
	}
	public function ajaxDeleteHour($id, RouteCollection $routes)
	{
		$hour=new Hour($_POST['id']);
		$hour->delete();
		echo 'sucess';
	}
	public function showOffer($id, RouteCollection $routes)
	{
		$customer = new Customer($id);
		$offers=$customer->offer;
      require_once APP_ROOT . '/views/customer/customerOffer.php';
		


	}

    public function showOfferNew($id, RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/customer/customerOfferNew.php';
	}
	public function ajaxOfferNew($id, RouteCollection $routes)
	{
		$data=json_decode($_POST['data'], true);
		$offer= new Offer();
		$offer->title=$data['title'];
		$offer->id_customer=$id;

		$response=go_googleApi($offer->title);
		if(isset($response['message'])){
			echo 'authorization';
		}elseif(isset($response['error'])){
			echo 'error';
		}elseif(isset($response['id'])){
			$id_spreadsheet=$response['id'];
			$offer->google_id=$id_spreadsheet;
			$offer->save();
			echo 'success';
		}else{
			echo 'error?';
		}
		return true;
	}
	public function ajaxOfferTemplate($id, RouteCollection $routes)
	{
		
		$response=go_googleApi('Template voor offertes','new');
		if(isset($response['message'])){
			echo 'authorization';
		}elseif(isset($response['error'])){
			echo 'error';
		}elseif(isset($response['id'])){
			$id_spreadsheet=$response['id'];
			//$user=new User($_SESSION['user']->id);
			//$user->sheet_template=$id_spreadsheet;
			//$user->save();
			$_SESSION['user']->sheet_template=$id_spreadsheet;
			$_SESSION['user']->save();
			echo 'success';
		}else{
			echo 'error?';
		}
		return true;
	}
}