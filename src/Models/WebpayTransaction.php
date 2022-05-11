<?php

namespace App\Plugins\Payment\WebpayPlus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SCart\Core\Front\Models\ShopOrder;

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

    /**
     * Get list of webpay transactions.
     *
     * @param   [array]  $dataSearch  [Search filter]
     *
     * @return  [type]               [return Transactions data]
     */
    public static function getTransactionList(array $dataSearch)
    {
        $from_to = $dataSearch['from_to'] ?? '';
        $end_to = $dataSearch['end_to'] ?? '';
        $transaction_status = $dataSearch['transaction_status'] ?? '';

        $orderList = (new WebpayTransaction)->with('order');

        if ($transaction_status) {
            $orderList = $orderList->where('status', $transaction_status);
        }

        if ($from_to) {
            $orderList = $orderList->where(function ($sql) use ($from_to) {
                $sql->Where('created_at', '>=', $from_to);
            });
        }

        if ($end_to) {
            $orderList = $orderList->where(function ($sql) use ($end_to) {
                $sql->Where('created_at', '<=', $end_to);
            });
        }

        $orderList = $orderList->orderBy('created_at', 'desc');
        $orderList = $orderList->paginate(20);

        return $orderList;
    }

    public function order() {
        return $this->belongsTo(ShopOrder::class);
    }
}