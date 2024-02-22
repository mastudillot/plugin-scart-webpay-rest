<?php
#App\Plugins\Payment\Transbank\Admin\AdminController.php

namespace App\Plugins\Payment\Transbank\Admin;

use DateTime;
use App\Plugins\Payment\Transbank\AppConfig;
use App\Plugins\Payment\Transbank\Models\WebpayTransaction;
use App\Plugins\Payment\Transbank\Utils\PluginConstants;
use App\Plugins\Payment\Transbank\Utils\TransbankResponse;
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
    private $tableTranslatePath;
    private $statusTranslatePath;
    private $productTranslatePath;
    private $searchTranslatePath;
    private $detailsTranslatePath;
    private $transactionStates;
    private $breadcrumb;
    private $configEnvironmentKey;
    private $configCommerceCodeKey;
    private $configApiKey;
    private $configOrderStatusSuccessKey;
    private $configOrderStatusFailedKey;
    private $configPaymentStatusKey;

    public function __construct()
    {
        parent::__construct();
        $this->pathPlugin = PluginConstants::$pluginPath;
        $this->configCode = PluginConstants::$configCode;

        $this->tableTranslatePath = $this->pathPlugin . '::lang.transactions.table.';
        $this->statusTranslatePath = $this->pathPlugin . '::lang.transactions.status.';
        $this->productTranslatePath = $this->pathPlugin . '::lang.transactions.product.';
        $this->searchTranslatePath = $this->pathPlugin . '::lang.transactions.search.';
        $this->detailsTranslatePath = $this->pathPlugin . '::lang.transactions.details.';

        $this->configEnvironmentKey = PluginConstants::$configEnvironmentKey;
        $this->configCommerceCodeKey = PluginConstants::$configCommerceCodeKey;
        $this->configApiKey = PluginConstants::$configApiKey;
        $this->configOrderStatusSuccessKey = PluginConstants::$configOrderStatusSuccessKey;
        $this->configOrderStatusFailedKey = PluginConstants::$configOrderStatusFailedKey;
        $this->configPaymentStatusKey = PluginConstants::$configPaymentStatusKey;

        $this->transactionStates = [
            WebpayTransaction::STATUS_INITIALIZED,
            WebpayTransaction::STATUS_FAILED,
            WebpayTransaction::STATUS_ABORTED_BY_USER,
            WebpayTransaction::STATUS_APPROVED,
        ];

        $breadcrumb['url'] = sc_route_admin('admin_plugin', ['code' => $this->configCode]);
        $breadcrumb['name'] = sc_language_render('admin.plugin.' . $this->configCode . '_plugin');
    }

    public function index(Request $request)
    {
        $adminViews = [
            'config' => 'config',
            'transactions' => 'transactions',
            'healthcheck' => 'healthcheck',
            'logs' => 'log'
        ];

        $option = $request->get('option') ?? 'config';

        $view = isset($adminViews[$option]) ? $adminViews[$option] : $adminViews['config'];

        $viewData = [
            'pathPlugin' => $this->pathPlugin,
            'view' => $view,
            'breadcrumb' => $this->breadcrumb,
            'statusOrder' => ShopOrderStatus::getIdAll(),
            'paymentStatus' => ShopPaymentStatus::getIdAll(),
        ];

        if ($view == 'config') {
            $isProduction = sc_config($this->configEnvironmentKey) == 'production';
            $commerceCode = sc_config($this->configCommerceCodeKey);
            $apiKey = sc_config($this->configApiKey);
            $orderStatusSuccess = sc_config($this->configOrderStatusSuccessKey);
            $orderStatusFailed = sc_config($this->configOrderStatusFailedKey);
            $orderPaymentStatus = sc_config($this->configPaymentStatusKey);

            $configOptions = [
                'isProduction' => $isProduction,
                'commerceCode' => $commerceCode,
                'apiKey' => $apiKey,
                'orderStatusSuccess' => $orderStatusSuccess,
                'orderStatusFailed' => $orderStatusFailed,
                'orderPaymentStatus' => $orderPaymentStatus,
            ];

            $viewData = array_merge($viewData, $configOptions);
        }

        if ($view == 'transactions') {
            $viewData = $this->createTransactionTable($request, $viewData);
        }

        return view($this->pathPlugin . '::Admin.' . $view)->with($viewData);
    }

    public function saveConfig(WebpayConfigRequest $request)
    {
        $requestData = $request->all();
        AdminConfig::where('key', $this->configEnvironmentKey)->update(['value' => $requestData['environment']]);
        AdminConfig::where('key', $this->configOrderStatusSuccessKey)->update(
            ['value' => $requestData['order-status-success']]
        );
        AdminConfig::where('key', $this->configOrderStatusFailedKey)->update(
            ['value' => $requestData['order-status-failed']]
        );
        AdminConfig::where('key', $this->configPaymentStatusKey)->update(['value' => $requestData['payment-status']]);

        if ($requestData['environment'] == 'production') {
            AdminConfig::where('key', $this->configCommerceCodeKey)->update(['value' => $requestData['commerce-code']]);
            AdminConfig::where('key', $this->configApiKey)->update(['value' => $requestData['api-key']]);
        }

        return redirect()->route('admin_webpayplus.index');
    }

    public function transactionDetail($id)
    {
        $transaction = WebpayTransaction::where('id', $id)->firstOrFail();
        $ordenCurrency = $transaction->order->currency;
        $transbankResponse = json_decode($transaction->transbank_response, true);
        $formattedStatus = TransbankResponse::getStatus($transbankResponse['status']);
        $formattedAmount = TransbankResponse::getAmountFormatted($transbankResponse['amount'] ?? 0, $ordenCurrency);
        $formattedPaymentType = TransbankResponse::getPaymentType($transbankResponse['paymentTypeCode']);
        $formattedInstallmentsAmount = '-';
        $formattedTxDate = TransbankResponse::transactionDateToLocalDate($transbankResponse['transactionDate']);
        $formattedBalance = '-';

        if (!is_null($transbankResponse['installmentsAmount'])) {
            $formattedInstallmentsAmount = TransbankResponse::getAmountFormatted(
                $transbankResponse['installmentsAmount'],
                $ordenCurrency
            );
        }

        if (!is_null($transbankResponse['balance'])) {
            $formattedBalance = TransbankResponse::getAmountFormatted($transbankResponse['balance'], $ordenCurrency);
        }


        $formattedTransaction = [
            'id' => $transaction->id,
            'status' => $formattedStatus,
            'responseCode' => $transbankResponse['responseCode'],
            'amount' => $formattedAmount,
            'authorizationCode' => $transbankResponse['authorizationCode'],
            'paymentType' => $formattedPaymentType,
            'installmentsNumber' => $transbankResponse['installmentsNumber'],
            'installmentsAmount' => $formattedInstallmentsAmount,
            'cardNumber' => $transbankResponse['cardNumber'],
            'transactionDate' => $formattedTxDate,
            'balance' => $formattedBalance,
        ];

        $viewData = [
            'pathPlugin' => $this->pathPlugin,
            'detailTranslatePath' => $this->detailsTranslatePath,
            'view' => $this->pathPlugin . '::Admin.transactionDetail',
            'breadcrumb' => $this->breadcrumb,
            'transaction' => $formattedTransaction,
        ];

        return view($viewData['view'])->with($viewData);
    }

    private function createTransactionTable($request, $viewData)
    {
        $from_to = sc_clean($request->get('from_to') ?? '');
        $end_to = sc_clean($request->get('end_to') ?? '');
        $transaction_status = sc_clean($request->get('transaction_status') ?? '');
        $defaultOptionValue = trans($this->searchTranslatePath . 'search_transaction_status');

        $dataSearch = [
            'from_to'      => $from_to,
            'end_to'       => $end_to,
            'transaction_status' => $transaction_status,
        ];

        $webpayTxData = (new WebpayTransaction())->getTransactionList($dataSearch);
        $webpayTxArray = $webpayTxData->toArray();
        $tableHeader = [
            'id' => trans($this->tableTranslatePath . 'id'),
            'order_id' => trans($this->tableTranslatePath . 'order_id'),
            'token' => trans($this->tableTranslatePath . 'token'),
            'status' => trans($this->tableTranslatePath . 'status'),
            'amount' => trans($this->tableTranslatePath . 'amount'),
            'transbank_status' => trans($this->tableTranslatePath . 'transbank_status'),
            'transbank_product' => trans($this->tableTranslatePath . 'transbank_product'),
            'transbank_environment' => trans($this->tableTranslatePath . 'transbank_environment'),
            'created_at' => trans($this->tableTranslatePath . 'created_at'),
            'action' => trans($this->tableTranslatePath . 'action'),
        ];

        $tableRows = [];

        foreach ($webpayTxArray['data'] as $row) {
            $date = new DateTime($row['created_at']);
            $formattedDate = $date->format('d-m-Y H:i:s');
            $action = '-';
            $transactionRoute = sc_route_admin(
                'admin_webpayplus.transaction',
                ['id' => $row['id'] ? $row['id'] : 'not-found-id']
            );
            $orderDetailRoute = sc_route_admin(
                'admin_order.detail',
                ['id' => $row['order_id'] ? $row['order_id'] : 'not-found-id']
            );

            if (
                $row['status'] == WebpayTransaction::STATUS_APPROVED ||
                $row['status'] == WebpayTransaction::STATUS_FAILED
            ) {
                $action = '
                    <a href="' . $transactionRoute . '">
                        <span class="btn btn-flat btn-sm btn-primary"
                        title="' . trans($this->tableTranslatePath . 'actions.show') . '">
                            <i class="fa fa-eye"></i>
                        </span>
                    </a>
                ';
            }

            $tableRows[] = [
                'id' => $row['id'],
                'order_id' => '<a href="' . $orderDetailRoute . '">' . $row['order_id'] . '</a>',
                'token' => $row['token'],
                'status' => trans($this->statusTranslatePath . $row['status']),
                'amount' => sc_currency_render_symbol($row['amount'] ?? 0, $row['order']['currency']),
                'transbank_status' => $row['transbank_status'] ?? '-',
                'transbank_product' => trans($this->productTranslatePath . $row['transbank_product']),
                'transbank_environment' => $row['transbank_environment'],
                'created_at' => $formattedDate,
                'action' => $action,
            ];
        }

        $viewData['tableHeader'] = $tableHeader;
        $viewData['tableRows'] = $tableRows;
        $viewData['pagination'] = $webpayTxData->appends(request()->except(['_token', '_pjax']))
            ->links($this->templatePathAdmin . 'component.pagination');

        $viewData['resultItems'] = sc_language_render(
            $this->pathPlugin . '::lang.transactions.result_items',
            [
                'from' => $webpayTxArray['from'],
                'to' => $webpayTxArray['to'],
                'total' => $webpayTxArray['total']
            ]
        );

        //menuSearch
        $optionStatus = '';
        foreach ($this->transactionStates as $status) {
            $optionStatus .= '<option  ' . (($transaction_status == $status) ? "selected" : "") .
                ' value="' . $status . '">' . trans($this->statusTranslatePath . $status) . '</option>';
        }
        $viewData['topMenuRight'][] = '
            <form action="' . sc_route_admin('admin_webpayplus.index') . '" id="button_search">
                <div class="input-group float-left">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>' . trans($this->searchTranslatePath . 'from') . ':</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm date_time rounded"
                                name="from_to" id="from_to" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>' . trans($this->searchTranslatePath . 'to') . ':</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm date_time rounded"
                                name="end_to" id="end_to" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>' . trans($this->searchTranslatePath . 'status') . ':</label>
                            <div class="input-group">
                                <select class="form-control rounded-left" name="transaction_status">
                                    <option value="">' . $defaultOptionValue . '</option>
                                    ' . $optionStatus . '
                                </select>
                                <input type="hidden" name="option" value="transactions">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-flat rounded-right">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        ';
        //=menuSearch

        return $viewData;
    }
}
