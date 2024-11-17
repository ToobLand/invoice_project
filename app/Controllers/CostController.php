<?php

namespace App\Controllers;

use App\Models\Cost;
use App\Models\Costcategory;
use App\Models\Customer;
use App\Models\Offer;
use App\Database\Mysql;
use Symfony\Component\Routing\RouteCollection;

class CostController
{

  // Show the product attributes based on the id.
  public function showList($year, $q, RouteCollection $routes)
  {
    $categories = new Costcategory();
    $categories = $categories->get_all();
    $costs = new Cost();
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
      $costs = $costs->get_from_till($from, $till, "date", 'date');
    } elseif ($year > 0) {
      $date = new \DateTime($year . '-01-01');
      $from = $date->getTimestamp();
      $date = new \DateTime($year . '-12-31');
      $till = $date->getTimestamp();
      $costs = $costs->get_from_till($from, $till, "date", 'date');
    } else {
      $costs = $costs->get_all('date');
    }

    // because we need to display a customer with every cost row. For each cost object a new query is done, so we optimize it here:
    // first get all the id_customers in an array
    if (count($costs) > 0) {
      $id_c_array = [];
      foreach ($costs as $c) {
        $id_c_array[] = $c->id_customer;
      }
      $id_c_array = array_unique($id_c_array);
      // now we get all the customers in one query
      $id_c_string = implode(',', $id_c_array);
      $conn = new Mysql();
      $result = $conn->fetchData("SELECT id, firstname, middlename,lastname FROM customer WHERE id IN(" . $id_c_string . ") ");
      // array we are gonna use in 'view' to show the names
      $customer_names = [];
      foreach ($result as $r) {
        $customer_names[$r['id']] = substr($r['firstname'], 0, 1) . '. ' . $r['lastname'];
      }
    }


