<?php

namespace App\Plugins\Payment\WebpayPlus\Admin;

use Illuminate\Foundation\Http\FormRequest;

use App\Plugins\Payment\WebpayPlus\AppConfig;

class WebpayConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'environment' => 'required|string|in:integration,production',
            'order-status-success' => 'required|numeric|exists:sc_shop_order_status,id',
            'order-status-failed' => 'required|numeric|exists:sc_shop_order_status,id',
            'payment-status' => 'required|numeric|exists:sc_shop_payment_status,id',
            'commerce-code' => 'exclude_if:environment,integration|required|numeric',
            'api-key' => 'exclude_if:environment,integration|required|string',
        ];
    }

    public function messages()
    {
        $pathPlugin = (new AppConfig)->pathPlugin;
        return [
            'environment.required' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_required'),
            'environment.string' => trans($pathPlugin . '::ang.errors.config.webpay_plus_attribute_string'),
            'environment.in' => trans($pathPlugin . '::ang.errors.config.webpay_plus_environment_in'),
            'order-status-success.required' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_required'),
            'order-status-success.numeric' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_numeric'),
            'order-status-success.exist' => trans($pathPlugin . '::lang.errors.config.webpay_plus_environment_exist'),
            'order-status-failed.required' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_required'),
            'order-status-failed.numeric' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_numeric'),
            'order-status-failed.exist' => trans($pathPlugin . '::lang.errors.config.webpay_plus_environment_exist'),
            'payment-status.required' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_required'),
            'payment-status.numeric' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_numeric'),
            'payment-status.exist' => trans($pathPlugin . '::lang.errors.config.webpay_plus_environment_exist'),
            'commerce-code.required' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_required'),
            'commerce-code.numeric' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_numeric'),
            'api-key.required' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_required'),
            'api-key.string' => trans($pathPlugin . '::lang.errors.config.webpay_plus_attribute_string'),
        ];
    }

    public function attributes()
    {
        $pathPlugin = (new AppConfig)->pathPlugin;
        return [
            'environment' => trans($pathPlugin . '::lang.admin.webpay_plus_environment'),
            'order-status-success' => trans($pathPlugin . '::lang.admin.webpay_plus_order_status_success'),
            'order-status-failed' => trans($pathPlugin . '::lang.admin.webpay_plus_order_status_failed'),
            'payment-status' => trans($pathPlugin . '::lang.admin.webpay_plus_payment_status'),
            'commerce-code' => trans($pathPlugin . '::lang.admin.webpay_plus_commerce_code'),
            'api-key' => trans($pathPlugin . '::lang.admin.webpay_plus_api_key'),
        ];
    }
}