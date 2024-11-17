<?php

namespace App\Controllers;

use App\Models\Cost;
use App\Models\Costcategory;
use App\Models\Invoice;
use App\Models\Travel;
use App\Database\Mysql;

use Symfony\Component\Routing\RouteCollection;

class ReportController
{

    public function showIndex(RouteCollection $routes)
    {
        require_once APP_ROOT . '/views/report/report.php';
    }
    public function showProfit($year, RouteCollection $routes)
    {
        $invoice = new Invoice();
        $date = new \DateTime($year . '-01-01');
        $from = $date->getTimestamp();
        $date = new \DateTime($year . '-12-31');
        $till = $date->getTimestamp();
        $invoice = $invoice->get_from_till($from, $till, "date_send");
        $total_excl_btw = 0;
        foreach ($invoice as $in) {
            $total_excl_btw += $in->price_excl_btw;
        }

        $categories = new Costcategory();
        $categories = $categories->get_all();
        $costs = new Cost();
        $costs = $costs->get_from_till($from, $till, "date");

        $travel = new Travel();
        $travel = $travel->get_from_till($from, $till, "date");

        if (count($costs) > 0) {
            $costs_total = [];
            foreach ($costs as $c) {
                if ($c->write_off > 0) {
                    // afschrijvingen werken anders, ff overslaan
                } else {
                    $cat = '';
                    foreach ($categories as $cat_obj) {
                        if ($cat_obj->id == $c->id_costcategory) {
                            $cat = $cat_obj->title;
                            break;
                        }
                    }
                    $excl_btw = round(($c->price / (($c->btw + 100) / 100)), 2);
                    if (!isset($costs_total[$cat])) {
                        $costs_total[$cat] = 0;
                    }
                    $costs_total[$cat] += $excl_btw;
                }
            }
            // afschrijvingen toevoegen
        }
        $conn = new Mysql();
        $result = $conn->fetchData("SELECT * FROM cost WHERE id_user={$_SESSION['user']->id} AND write_off > 0 ");
        if (count($result) > 0) {
            $costs_total['afschrijvingen'] = 0;
            foreach ($result as $r) {
                $timestamp = strtotime($r['date']);
                $costyear = date("Y", $timestamp);
                $maxyear = $costyear + $r['write_off_years'];
                if ($costyear <= $year && $maxyear > $year) {
                    $excl_btw = round(($r['price'] / (($r['btw'] + 100) / 100)), 2);
                    $peryear = $excl_btw - $r['write_off_rest'];
                    $peryear = round($peryear / $r['write_off_years'], 2);
                    $costs_total['afschrijvingen'] = $costs_total['afschrijvingen'] + $peryear;
                }
            }
        }

        if (count($travel) > 0) {
            $costs_total['Reiskosten (km x 0,19)'] = 0;
            foreach ($travel as $t) {
                $costs_total['Reiskosten (km x 0,19)'] += ($t->km * 0.19);
            }
        }
        require_once APP_ROOT . '/views/report/reportProfit.php';
    }
    public function showCosts($year, RouteCollection $routes)
    {
        $costs = new Cost();
        $date = new \DateTime($year . '-01-01');
        $from = $date->getTimestamp();
        $date = new \DateTime($year . '-12-31');
        $till = $date->getTimestamp();
        $costs = $costs->get_from_till($from, $till, "date");

        $cleanup_costs=[];
        foreach($costs as $cost){
            if($cost->write_off>0){
                // afschrijvingen werken anders, voegen we nu nog niet toe.
            }else{
                $cleanup_costs[]=$cost;
            }
        }

        $conn = new Mysql();
        $result = $conn->fetchData("SELECT * FROM cost WHERE id_user={$_SESSION['user']->id} AND write_off > 0 ");
        if (count($result) > 0) {
            $c= new Cost();
            $fields=$c->get_fields();
            
                  

            foreach ($result as $r) {
                $timestamp = strtotime($r['date']);
                $costyear = date("Y", $timestamp);
                $maxyear = $costyear + $r['write_off_years'];
                if ($costyear <= $year && $maxyear > $year) {
                    $excl_btw = round(($r['price'] / (($r['btw'] + 100) / 100)), 2);
                    $peryear = $excl_btw - $r['write_off_rest'];
                    $peryear = round($peryear / $r['write_off_years'], 2);
                    $tmp_obj=new \stdClass();
                    foreach($fields as $key => $val){
                        $tmp_obj->$key=$r[$key];
                    }  
                    $tmp_obj->peryear=$peryear;
                    $tmp_obj->firstyear=$costyear;
                    $cleanup_costs[]=$tmp_obj;
                }
            }
        }
        $costs=$cleanup_costs;
        require_once APP_ROOT . '/views/report/reportCosts.php';
    }
    public function showTest(RouteCollection $routes)
    {
        
        require_once APP_ROOT . '/views/report/reportTestPdf.php';
    }
}
