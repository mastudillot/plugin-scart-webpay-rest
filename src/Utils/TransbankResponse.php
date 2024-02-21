<?php

namespace App\Plugins\Payment\Transbank\Utils;

use DateTime;
use DateTimeZone;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCommitResponse;

/**
 * Utility class for handling Transbank responses.
 */
class TransbankResponse
{
    const TRANSLATION_PAYMENT_DEBIT = '::lang.payment.debit';
    const TRANSLATION_PAYMENT_CREDIT = '::lang.payment.credit';
    const TRANSLATION_PAYMENT_PREPAID = '::lang.payment.prepaid';
    private static $paymentType;
    private static $installmentType;
    private static $transactionStatus;
    private static $timeZone;

    public static function initialize()
    {
        $pathPlugin = PluginConstants::$pluginPath;
        self::$paymentType = [
            "VD" => trans($pathPlugin . self::TRANSLATION_PAYMENT_DEBIT),
            "VN" => trans($pathPlugin . self::TRANSLATION_PAYMENT_CREDIT),
            "VC" => trans($pathPlugin . self::TRANSLATION_PAYMENT_CREDIT),
            "SI" => trans($pathPlugin . self::TRANSLATION_PAYMENT_CREDIT),
            "S2" => trans($pathPlugin . self::TRANSLATION_PAYMENT_CREDIT),
            "NC" => trans($pathPlugin . self::TRANSLATION_PAYMENT_CREDIT),
            "VP" => trans($pathPlugin . self::TRANSLATION_PAYMENT_PREPAID)
        ];

        self::$installmentType = [
            "VD" => trans($pathPlugin . '::lang.payment.debit_sale'),
            "VN" => trans($pathPlugin . '::lang.payment.normal_sale'),
            "VC" => trans($pathPlugin . '::lang.payment.installment_sale'),
            "SI" => trans($pathPlugin . '::lang.payment.3_installment_sale'),
            "S2" => trans($pathPlugin . '::lang.payment.2_installment_sale'),
            "NC" => trans($pathPlugin . '::lang.payment.n_installment_sale'),
            "VP" => trans($pathPlugin . '::lang.payment.prepaid_sale'),
        ];

        self::$transactionStatus = [
            'INITIALIZED' => trans($pathPlugin . '::lang.payment.status.initialized'),
            'AUTHORIZED' => trans($pathPlugin . '::lang.payment.status.authorized'),
            'REVERSED' => trans($pathPlugin . '::lang.payment.status.reversed'),
            'FAILED' => trans($pathPlugin . '::lang.payment.status.failed'),
            'NULLIFIED' => trans($pathPlugin . '::lang.payment.status.nullified'),
            'PARTIALLY_NULLIFIED' => trans($pathPlugin . '::lang.payment.status.partially_nullified'),
            'CAPTURED' => trans($pathPlugin . '::lang.payment.status.captured'),
        ];

        self::$timeZone = config('app.timezone', 'America/Santiago');
    }
    /**
     * Get the payment type from its code.
     *
     * @param string $paymentType The code of the payment type.
     * @return string The corresponding payment type.
     */
    public static function getPaymentType(string $paymentType): string
    {
        return self::$paymentType[$paymentType] ?? $paymentType;
    }

    /**
     * Get the installment type from the payment type response.
     *
     * @param string $paymentType The code of the installment type.
     * @return string The corresponding installment type.
     */
    public static function getInstallmentType(string $paymentType): string
    {
        return self::$installmentType[$paymentType] ?? $paymentType;
    }

    /**
     * Get the transaction status description from response status.
     *
     * @param string $status The code of the transaction status.
     * @return string The description of the corresponding transaction status.
     */
    public static function getStatus(string $status): string
    {
        return self::$transactionStatus[$status] ?? $status;
    }

    /**
     * Get the formatted accounting date from response.
     *
     * @param string $accountingDate The accounting date in 'md' format.
     * @return string The accounting date in 'mm-dd' format.
     */
    public static function getAccountingDate(string $accountingDate): string
    {
        $date = DateTime::createFromFormat('md', $accountingDate);

        if (!$date) {
            return $accountingDate;
        }

        return $date->format('m-d');
    }

