<?php
#App\Plugins\Payment\WebpayPlus\Admin\AdminController.php

namespace App\Plugins\Payment\WebpayPlus\Admin;

use App\Http\Controllers\RootAdminController;
use App\Plugins\Payment\WebpayPlus\AppConfig;

class AdminController extends RootAdminController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }
    public function index()
    {
        return view($this->plugin->pathPlugin.'::Admin',
            [
                
            ]
        );
    }
}
