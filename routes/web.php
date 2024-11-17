<?php 

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

// Routes system
$routes = new RouteCollection();

$routes->add('landingpage', new Route(constant('URL_SUBFOLDER') . '/', array('controller' => 'LandingpageController', 'method'=>'showAction')));

$routes->add('login', new Route(constant('URL_SUBFOLDER') . '/login', array('controller' => 'LoginController', 'method'=>'showAction')));
$routes->add('login_ajax', new Route(constant('URL_SUBFOLDER') . '/login_ajax', array('controller' => 'LoginController', 'method'=>'checkLogin')));
$routes->add('logout', new Route(constant('URL_SUBFOLDER') . '/logout', array('controller' => 'LoginController', 'method'=>'logOut')));
$routes->add('register', new Route(constant('URL_SUBFOLDER') . '/register', array('controller' => 'RegisterController', 'method'=>'showAction')));
$routes->add('register_ajax', new Route(constant('URL_SUBFOLDER') . '/register_ajax', array('controller' => 'RegisterController', 'method'=>'saveAjax')));

$routes->add('newCost', new Route(constant('URL_SUBFOLDER') . '/cost/new', array('controller' => 'CostController', 'method'=>'showNew')));
$routes->add('newCost_get_offer_ajax', new Route(constant('URL_SUBFOLDER') . '/cost/new/get_offer_ajax', array('controller' => 'CostController', 'method'=>'ajaxGetOffer')));
$routes->add('newCost_preselect_ajax', new Route(constant('URL_SUBFOLDER') . '/cost/new/preselect_ajax', array('controller' => 'CostController', 'method'=>'ajaxPreSelect')));
$routes->add('newCost_ajax', new Route(constant('URL_SUBFOLDER') . '/cost/new_ajax', array('controller' => 'CostController', 'method'=>'ajaxNew')));
$routes->add('deleteCost_ajax', new Route(constant('URL_SUBFOLDER') . '/cost/delete_ajax', array('controller' => 'CostController', 'method'=>'ajaxDelete')));
$routes->add('editCost', new Route(constant('URL_SUBFOLDER') . '/cost/edit/{id}', array('controller' => 'CostController', 'method'=>'showEdit'),array('id' => '[0-9]+')));
$routes->add('editCost_ajax', new Route(constant('URL_SUBFOLDER') . '/cost/edit_ajax', array('controller' => 'CostController', 'method'=>'ajaxEdit')));

$routes->add('cost', new Route(constant('URL_SUBFOLDER') . '/cost/{year}/{q}', array('controller' => 'CostController', 'method'=>'showList','year'=>'0','q'=>'0',), array('year' => '[0-9]+','q' => '[0-9]+')));

$routes->add('travel', new Route(constant('URL_SUBFOLDER') . '/travel/{year}/{q}', array('controller' => 'TravelController', 'method'=>'showList','year'=>'0','q'=>'0',), array('year' => '[0-9]+','q' => '[0-9]+')));
$routes->add('newTravel', new Route(constant('URL_SUBFOLDER') . '/travel/new', array('controller' => 'TravelController', 'method'=>'showNew')));
$routes->add('newTravel_ajax', new Route(constant('URL_SUBFOLDER') . '/travel/new_ajax', array('controller' => 'TravelController', 'method'=>'ajaxNew')));

$routes->add('hour', new Route(constant('URL_SUBFOLDER') . '/hour/{year}/{q}', array('controller' => 'HourController', 'method'=>'showList','year'=>'0','q'=>'0',), array('year' => '[0-9]+','q' => '[0-9]+')));
$routes->add('newHour', new Route(constant('URL_SUBFOLDER') . '/hour/new', array('controller' => 'HourController', 'method'=>'showNew')));
$routes->add('newHour_ajax', new Route(constant('URL_SUBFOLDER') . '/hour/new_ajax', array('controller' => 'HourController', 'method'=>'ajaxNew')));

