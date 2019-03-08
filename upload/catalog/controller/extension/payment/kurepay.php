<?php
class ControllerExtensionPaymentKurepay extends Controller {
    public function index() {
        $this->load->language('extension/payment/kurepay');
        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['action'] = 'https://payment.kurepay.com/init-payment';
        $data['payment_kurepay_public_key'] = $this->config->get('payment_kurepay_public_key');
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info) {
           // $data['orderid'] = $this->session->data['order_id'];
            $data['orderid'] = date('His') . $this->session->data['order_id'];
            $data['callbackurl'] = HTTP_SERVER . "payment_callback/kurepay";
            $data['orderdate'] = date('YmdHis');

            $data['orderamount'] = trim($this->currency->format($order_info['total'], 'NGN', '', false));
            $data['billemail'] = $order_info['email'];
            $data['billphone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
            $data['deliveryname'] = html_entity_decode($order_info['shipping_firstname'] . $order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');

            return $this->load->view('extension/payment/kurepay', $data);
        }
    }

    public function callback() {
        $data = array_merge($this->request->post, $this->request->get);
        $transactionReference = $data['?trans_reference'];
        if ($this->getTransactionStatus($transactionReference)) {
            $order_id = trim(substr($transactionReference, 6));
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($order_id);
            if ($order_info) {
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_kurepay_order_status_id'));
                $this->response->redirect($this->url->link('checkout/success', 'user_token=' . $this->session->data['user_token']));
            }
        }
        else {
            $this->response->redirect($this->url->link('checkout/failure', 'user_token=' . $this->session->data['user_token']));
        }

    }

    private function getTransactionStatus($transactionReference) {
        $publicKey = $this->config->get('payment_kurepay_public_key');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://payment.kurepay.com/api/auth/transaction/status/".$transactionReference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'publicKey'=> $publicKey,
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        $result = json_decode($response);

        if ($result->data->status == 0) {
            return false;
        }
        elseif ($result->data->status == 1) {
            return true;
        }
    }
}