    /**
     * Converts a string of transaction UTC date to local date with time difference.
     *
     * @param string $date an date/time string in UTC.
     * @return string the string of local date with time difference.
     */
    public static function transactionDateToLocalDate(string $date): string
    {
        $utcDate = new DateTime($date, new DateTimeZone('UTC'));
        $utcDate->setTimeZone(new DateTimeZone(self::$timeZone));
        return $utcDate->format('d-m-Y H:i:s P');
    }

    /**
     * Get the CLP formatted amount from an integer value.
     *
     * @param int $amount The integer amount to be formatted.
     * @return string The formatted amount as a string.
     */
    public static function getAmountFormatted(int $amount): string
    {
        return '$' . number_format($amount, 0, ',', '.');
    }

    /**
     * Get the common fields formatted for sale receipt.
     *
     * @param TransactionCommitResponse|MallTransactionAuthorizeResponse $transactionResponse The transaction response.
     * @return array The formatted common fields.
     */
    private static function getCommonFieldsFormatted(
        TransactionCommitResponse | MallTransactionAuthorizeResponse $transactionResponse
    ): array {
        $utcDate = new DateTime($transactionResponse->transactionDate, new DateTimeZone('UTC'));
        $utcDate->setTimeZone(new DateTimeZone(self::$timeZone));

        $buyOrder = $transactionResponse->buyOrder;
        $cardNumber = "**** **** **** {$transactionResponse->cardNumber}";
        $transactionDate = $utcDate->format('d-m-Y');
        $transactionTime = $utcDate->format('H:i:s');

        return [
            'buyOrder' => $buyOrder,
            'cardNumber' => $cardNumber,
            'transactionDate' => $transactionDate,
            'transactionTime' => $transactionTime
        ];
    }

    /**
     * Get the formatted response for Webpay transactions.
     *
     * @param TransactionCommitResponse $transactionResponse The response object for Webpay transactions.
     * @return array The formatted response fields.
     */
    public static function getWebpayFormattedResponse(TransactionCommitResponse $transactionResponse): array
    {
        $commonFields = self::getCommonFieldsFormatted($transactionResponse);

        $amount = self::getAmountFormatted($transactionResponse->amount);
        $paymentType = self::getPaymentType($transactionResponse->paymentTypeCode);
        $installmentType = self::getInstallmentType($transactionResponse->paymentTypeCode);
        $installmentAmount = self::getAmountFormatted($transactionResponse->installmentsAmount ?? 0);

        $webpayFields = [
            'amount' => $amount,
            'authorizationCode' => $transactionResponse->authorizationCode,
            'paymentType' => $paymentType,
            'installmentType' => $installmentType,
            'installmentNumber' => $transactionResponse->installmentsNumber,
            'installmentAmount' => $installmentAmount
        ];

        return array_merge($commonFields, $webpayFields);
    }

    /**
     * Get the formatted response for Oneclick transactions.
     *
     * @param MallTransactionAuthorizeResponse $transactionResponse The response object for Oneclick transactions.
     * @return array The formatted response fields.
     */
    public static function getOneclickFormattedResponse(MallTransactionAuthorizeResponse $transactionResponse): array
    {
        $commonFields = self::getCommonFieldsFormatted($transactionResponse);
        $detail = $transactionResponse->details[0];

        $amount = self::getAmountFormatted($detail->amount);
        $paymentType = self::getPaymentType($detail->paymentTypeCode);
        $installmentType = self::getInstallmentType($detail->paymentTypeCode);
        $installmentAmount = self::getAmountFormatted($detail->installmentsAmount ?? 0);

        $oneclickFields = [
            'amount' => $amount,
            'authorizationCode' => $detail->authorizationCode,
            'paymentType' => $paymentType,
            'installmentType' => $installmentType,
            'installmentNumber' => $detail->installmentsNumber,
            'installmentAmount' => $installmentAmount
        ];

        return array_merge($commonFields, $oneclickFields);
    }
}

TransbankResponse::initialize();