$routes->add('customer', new Route(constant('URL_SUBFOLDER') . '/customer', array('controller' => 'CustomerController', 'method'=>'showList')));
$routes->add('newCustomer', new Route(constant('URL_SUBFOLDER') . '/customer/new', array('controller' => 'CustomerController', 'method'=>'showNew')));
$routes->add('newCustomer_ajax', new Route(constant('URL_SUBFOLDER') . '/customer/new_ajax', array('controller' => 'CustomerController', 'method'=>'ajaxNew')));
$routes->add('customer_detail', new Route(constant('URL_SUBFOLDER') . '/customer/{id}', array('controller' => 'CustomerController', 'method'=>'showDetail'),array('id' => '[0-9]+')));
$routes->add('customer_detail_getTravel_ajax', new Route(constant('URL_SUBFOLDER') . '/customer/{id}/getTravel_ajax', array('controller' => 'CustomerController', 'method'=>'ajaxGetTravel'),array('id' => '[0-9]+')));
$routes->add('customer_detail_saveTravel_ajax', new Route(constant('URL_SUBFOLDER') . '/customer/{id}/saveTravel_ajax', array('controller' => 'CustomerController', 'method'=>'ajaxSaveTravel'),array('id' => '[0-9]+')));
$routes->add('customer_detail_deleteTravel_ajax', new Route(constant('URL_SUBFOLDER') . '/customer/{id}/deleteTravel_ajax', array('controller' => 'CustomerController', 'method'=>'ajaxDeleteTravel'),array('id' => '[0-9]+')));
$routes->add('customer_detail_getHour_ajax', new Route(constant('URL_SUBFOLDER') . '/customer/{id}/getHour_ajax', array('controller' => 'CustomerController', 'method'=>'ajaxGetHour'),array('id' => '[0-9]+')));
$routes->add('customer_detail_saveHour_ajax', new Route(constant('URL_SUBFOLDER') . '/customer/{id}/saveHour_ajax', array('controller' => 'CustomerController', 'method'=>'ajaxSaveHour'),array('id' => '[0-9]+')));
$routes->add('customer_detail_deleteHour_ajax', new Route(constant('URL_SUBFOLDER') . '/customer/{id}/deleteHour_ajax', array('controller' => 'CustomerController', 'method'=>'ajaxDeleteHour'),array('id' => '[0-9]+')));

$routes->add('offer', new Route(constant('URL_SUBFOLDER') . '/offer', array('controller' => 'OfferController', 'method'=>'showList')));
$routes->add('newOffer', new Route(constant('URL_SUBFOLDER') . '/offer/new', array('controller' => 'OfferController', 'method'=>'showNew')));
$routes->add('newOffer_ajax', new Route(constant('URL_SUBFOLDER') . '/offer/new_ajax', array('controller' => 'OfferController', 'method'=>'ajaxNew')));
$routes->add('newOffer_template_ajax', new Route(constant('URL_SUBFOLDER') . '/offer/template_ajax', array('controller' => 'OfferController', 'method'=>'ajaxTemplate')));
 
$routes->add('invoice', new Route(constant('URL_SUBFOLDER') . '/invoice/{year}/{q}', array('controller' => 'InvoiceController', 'method'=>'showList','year'=>'0','q'=>'0',), array('year' => '[0-9]+','q' => '[0-9]+')));
$routes->add('invoiceCustomerOffer', new Route(constant('URL_SUBFOLDER') . '/invoice/customer/{id}/offer/{id_offer}', array('controller' => 'InvoiceController', 'method'=>'showListCustomer'), array('id' => '[0-9]+','id_offer' => '[0-9]+')));
$routes->add('editInvoice', new Route(constant('URL_SUBFOLDER') . '/invoice/edit/{id}', array('controller' => 'InvoiceController', 'method'=>'showEdit'),array('id'=> '[0-9]+')));
$routes->add('editInvoice_ajax', new Route(constant('URL_SUBFOLDER') . '/invoice/edit_ajax', array('controller' => 'InvoiceController', 'method'=>'ajaxEdit')));
$routes->add('newInvoice', new Route(constant('URL_SUBFOLDER') . '/invoice/new', array('controller' => 'InvoiceController', 'method'=>'showNew')));
$routes->add('newInvoice_ajax', new Route(constant('URL_SUBFOLDER') . '/invoice/new_ajax', array('controller' => 'InvoiceController', 'method'=>'ajaxNew')));
$routes->add('invoice_pdf', new Route(constant('URL_SUBFOLDER') . '/invoice/pdf/{id}', array('controller' => 'InvoiceController', 'method'=>'showPdf','id'=>'0'), array('id' => '[0-9]+')));

$routes->add('report', new Route(constant('URL_SUBFOLDER') . '/report', array('controller' => 'ReportController', 'method'=>'showIndex')));
$routes->add('reportProfit', new Route(constant('URL_SUBFOLDER') . '/report/profit/{year}', array('controller' => 'ReportController', 'method'=>'showProfit'), array('year' => '[0-9]+')));
$routes->add('reportCosts', new Route(constant('URL_SUBFOLDER') . '/report/costs/{year}', array('controller' => 'ReportController', 'method'=>'showCosts'), array('year' => '[0-9]+')));
$routes->add('reportTestPdf', new Route(constant('URL_SUBFOLDER') . '/report/test', array('controller' => 'ReportController', 'method'=>'showTest')));

$routes->add('docs_test', new Route(constant('URL_SUBFOLDER') . '/docs_test', array('controller' => 'docs_test', 'method'=>'docs_test')));
$routes->add('googleapi_return', new Route(constant('URL_SUBFOLDER') . '/googleapi/{values}', array('controller' => 'docs_test', 'method'=>'return','values'=>''), array('values' => '.+')));
$routes->add('googleapi_askAuth', new Route(constant('URL_SUBFOLDER') . '/googleauth', array('controller' => 'docs_test', 'method'=>'askAuth')));
 