<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Models\Hour;
use App\Database\Mysql;
use Symfony\Component\Routing\RouteCollection;

class HourController
{

  public function showList($year, $q, RouteCollection $routes)
  {
    $hours = new Hour();
    if ($year > 0 && $q > 0) {
      $month = "01";
      if ($q == 1) {
        $month = "01";
        $month2 = "03";
      }
      if ($q == 2) {
        $month = "04";
        $month2 = "06";
      }
      if ($q == 3) {
        $month = "07";
        $month2 = "09";
      }
      if ($q == 4) {
        $month = "10";
        $month2 = "12";
      }
      $date = new \DateTime($year . '-' . $month . '-01');
      $from = $date->getTimestamp();
      $date = new \DateTime($year . '-' . $month2 . '-31');
      $till = $date->getTimestamp();
      $hours = $hours->get_from_till($from, $till, "date", 'date');
    } elseif ($year > 0) {
      $date = new \DateTime($year . '-01-01');
      $from = $date->getTimestamp();
      $date = new \DateTime($year . '-12-31');
      $till = $date->getTimestamp();
      $hours = $hours->get_from_till($from, $till, "date", 'date');
    } else {
      $hours = $hours->get_all('date');
    }

    // because we need to display a customer with every hour row. For each hour object a new query is done, so we optimize it here:
    // first get all the id_customers in an array
    if (count($hours) > 0) {
      $id_c_array = [];
      foreach ($hours as $h) {
        $id_c_array[] = $h->id_customer;
      }
      $id_c_array = array_unique($id_c_array);
      // now we get all the customers in one query
      $id_c_string = implode(',', $id_c_array);
      $conn = new Mysql();
      $result = $conn->fetchData("SELECT id, firstname, middlename,lastname FROM customer WHERE id IN(" . $id_c_string . ") ");
      // array we are gonna use in 'view' to show the names
      $customer_names = [];
      foreach ($result as $r) {
        $customer_names[$r['id']] = $r['firstname'] . ' ' . $r['middlename'] . ' ' . $r['lastname'];
      }
    }
    require_once APP_ROOT . '/views/hour/hour.php';
  }

  // Show the product attributes based on the id.
  public function showNew(RouteCollection $routes)
  {
    $customers = new Customer();
    $customers = $customers->get_all();
    require_once APP_ROOT . '/views/hour/hourNew.php';
  }
  public function ajaxNew(RouteCollection $routes)
  {
    $data = json_decode($_POST['data'], true);

    $amount = explode(',', $data['date']);
    foreach ($amount as $h) {
      $hour = new Hour();
      $hour->date = strtotime(str_replace('/', '-', $h) . " 00:00:00 GMT");
      $hour->hour = $data['hour'];
      $hour->id_customer = $data['customer'];
      $hour->id_offer = $data['offer'];

      $hour->save();
    }

    echo "success";
  }
}
