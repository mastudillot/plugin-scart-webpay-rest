<?php
#App\Plugins\Payment\Transbank\Controllers\FrontController.php
namespace App\Plugins\Payment\Transbank\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Plugins\Payment\Transbank\TransbankSDK\Webpay;
use App\Plugins\Payment\Transbank\Models\WebpayTransaction;
use App\Plugins\Payment\Transbank\Utils\PluginConstants;
use SCart\Core\Front\Controllers\RootFrontController;
use SCart\Core\Front\Controllers\ShopCartController;
use SCart\Core\Front\Models\ShopOrder;

class FrontController extends RootFrontController
{
    private $pathPlugin;

    public function __construct()
    {
        parent::__construct();
        $this->pathPlugin = PluginConstants::$pluginPath;
    }

    public function index()
    {

        if (empty(session('dataPayment'))) {
            return redirect(sc_route('checkout'))->with(["error" => 'No session']);
        }
        $dataPayment = session('dataPayment');
        $buyOrder = $dataPayment['reference_id'];
        $sessionId = session()->getId();
        $amount = $dataPayment['amount']['value'];
        $returnUrl = sc_route('webpayplus.finish', ['orderId' => $buyOrder]);

        try {
            $response = (new Webpay)->create($buyOrder, $sessionId, $amount, $returnUrl);
            $webpayTransaction = new WebpayTransaction;
            $webpayTransaction->order_id = $buyOrder;
            $webpayTransaction->amount = $amount;
            $webpayTransaction->token = $response->getToken();
            $webpayTransaction->session_id = $sessionId;
            $webpayTransaction->status = WebpayTransaction::STATUS_INITIALIZED;
            $webpayTransaction->transbank_environment = sc_config(PluginConstants::$configEnvironmentKey);
            $webpayTransaction->transbank_product = WebpayTransaction::PRODUCT_WEBPAY_PLUS;
            $webpayTransaction->save();

            return view($this->pathPlugin . '::toPay')->with([
                'response' => $response,
            ]);
        } catch (Throwable $e) {
            return redirect(sc_route('checkout'))->with(["error" => $e->getMessage()]);
        }
    }

    public function processOrder()
    {
        $dataOrder = session('dataOrder') ?? [];
        $currency = $dataOrder['currency'] ?? '';
        $orderID = session('orderID') ?? 0;
        $arrCartDetail = session('arrCartDetail') ?? null;

        if ($orderID && $dataOrder && $arrCartDetail) {
            $dataTotal = [
                'item_total' => [
                    'currency_code' => $currency,
                    'value' => (int)$dataOrder['subtotal'],
                ],
                'shipping' => [
                    'currency_code' => $currency,
                    'value' => (int)$dataOrder['shipping'],
                ],
                'handling' => [
                    'currency_code' => $currency,
                    'value' => (int)$dataOrder['tax'],
                ],
                'discount' => [
                    'currency_code' => $currency,
                    'value' => abs((int)$dataOrder['discount']),
                ],
            ];

            foreach ($arrCartDetail as $item) {
                $dataItems[] = [
                    'name' => $item['name'],
                    'quantity' => $item['qty'],
                    'unit_amount' => [
                        'currency_code' => $currency,
                        'value' => sc_currency_value((int)$item['price']),
                    ],
                    'sku' => $item['product_id'],
                ];
            }

            $dataPayment = [];
            $dataPayment['reference_id'] = $orderID;
            $dataPayment['amount'] = [
                'currency_code' => $currency,
                'value' => (int)$dataOrder['total'],
                'breakdown' => $dataTotal,
            ];
            $dataPayment['items'] = $dataItems;

            return redirect()->route('webpayplus.index')->with('dataPayment', $dataPayment);
        } else {
            return redirect(sc_route('checkout'))->with(['error' => 'Data not correct']);
        }
    }

    public function finish($orderId, Request $request)
    {
        $token_ws = $request->get('token_ws');

        // Se verifica el resultado de la pasarela de pago.
        $webpayError = $this->checkWebpayResult($request);
        if ($webpayError) {
            return redirect(sc_route('checkout'))->with(['error' => $webpayError]);
        }

        /* Flujo finalizado correctamente, puede ser aprobado o rechazado */
        $response = (new Webpay)->commit($token_ws);

        $webpayTransaction = WebpayTransaction::orderBy('id', 'DESC')->where('token', $token_ws)->firstOrFail();

        if ($response->isApproved()) {
            $webpayTransaction->status = WebpayTransaction::STATUS_APPROVED;
        } else {
            $webpayTransaction->status = WebpayTransaction::STATUS_FAILED;
        }

        $webpayTransaction->transbank_response = json_encode($response);
        $webpayTransaction->transbank_status = $response->getStatus();
        $webpayTransaction->save();

        if ($response->isApproved()) {
            $order = ShopOrder::find($orderId);

            $order->update([
                'transaction' => $token_ws,
                'status' => sc_config(PluginConstants::$configOrderStatusSuccessKey),
                'payment_status' => sc_config(PluginConstants::$configPaymentStatusKey)
            ]);
            //Add history
            $dataHistory = [
                'order_id' => $orderId,
                'content' => trans($this->pathPlugin . '::lang.payment.paid_with') . 'Webpay Plus',
                'order_status_id' => sc_config(PluginConstants::$configOrderStatusSuccessKey),
            ];
            $order->addOrderHistory($dataHistory);
            return (new ShopCartController)->completeOrder();
        }

        return redirect(sc_route('checkout'))->with(
            ['error' => trans($this->pathPlugin . '::lang.errors.payment_rejected')]
        );
    }

    private function checkWebpayResult($request)
    {
        $token_ws = $request->get('token_ws');
        $tbk_id_session = $request->get('TBK_ID_SESION');
        $tbk_orden_compra = $request->get('TBK_ORDEN_COMPRA');
        $tbk_token = $request->get('TBK_TOKEN');

        $result = false;

        if (is_null($token_ws)) {
            if (is_null($tbk_token)) {
                /* TimeOut 10 minutos */
                $webpayTransaction = WebpayTransaction::orderBy('id', 'DESC')
                ->where('token', $tbk_token)
                ->firstOrFail();
                
                $webpayTransaction->status = WebpayTransaction::STATUS_FAILED;
                $webpayTransaction->save();
                $result = trans($this->pathPlugin . '::lang.errors.payment_timeout');
            } else {
                /* Pago abortado */
                $webpayTransaction = WebpayTransaction::orderBy('id', 'DESC')
                    ->where('order_id', $tbk_orden_compra)
                    ->where('session_id', $tbk_id_session)
                    ->firstOrFail();

                $webpayTransaction->status = WebpayTransaction::STATUS_ABORTED_BY_USER;
                $webpayTransaction->save();
                $result = trans($this->pathPlugin . '::lang.errors.payment_aborted');
            }
        } elseif (!is_null($tbk_token)) {
            /*
            Error en el Pago:
            Si ocurre un error en el formulario de pago, y hace click en el link de "volver al sitio"
            de la pantalla de error: (replicable solo en producción si inicias una transacción,
            abres el formulario de pago, cierras el tab de Chrome y luego lo recuperas
            */

            $webpayTransaction = WebpayTransaction::orderBy('id', 'DESC')->where('token', $token_ws)->firstOrFail();
            $webpayTransaction->status = WebpayTransaction::STATUS_FAILED;
            $webpayTransaction->save();
            $result = trans($this->pathPlugin . '::lang.errors.payment_error');
        }

        return $result;
    }
}