    require_once APP_ROOT . '/views/cost/cost.php';
  }

  // Show the product attributes based on the id.
  public function showNew(RouteCollection $routes)
  {
    $categories = new Costcategory();
    $categories = $categories->get_all();
    $customers = new Customer();
    $customers = $customers->get_all();
    require_once APP_ROOT . '/views/cost/costNew.php';
  }
  public function showEdit($id, RouteCollection $routes)
  {
    $categories = new Costcategory();
    $categories = $categories->get_all();
    $customers = new Customer();
    $customers = $customers->get_all();
    $cost = new Cost($id);
    $offers = $cost->customer->offer;
    require_once APP_ROOT . '/views/cost/costEdit.php';
  }
  public function ajaxGetOffer(RouteCollection $routes)
  {
    $customers = new Customer($_POST['id_customer']);
    $offers = $customers->offer;
    echo json_encode($offers, true);
  }
  public function ajaxPreSelect()
  {
    $customer = new Customer($_POST['id_customer']);
    $offer = new Offer($_POST['id_offer']);
    echo json_encode([$customer, $offer], true);
  }
  public function ajaxNew(RouteCollection $routes)
  {

    // Sanitize and validation of data happens in ABSData.php 
    // to do: user friendly to show realtime data validation in views. 

    $message = '';
    if (!file_exists(SITE_ROOT . "/uploads/costs")) {
      mkdir(SITE_ROOT . "/uploads/costs", 0755, true);
    }
    $target_dir = SITE_ROOT . "/uploads/costs/";
    $imageFileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    // save unique filename. (never problems with characters, same names etcetera)
    $filename = date('d-m-Y-', time()) . mt_rand(1, 999999) . time() . mt_rand(1, 999999) . "." . $imageFileType;
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
      $uploadOk = 1;
    } elseif ($imageFileType == "application/pdf" || $imageFileType == "pdf") {
      $uploadOk = 1;
    } else {
      echo $imageFileType . " - File is geen plaatje of PDF.";
      $uploadOk = 0;
      exit;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
      exit;
    }

    if (
      $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" && $imageFileType != "application/pdf" && $imageFileType != "pdf"
    ) {
      echo "Sorry, alleen JPG, JPEG, PNG, GIF & PDF files zijn toegestaan.";
      $uploadOk = 0;
      exit;
    }

    function compress($destination, $percent)
    {

      $info = getimagesize($_FILES["file"]["tmp_name"]);
      list($width, $height, $type) = getimagesize($_FILES["file"]["tmp_name"]);

      $newwidth = $width * $percent;
      $newheight = $height * $percent;
      if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);

      elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($_FILES["file"]["tmp_name"]);

      elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($_FILES["file"]["tmp_name"]);



      $thumb = imagecreatetruecolor($newwidth, $newheight);

      // Resize
      imagecopyresized($thumb, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

      // Output
      //imagejpeg($thumb,'new.jpeg');
      imagejpeg($thumb, $destination);
      return "Succesvolle upload. Plaatje verkleind en gecomprimeerd opgeslagen.";
    }

    if ($_FILES["file"]["size"] > 900000 && ($imageFileType == "application/pdf" || $imageFileType == "pdf")) {
      echo " Upload geanulleerd. PDF bestand is veel te groot. Beter om een screenshot te maken of foto met mobiel";
      exit;
    } elseif ($_FILES["file"]["size"] > 700000 && $imageFileType != "application/pdf" && $imageFileType != "pdf") {
      // ALS PLAATJE, CHECK GROOTTE EN COMPRIMEER ZO NODIG. 
      // IN COMPRESSIE FUNCTIE WORD FILE OPGESLAGEN
      if ($_FILES["file"]["size"] > 7000000) {
        echo " Upload geanulleerd. Plaatje veel te groot.";
        exit;
      } elseif ($_FILES["file"]["size"] > 4500000) {
        $message .= compress($target_file, 0.45);
      } elseif ($_FILES["file"]["size"] > 2500000) {
        $message .= compress($target_file, 0.6);
      } elseif ($_FILES["file"]["size"] > 1000000) {
        $message .= compress($target_file, 0.7);
      } elseif ($_FILES["file"]["size"] > 600000) {
        $message .= compress($target_file, 0.8);
      }
    } else {                                    // GEEN COMPRESSIE NODIG, PLAATJE IS KLEIN OF IS PDF, GEWOON OPSLAAN
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        exit;
        // if everything is ok, try to upload file
      } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
          $message .= "Bon is geupload";
        } else {
          echo "Excuus, er was een probleem tijdens het uploaden";
          exit;
        }
      }
    }
    $data = json_decode($_POST['costs'], true);
    if (count($data) > 0) {
      foreach ($data as $cost_vals) {
        $cost = new Cost();
        $cost->price = $cost_vals['price'];
        $cost->btw = $cost_vals['btw'];

        $cost->price_excl_btw = round(($cost->price / (($cost->btw + 100) / 100)), 2);

        $cost->description = $cost_vals['description'];
        $cost->id_costcategory = 1;
        $cost->image = $filename;
        $cost->id_costcategory = $cost_vals['category'];
        $cost->id_customer = $cost_vals['id_customer'];
        $cost->id_offer = $cost_vals['id_offerte'];
        $cost->write_off = $cost_vals['write_off'];
        $cost->write_off_years = $cost_vals['write_off_years'];
        $cost->write_off_rest = $cost_vals['write_off_rest'];
        $cost->date = strtotime(str_replace('/', '-', $_POST['date']) . " 00:00:00 GMT");
        $cost->save();
      }
      echo $message;
    }
  }

  public function ajaxEdit(RouteCollection $routes)
  {
    $cost_vals = json_decode($_POST['costs'], true);

    $cost = new Cost($_POST['id']);
    $cost->price = $cost_vals['price'];
    $cost->btw = $cost_vals['btw'];
    $cost->price_excl_btw = round(($cost->price / (($cost->btw + 100) / 100)), 2);
    $cost->description = $cost_vals['description'];
    $cost->id_costcategory = $cost_vals['category'];
    $cost->id_customer = $cost_vals['id_customer'];
    $cost->id_offer = $cost_vals['id_offerte'];
    $cost->write_off = $cost_vals['write_off'];
    if ($cost_vals['write_off'] == 0) {
      $cost_vals['write_off_years'] = 0;
      $cost_vals['write_off_rest'] = 0;
    }
    $cost->write_off_years = $cost_vals['write_off_years'];
    $cost->write_off_rest = $cost_vals['write_off_rest'];
    $cost->date = strtotime(str_replace('/', '-', $_POST['date']) . " 00:00:00 GMT");
    $cost->save();

    echo "Update is opgeslagen";
  }
  public function ajaxDelete(RouteCollection $routes)
  {
    $id = (int) $_POST['id'];
    $cost = new Cost($id);
    if ($cost->id > 0) {
      $image = SITE_ROOT . "/uploads/costs/{$cost->image}";
      if (file_exists($image)) {
        unlink($image);
        echo 'Bon ' . $cost->image . ' is verwijderd van server.';
      } else {
        echo 'Geuploade bon niet gevonden.';
      }
      $cost->delete();
      echo "<br>Kostenpost is verwijderd.";
    }
    return true;
  }
}
