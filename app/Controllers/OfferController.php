<?php 

namespace App\Controllers;
use App\Models\Customer;
use App\Models\Offer;
use Symfony\Component\Routing\RouteCollection;
use function App\Helpers\GoogleApi\go_googleApi;

class OfferController {

public function showList(RouteCollection $routes)
	{
		$offers=new Offer();
		$offers=$offers->get_all();
      require_once APP_ROOT . '/views/offer/offer.php';
		


	}

    public function showNew(RouteCollection $routes)
	{
        $customers=new Customer();
        $customers=$customers->get_all();
      require_once APP_ROOT . '/views/offer/offerNew.php';
	}
	public function ajaxNew(RouteCollection $routes)
	{
		$data=json_decode($_POST['data'], true);
		$offer= new Offer();
		$offer->title=$data['title'];
		$offer->id_customer=$data['customer'];
		$type='';
		if($_SESSION['user']->sheet_template!=''){
			$type='copy';
		}else{
			$type='new';
		}
		if($data['spreadsheet']==1){
			$response=go_googleApi($offer->title,$type);
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
		}else{
			$offer->google_id='';
			$offer->save();
			echo 'success';
		}
		
		return true;
	}
	public function ajaxTemplate(RouteCollection $routes)
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