<?php 

namespace App\Plugins\Payment\Transbank\TransbankSDK;

require_once __DIR__ . '/vendor/autoload.php';

use App\Plugins\Payment\Transbank\Utils\PluginConstants;
use DateTime;
use DateTimeZone;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus\Transaction;

class Webpay
{
    /**
     * @var Transaction
     */
    protected $transaction = null;

    /**
     * Webpay constructor.
     */
    public function __construct()
    {
        $environment = sc_config(PluginConstants::$configEnvironmentKey);
        $options = Transaction::getDefaultOptions();

        if ($environment !== 'integration') {
            $commerceCode = sc_config(PluginConstants::$configCommerceCodeKey);
            $apiKey = sc_config(PluginConstants::$configApiKey);
            $options = Options::forProduction($commerceCode, $apiKey);
        }

        $this->transaction = new Transaction($options);
    }
    public function create($buyOrder, $sessionId, $amount, $returnUrl)
    {
        return $this->transaction->create($buyOrder, $sessionId, $amount, $returnUrl);
    }

    public function commit($token)
    {
        return $this->transaction->commit($token);
    }

    public function refund($token, $amount)
    {
        return $this->transaction->refund($token, $amount);
    }

    public function status($token)
    {
        return $this->transaction->status($token);
    }

    public function getFormattedResponse($response)
    {
        $vci = $response->getVci();
        $amount = $response->getAmount();
        $state = $response->getStatus();
        $buy_order = $response->getBuyOrder();
        $session_id = $response->getSessionId();
        $cardDetail = $response->getCardDetail();
        $cardNumber = $response->getCardNumber();
        $accountingDate = $response->getAccountingDate();
        $transactionDate = $response->getTransactionDate();
        $authorizationCode = $response->getAuthorizationCode();
        $paymentTypeCode = $response->getPaymentTypeCode();
        $responseCode = $response->getResponseCode();
        $installmentsAmount = $response->getInstallmentsAmount();
        $installmentsNumber = $response->getInstallmentsNumber();
        $balance = $response->getBalance();

        $utc_date = new DateTime($transactionDate, new DateTimeZone('UTC'));
        $local_date = $utc_date;
        $local_date->setTimeZone(new DateTimeZone('America/Santiago'));
        $formattedDate = $local_date->format('d-m-Y H:i:s');

        return array(
            'isApproved' => $response->isApproved(),
            'vci' => $vci,
            'amount' => $amount,
            'state' => $state,
            'buy_order' => $buy_order,
            'session_id' => $session_id,
            'cardDetail' => $cardDetail,
            'cardNumber' => $cardNumber,
            'accountingDate' => $accountingDate,
            'transactionDate' => $formattedDate,
            'authorizationCode' => $authorizationCode,
            'paymentTypeCode' => $paymentTypeCode,
            'responseCode' => $responseCode,
            'installmentsAmount' => $installmentsAmount,
            'installmentsNumber' => $installmentsNumber,
            'balance' => $balance
        );
    }
}