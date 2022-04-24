<?php
#App\Plugins\Payment\WebpayPlus\Admin\AdminController.php

namespace App\Plugins\Payment\WebpayPlus\Admin;

use App\Plugins\Payment\WebpayPlus\AppConfig;
use Illuminate\Http\Request;
use SCart\Core\Admin\Models\AdminConfig;
use SCart\Core\Admin\Controllers\RootAdminController;
use SCart\Core\Front\Models\ShopOrderStatus;
use SCart\Core\Front\Models\ShopPaymentStatus;

class AdminController extends RootAdminController
{
    public $plugin;
    private $pathPlugin;
    private $configCode;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
        $this->pathPlugin = $this->plugin->pathPlugin;
        $this->configCode = $this->plugin->configCode;
        
    }
    public function index(Request $request)
    {
        $adminViews = [
            'config' => 'configWebpay',
            'transactions' => 'transaction',
            'healthcheck' => 'healthcheck',
            'logs' => 'log'
        ];

        $option = $request->get('option') ?? 'config';

        $view = isset($adminViews[$option]) ? $adminViews[$option] : $adminViews['config'];

        $breadcrumb['url'] = sc_route_admin('admin_plugin', ['code' => $this->configCode]);
        $breadcrumb['name'] = sc_language_render('admin.plugin.' . $this->configCode.'_plugin');

        return view($this->pathPlugin.'::Admin.'.$view,
            [
                'pathPlugin' => $this->pathPlugin,
                'view' => $view,
                'breadcrumb' => $breadcrumb,
                'statusOrder' => ShopOrderStatus::getIdAll(),
                'paymentStatus' => ShopPaymentStatus::getIdAll(),
            ]
        );
    }
    public function saveConfig(WebpayConfigRequest $request)
    {
        $data = $request->all();
        AdminConfig::where('key', 'WebpayPlus_environment')->update(['value' => $data['environment']]);
        AdminConfig::where('key', 'WebpayPlus_order_status_success')->update(['value' => $data['order-status-success']]);
        AdminConfig::where('key', 'WebpayPlus_order_status_failed')->update(['value' => $data['order-status-failed']]);
        AdminConfig::where('key', 'WebpayPlus_payment_status')->update(['value' => $data['payment-status']]);

        if($data['environment'] == 'production') {
            AdminConfig::where('key', 'WebpayPlus_commerce_code')->update(['value' => $data['commerce-code']]);
            AdminConfig::where('key', 'WebpayPlus_api_key')->update(['value' => $data['api-key']]);
        }

        return redirect()->route('admin_webpayplus.index');
    }
}
