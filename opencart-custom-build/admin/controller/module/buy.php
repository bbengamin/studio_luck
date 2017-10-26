<?php

class ControllerModuleBuy extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('module/buy');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('buy', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_form_fields'] = $this->language->get('tab_form_fields');
        $data['tab_headings'] = $this->language->get('tab_headings');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_required'] = $this->language->get('text_required');
        $data['text_not_required'] = $this->language->get('text_not_required');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_single_col'] = $this->language->get('text_single_col');
        $data['text_two_col'] = $this->language->get('text_two_col');
        $data['text_meta_title'] = $this->language->get('text_meta_title');
        $data['text_h1'] = $this->language->get('text_h1');
        $data['text_h2'] = $this->language->get('text_h2');
        $data['text_heading_1'] = $this->language->get('text_heading_1');
        $data['text_heading_2'] = $this->language->get('text_heading_2');
        $data['text_heading_3'] = $this->language->get('text_heading_3');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_fields'] = $this->language->get('text_fields');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_min_order_sum'] = $this->language->get('entry_min_order_sum');
        $data['entry_cart_modules'] = $this->language->get('entry_cart_modules');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_shipping_select'] = $this->language->get('entry_shipping_select');
        $data['entry_payment_select'] = $this->language->get('entry_payment_select');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_telephone_mask'] = $this->language->get('entry_telephone_mask');
        $data['entry_form_design'] = $this->language->get('entry_form_design');
        $data['entry_form_headings'] = $this->language->get('entry_form_headings');
        $data['entry_heading_1'] = $this->language->get('entry_heading_1');
        $data['entry_heading_2'] = $this->language->get('entry_heading_2');
        $data['entry_heading_3'] = $this->language->get('entry_heading_3');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_h1'] = $this->language->get('entry_h1');
        $data['entry_h2'] = $this->language->get('entry_h2');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['token'] = $this->session->data['token'];
        $data['settings'] = $this->model_setting_setting->getSetting('buy');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/buy', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('module/buy', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');


        if (isset($this->request->post['buy_status'])) {
                $data['buy_status'] = $this->request->post['buy_status'];
        } else {
                $data['buy_status'] = $this->config->get('buy_status');
        }
        
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/buy.tpl', $data));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/buy')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}

?>