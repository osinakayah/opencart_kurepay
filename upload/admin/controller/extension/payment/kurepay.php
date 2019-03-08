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

            $this->model_setting_setting->editSetting('payment_kurepay', $this->request->post);
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

        $data['action'] = $this->url->link('extension/payment/kurepay', 'user_token=' . $this->session->data['user_token']);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

        if (isset($this->request->post['payment_kurepay_total'])) {
            $data['payment_kurepay_total'] = $this->request->post['payment_kurepay_total'];
        } else {
            $data['payment_kurepay_total'] = $this->config->get('payment_kurepay_total');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_kurepay_status'])) {
            $data['payment_kurepay_status'] = $this->request->post['payment_kurepay_status'];
        } else {
            $data['payment_kurepay_status'] = $this->config->get('payment_kurepay_status');
        }
        if (isset($this->request->post['payment_kurepay_order_status_id'])) {
            $data['payment_kurepay_order_status_id'] = $this->request->post['payment_kurepay_order_status_id'];
        } else {
            $data['payment_kurepay_order_status_id'] = $this->config->get('payment_kurepay_order_status_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['payment_kurepay_geo_zone_id'])) {
            $data['payment_kurepay_geo_zone_id'] = $this->request->post['payment_kurepay_geo_zone_id'];
        } else {
            $data['payment_kurepay_geo_zone_id'] = $this->config->get('payment_kurepay_geo_zone_id');
        }


        if (isset($this->request->post['payment_kurepay_sort_order'])) {
            $data['payment_kurepay_sort_order'] = $this->request->post['payment_kurepay_sort_order'];
        } else {
            $data['payment_kurepay_sort_order'] = $this->config->get('payment_kurepay_sort_order');
        }


        if (isset($this->request->post['payment_kurepay_public_key'])) {
            $data['payment_kurepay_public_key'] = $this->request->post['payment_kurepay_public_key'];
        } else {
            $data['payment_kurepay_public_key'] = $this->config->get('payment_kurepay_public_key');
        }


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('extension/payment/kurepay_view', $data));
    }
}

// // 