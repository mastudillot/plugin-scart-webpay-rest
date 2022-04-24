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
        $requireAttributeTranslation = '::lang.errors.config.attribute_required';
        $numericAttributeTranslation = '::lang.errors.config.attribute_numeric';
        $existsAttributeTranslation = '::lang.errors.config.environment_exist';
        $stringAttributeTranslation = '::lang.errors.config.attribute_string';
        
        $pathPlugin = (new AppConfig)->pathPlugin;
        return [
            'environment.required' => trans($pathPlugin . $requireAttributeTranslation),
            'environment.string' => trans($pathPlugin . $stringAttributeTranslation),
            'environment.in' => trans($pathPlugin . '::ang.errors.config.environment_in'),
            'order-status-success.required' => trans($pathPlugin . $requireAttributeTranslation),
            'order-status-success.numeric' => trans($pathPlugin . $numericAttributeTranslation),
            'order-status-success.exist' => trans($pathPlugin . $existsAttributeTranslation),
            'order-status-failed.required' => trans($pathPlugin . $requireAttributeTranslation),
            'order-status-failed.numeric' => trans($pathPlugin . $numericAttributeTranslation),
            'order-status-failed.exist' => trans($pathPlugin . $existsAttributeTranslation),
            'payment-status.required' => trans($pathPlugin . $requireAttributeTranslation),
            'payment-status.numeric' => trans($pathPlugin . $numericAttributeTranslation),
            'payment-status.exist' => trans($pathPlugin . $existsAttributeTranslation),
            'commerce-code.required' => trans($pathPlugin . $requireAttributeTranslation),
            'commerce-code.numeric' => trans($pathPlugin . $numericAttributeTranslation),
            'api-key.required' => trans($pathPlugin . $requireAttributeTranslation),
            'api-key.string' => trans($pathPlugin . $stringAttributeTranslation),
        ];
    }

    public function attributes()
    {
        $pathPlugin = (new AppConfig)->pathPlugin;
        return [
            'environment' => trans($pathPlugin . '::lang.admin.environment'),
            'order-status-success' => trans($pathPlugin . '::lang.admin.order_status_success'),
            'order-status-failed' => trans($pathPlugin . '::lang.admin.order_status_failed'),
            'payment-status' => trans($pathPlugin . '::lang.admin.payment_status'),
            'commerce-code' => trans($pathPlugin . '::lang.admin.commerce_code'),
            'api-key' => trans($pathPlugin . '::lang.admin.api_key'),
        ];
    }
}
