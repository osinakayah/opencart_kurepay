<?php
/**
 * Created by PhpStorm.
 * User: osinakayah
 * Date: 20/02/2019
 * Time: 3:12 PM
 */

class ControllerExtensionPaymentKurepay extends Controller
{
    private $error = array();

    public function index() {
        $this->load->language('extension/payment/kurepay');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->model_setting_setting->editSetting('kurepay', $this->request->post);
            $this->session->data['success'] = 'Success: You have modified your Kurepay account details!';
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
        }



        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/kurepay', 'user_token=' . $this->session->data['user_token'])
        );

//        $data['heading_title'] = $this->language->get('heading_title');
//        $data['entry_text_config_one'] = $this->language->get('text_config_one');
//        $data['entry_text_config_two'] = $this->language->get('text_config_two');
//        $data['button_save'] = $this->language->get('text_button_save');
//        $data['button_cancel'] = $this->language->get('text_button_cancel');
//        $data['entry_order_status'] = $this->language->get('entry_order_status');
//        $data['text_enabled'] = $this->language->get('text_enabled');
//        $data['text_disabled'] = $this->language->get('text_disabled');
//        $data['entry_status'] = $this->language->get('entry_status');

        $data['action'] = $this->url->link('extension/payment/kurepay', 'user_token=' . $this->session->data['user_token']);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

        if (isset($this->request->post['payment_kurepay_public_key'])) {
            $data['payment_kurepay_public_key'] = $this->request->post['payment_kurepay_public_key'];
        } else {
            $data['payment_kurepay_public_key'] = $this->config->get('payment_kurepay_public_key');
        }

//        if (isset($this->request->post['text_config_one'])) {
//            $data['text_config_one'] = $this->request->post['text_config_one'];
//        } else {
//            $data['text_config_one'] = $this->config->get('text_config_one');
//        }
//
//        if (isset($this->request->post['text_config_two'])) {
//            $data['text_config_two'] = $this->request->post['text_config_two'];
//        } else {
//            $data['text_config_two'] = $this->config->get('text_config_two');
//        }
//
//        if (isset($this->request->post['custom_status'])) {
//            $data['custom_status'] = $this->request->post['custom_status'];
//        } else {
//            $data['custom_status'] = $this->config->get('custom_status');
//        }
//
//        if (isset($this->request->post['custom_order_status_id'])) {
//            $data['custom_order_status_id'] = $this->request->post['custom_order_status_id'];
//        } else {
//            $data['custom_order_status_id'] = $this->config->get('custom_order_status_id');
//        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('extension/payment/kurepay_view', $data));
    }
}