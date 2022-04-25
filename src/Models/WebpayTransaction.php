<?php

namespace App\Plugins\Payment\WebpayPlus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebpayTransaction extends Model
{
    use HasFactory;

    const STATUS_INITIALIZED = 'initialized';
    const STATUS_FAILED = 'failed';
    const STATUS_ABORTED_BY_USER = 'aborted_by_user';
    const STATUS_APPROVED = 'approved';

    const PRODUCT_WEBPAY_PLUS = 'webpay_plus';
    const PRODUCT_WEBPAY_ONECLICK = 'webpay_oneclick';
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SC_DB_PREFIX.'webpay_transactions';
}