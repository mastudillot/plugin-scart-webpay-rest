<?php
#App\Plugins\Payment\WebpayPlus\Controllers\FrontController.php
namespace App\Plugins\Payment\WebpayPlus\Controllers;

use App\Plugins\Payment\WebpayPlus\AppConfig;
use App\Http\Controllers\RootFrontController;
class FrontController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    public function index() {
        return view($this->plugin->pathPlugin.'::Front',
            [
                //
            ]
        );
    }

    public function processOrder(){
        // Function require if plugin is payment method
    }
}
