<?php
/**
 * Created by PhpStorm.
 * User: osinakayah
 * Date: 28/02/2019
 * Time: 5:12 PM
 */

class ModelExtensionPaymentKurepay extends Model {
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/kurepay');

        $method_data = array(
            'code'     => 'kurepay',
            'title'    => $this->language->get('text_title'),
            'sort_order' => $this->config->get('payment_kurepay_sort_order')
        );

        return $method_data;
    }
}