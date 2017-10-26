<?php

class ControllerCheckoutBuy extends Controller {

    public function index() {
        $this->load->language('checkout/buy');
        $this->load->model('setting/setting');
        $lang = '_'.$this->session->data['language'];
        $data['lang'] = $lang;
        $data['settings'] = $this->model_setting_setting->getSetting('buy');

        $this->document->setTitle($data['settings']['buy_meta_title'.$lang]);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/buy'),
            'text' => $data['settings']['buy_h1'.$lang]
        );

        if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
            $data['heading_title'] = $data['settings']['buy_h1'.$lang];

            $data['text_recurring'] = $this->language->get('text_recurring');
            $data['text_length'] = $this->language->get('text_length');
            $data['text_recurring_item'] = $this->language->get('text_recurring_item');
            $data['text_next'] = $this->language->get('text_next');
            $data['text_next_choice'] = $this->language->get('text_next_choice');

            $data['column_image'] = $this->language->get('column_image');
            $data['column_name'] = $this->language->get('column_name');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_quantity'] = $this->language->get('column_quantity');
            $data['column_price'] = $this->language->get('column_price');
            $data['column_total'] = $this->language->get('column_total');

            $data['button_update'] = $this->language->get('button_update');
            $data['button_remove'] = $this->language->get('button_remove');
            $data['button_shopping'] = $this->language->get('button_shopping');
            $data['button_checkout'] = $this->language->get('button_checkout');
            $data['button_order'] = $this->language->get('button_order');

            $data['text_select'] = $this->language->get('text_select');
            $data['text_none'] = $this->language->get('text_none');
            $data['text_your_details'] = $this->language->get('text_your_details');
            $data['text_your_account'] = $this->language->get('text_your_account');
            $data['text_your_address'] = $this->language->get('text_your_address');
            $data['text_loading'] = $this->language->get('text_loading');

            $data['entry_firstname'] = $this->language->get('entry_firstname');
            $data['entry_lastname'] = $this->language->get('entry_lastname');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_telephone'] = $this->language->get('entry_telephone');
            $data['entry_fax'] = $this->language->get('entry_fax');
            $data['entry_company'] = $this->language->get('entry_company');
            $data['entry_customer_group'] = $this->language->get('entry_customer_group');
            $data['entry_address_1'] = $this->language->get('entry_address_1');
            $data['entry_address_2'] = $this->language->get('entry_address_2');
            $data['entry_postcode'] = $this->language->get('entry_postcode');
            $data['entry_city'] = $this->language->get('entry_city');
            $data['entry_country'] = $this->language->get('entry_country');
            $data['entry_zone'] = $this->language->get('entry_zone');
            $data['entry_shipping'] = $this->language->get('entry_shipping');
            $data['entry_comment'] = $this->language->get('entry_comment');

            if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
                $data['error_warning'] = $this->language->get('error_stock');
            } elseif (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
                $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
            } else {
                $data['attention'] = '';
            }

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            $data['action'] = $this->url->link('checkout/buy/edit');

            if ($this->config->get('config_cart_weight')) {
                $data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            } else {
                $data['weight'] = '';
            }

            $this->load->model('tool/image');
            $this->load->model('tool/upload');

            $data['products'] = array();

            $products = $this->cart->getProducts();

            foreach ($products as $product) {
                $product_total = 0;

                foreach ($products as $product_2) {
                    if ($product_2['product_id'] == $product['product_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                if ($product['minimum'] > $product_total) {
                    $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                }

                if ($product['image']) {
                    $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
                } else {
                    $image = '';
                }

                $option_data = array();

                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
                } else {
                    $total = false;
                }

                $recurring = '';

                if ($product['recurring']) {
                    $frequencies = array(
                        'day' => $this->language->get('text_day'),
                        'week' => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month' => $this->language->get('text_month'),
                        'year' => $this->language->get('text_year'),
                    );

                    if ($product['recurring']['trial']) {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    }

                    if ($product['recurring']['duration']) {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_until_canceled_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                }

                $data['products'][] = array(
                    'key' => $product['cart_id'],
                    'thumb' => $image,
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'recurring' => $recurring,
                    'quantity' => $product['quantity'],
                    'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                    'price' => $price,
                    'total' => $total,
                    'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }

            // Gift Voucher
            $data['vouchers'] = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $key => $voucher) {
                    $data['vouchers'][] = array(
                        'key' => $key,
                        'description' => $voucher['description'],
                        'amount' => $this->currency->format($voucher['amount']),
                        'remove' => $this->url->link('checkout/cart', 'remove=' . $key)
                    );
                }
            }

            // Totals
            $this->load->model('extension/extension');

            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $sort_order = array();

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);

                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }

                $sort_order = array();

                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }

            $data['totals'] = array();

            foreach ($total_data as $total) {
                $data['totals'][] = array(
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'])
                );
            }

            $data['continue'] = $this->url->link('common/home');

            $data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

            $this->load->model('extension/extension');

            $data['checkout_buttons'] = array();

            /* Guest information */
            $data['customer_groups'] = array();

            if (is_array($this->config->get('config_customer_group_display'))) {
                $this->load->model('account/customer_group');

                $customer_groups = $this->model_account_customer_group->getCustomerGroups();

                foreach ($customer_groups as $customer_group) {
                    if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                        $data['customer_groups'][] = $customer_group;
                    }
                }
            }

            if (isset($this->session->data['guest']['customer_group_id'])) {
                $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
            } else if($this->customer->isLogged()) {
                $data['customer_group_id'] = $this->customer->getGroupId();
            } else {
                $data['customer_group_id'] = $this->config->get('config_customer_group_id');
            }
            if (isset($this->session->data['guest']['firstname'])) {
                $data['firstname'] = $this->session->data['guest']['firstname'];
            } else if($this->customer->isLogged()) {
                $data['firstname'] = $this->customer->getFirstname();
            } else {
                $data['firstname'] = '';
            }

            if (isset($this->session->data['guest']['lastname'])) {
                $data['lastname'] = $this->session->data['guest']['lastname'];
            } else if($this->customer->isLogged()) {
                $data['lastname'] = $this->customer->getLastname();
            } else {
                $data['lastname'] = '';
            }

            if (isset($this->session->data['guest']['email'])) {
                $data['email'] = $this->session->data['guest']['email'];
            } else if($this->customer->isLogged()) {
                $data['email'] = $this->customer->getEmail();
            } else {
                $data['email'] = '';
            }

            if (isset($this->session->data['guest']['telephone'])) {
                $data['telephone'] = $this->session->data['guest']['telephone'];
            } else if($this->customer->isLogged()) {
                $data['telephone'] = $this->customer->getTelephone();
            } else {
                $data['telephone'] = '';
            }

            if (isset($this->session->data['guest']['fax'])) {
                $data['fax'] = $this->session->data['guest']['fax'];
            } else if($this->customer->isLogged()) {
                $data['fax'] = $this->customer->getFax();
            } else {
                $data['fax'] = '';
            }
            
            $address = array();
            if($this->customer->isLogged()){
                $this->load->model('account/address');
                $address = $this->model_account_address->getAddress($this->customer->getAddressId());
            }
            
            if (isset($this->session->data['payment_address']['company'])) {
                $data['company'] = $this->session->data['payment_address']['company'];
            } else if($address) {
                $data['company'] = $address['company'];
            } else {
                $data['company'] = '';
            }

            if (isset($this->session->data['payment_address']['address_1'])) {
                $data['address_1'] = $this->session->data['payment_address']['address_1'];
            } else if($address) {
                $data['address_1'] = $address['address_1'];
            } else {
                $data['address_1'] = '';
            }

            if (isset($this->session->data['payment_address']['address_2'])) {
                $data['address_2'] = $this->session->data['payment_address']['address_2'];
            } else if($address) {
                $data['address_2'] = $address['address_2'];
            } else {
                $data['address_2'] = '';
            }

            if (isset($this->session->data['payment_address']['postcode'])) {
                $data['postcode'] = $this->session->data['payment_address']['postcode'];
            } elseif (isset($this->session->data['shipping_address']['postcode'])) {
                $data['postcode'] = $this->session->data['shipping_address']['postcode'];
            } else if($address) {
                $data['postcode'] = $address['postcode'];
            } else {
                $data['postcode'] = '';
            }

            if (isset($this->session->data['payment_address']['city'])) {
                $data['city'] = $this->session->data['payment_address']['city'];
            } else if($address) {
                $data['city'] = $address['city'];
            } else {
                $data['city'] = '';
            }

            if (isset($this->session->data['payment_address']['country_id'])) {
                $data['country_id'] = $this->session->data['payment_address']['country_id'];
            } elseif (isset($this->session->data['shipping_address']['country_id'])) {
                $data['country_id'] = $this->session->data['shipping_address']['country_id'];
            } else if($address) {
                $data['country_id'] = $address['country_id'];
            } else {
                $data['country_id'] = $this->config->get('config_country_id');
            }

            if (isset($this->session->data['payment_address']['zone_id'])) {
                $data['zone_id'] = $this->session->data['payment_address']['zone_id'];
            } elseif (isset($this->session->data['shipping_address']['zone_id'])) {
                $data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
            } else if($address) {
                $data['zone_id'] = $address['zone_id'];
            } else {
                $data['zone_id'] = $this->config->get('config_zone_id');
            }

            $this->load->model('localisation/country');

            $data['countries'] = $this->model_localisation_country->getCountries();

            // Custom Fields
            $this->load->model('account/custom_field');

            $data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

            if (isset($this->session->data['guest']['custom_field'])) {
                $data['guest_custom_field'] = $this->session->data['guest']['custom_field'] + $this->session->data['payment_address']['custom_field'];
            } else {
                $data['guest_custom_field'] = array();
            }

            $data['shipping_required'] = $this->cart->hasShipping();

            if (isset($this->session->data['guest']['shipping_address'])) {
                $data['shipping_address'] = $this->session->data['guest']['shipping_address'];
            } else {
                $data['shipping_address'] = true;
            }

            if (isset($this->session->data['comment'])) {
                $data['comment'] = $this->session->data['comment'];
            } else {
                $data['comment'] = '';
            }
            
            if(!isset($this->session->data['payment_methods'])){
                if (isset($this->session->data['payment_address'])) {
                    $payment_address = $this->session->data['payment_address'];
                } else {
                    $payment_address = array();
                    $payment_address['country_id'] = $this->config->get('config_country_id');
                    $payment_address['zone_id'] = '';
                }

                // Selected payment methods should be from cart sub total not total!
                $total = $this->cart->getSubTotal();

                // Payment Methods
                $method_data = array();

                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('payment');

                $recurring = $this->cart->hasRecurringProducts();

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('payment/' . $result['code']);

                        $method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total);

                        if ($method) {
                            if ($recurring) {
                                if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                                    $method_data[$result['code']] = $method;
                                }
                            } else {
                                $method_data[$result['code']] = $method;
                            }
                        }
                    }
                }

                $sort_order = array();

                foreach ($method_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $method_data);

                $this->session->data['payment_methods'] = $method_data;
            }
            
            if(!isset($this->session->data['shipping_methods'])){
                if (isset($this->session->data['shipping_address'])) {
                    $shipping_address = $this->session->data['shipping_address'];
                } else {
                    $shipping_address = array();
                    $shipping_address['country_id'] = $this->config->get('config_country_id');
                    $shipping_address['zone_id'] = '';
                }

                // Shipping Methods
                $method_data = array();

                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('shipping');

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('shipping/' . $result['code']);

                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);

                        if ($quote) {
                            $method_data[$result['code']] = array(
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error']
                            );
                        }
                    }
                }

                $sort_order = array();

                foreach ($method_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $method_data);

                $this->session->data['shipping_methods'] = $method_data;
            }

            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info) {
                    $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
                } else {
                    $data['text_agree'] = '';
                }
            } else {
                $data['text_agree'] = '';
            }

            if (isset($this->session->data['agree'])) {
                $data['agree'] = $this->session->data['agree'];
            } else {
                $data['agree'] = '';
            }

            /* END Guest information */

        /*    $data['coupon'] = $this->load->controller('checkout/coupon');
            $data['voucher'] = $this->load->controller('checkout/voucher');
            $data['reward'] = $this->load->controller('checkout/reward');
            $data['shipping'] = $this->load->controller('checkout/shipping');
            */
            
            $data['coupon'] = "";
            $data['voucher'] = "";
            $data['reward'] = "";
            $data['shipping'] = "";
            
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/buy.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/buy.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/checkout/buy.tpl', $data));
            }
        } else {
            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_error'] = $this->language->get('text_empty');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            unset($this->session->data['success']);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }

    public function edit() {
        $this->load->language('checkout/buy');

        $json = array();

        // Update
        if (!empty($this->request->post['quantity'])) {
            foreach ($this->request->post['quantity'] as $key => $value) {
                $this->cart->update($key, $value);
            }

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);

            $this->response->redirect($this->url->link('checkout/buy'));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function remove() {
        $this->load->language('checkout/buy');

        $json = array();

        // Remove
        if (isset($this->request->post['key'])) {
            $this->cart->remove($this->request->post['key']);

            unset($this->session->data['vouchers'][$this->request->post['key']]);

            $this->session->data['success'] = $this->language->get('text_remove');

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);

            // Totals
            $this->load->model('extension/extension');

            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $sort_order = array();

                $results = $this->model_extension_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get($result['code'] . '_status')) {
                        $this->load->model('total/' . $result['code']);

                        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                    }
                }

                $sort_order = array();

                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }

            $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function save() {
        $this->load->language('checkout/buy');
        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('buy');

        $json = array();

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $json['redirect'] = $this->url->link('checkout/buy');

                break;
            }
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $json['redirect'] = $this->url->link('checkout/buy');
        }

        if (!$json) {
            if ($settings['buy_firstname_status'] && $settings['buy_firstname_required'] && (!isset($this->request->post['firstname']) || utf8_strlen(trim($this->request->post['firstname'])) < 1 || utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
                $json['error']['firstname'] = $this->language->get('error_firstname');
            }

            if ($settings['buy_lastname_status'] && $settings['buy_lastname_required'] && (!isset($this->request->post['lastname']) || utf8_strlen(trim($this->request->post['lastname'])) < 1 || utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
                $json['error']['lastname'] = $this->language->get('error_lastname');
            }

            if ($settings['buy_email_status'] && $settings['buy_email_required'] && (!isset($this->request->post['email']) || utf8_strlen($this->request->post['email']) > 96 || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if ($settings['buy_telephone_status'] && $settings['buy_telephone_required'] && (!isset($this->request->post['telephone']) || utf8_strlen($this->request->post['telephone']) < 3 || utf8_strlen($this->request->post['telephone']) > 32)) {
                $json['error']['telephone'] = $this->language->get('error_telephone');
            }

            if ($settings['buy_fax_status'] && $settings['buy_fax_required'] && (!isset($this->request->post['fax']) || utf8_strlen($this->request->post['fax']) < 3 || utf8_strlen($this->request->post['fax']) > 32)) {
                $json['error']['fax'] = $this->language->get('error_fax');
            }

            if ($settings['buy_address_1_status'] && $settings['buy_address_1_required'] && (!isset($this->request->post['address_1']) || utf8_strlen(trim($this->request->post['address_1'])) < 3 || utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
                $json['error']['address_1'] = $this->language->get('error_address_1');
            }
            if ($settings['buy_address_2_status'] && $settings['buy_address_2_required'] && (!isset($this->request->post['address_2']) || utf8_strlen(trim($this->request->post['address_2'])) < 3 || utf8_strlen(trim($this->request->post['address_2'])) > 128)) {
                $json['error']['address_2'] = $this->language->get('error_address_2');
            }

            if ($settings['buy_city_status'] && $settings['buy_city_required'] && (!isset($this->request->post['city']) || utf8_strlen(trim($this->request->post['city'])) < 2 || utf8_strlen(trim($this->request->post['city'])) > 128)) {
                $json['error']['city'] = $this->language->get('error_city');
            }

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

            if ($settings['buy_postcode_status'] && $settings['buy_postcode_required'] && $country_info && $country_info['postcode_required'] && (!isset($this->request->post['postcode']) || utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
                $json['error']['postcode'] = $this->language->get('error_postcode');
            }

            if ($settings['buy_country_status'] && $settings['buy_country_required'] && $this->request->post['country_id'] == '') {
                $json['error']['country'] = $this->language->get('error_country');
            }

            if ($settings['buy_zone_status'] && $settings['buy_zone_required'] && (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '')) {
                $json['error']['zone'] = $this->language->get('error_zone');
            }
            if ($settings['buy_comment_status'] && $settings['buy_comment_required'] && (!isset($this->request->post['comment']) || $this->request->post['comment'] == '')) {
                $json['error']['comment'] = $this->language->get('error_comment');
            }
            if ($settings['buy_company_status'] && $settings['buy_company_required'] && (!isset($this->request->post['company']) || $this->request->post['company'] == '')) {
                $json['error']['company'] = $this->language->get('error_company');
            }

            // Customer Group
            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            // Custom field validation
            $this->load->model('account/custom_field');

            $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

            foreach ($custom_fields as $custom_field) {
                if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
                    $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }

            if (!isset($this->request->post['shipping_method'])) {
                $json['error']['warning'] = $this->language->get('error_shipping');
            } else {
                $shipping = explode('.', $this->request->post['shipping_method']);

                if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                    $json['error']['warning'] = $this->language->get('error_shipping');
                }
            }

            if (!isset($this->request->post['payment_method'])) {
                $json['error']['warning'] = $this->language->get('error_payment');
            } elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
                $json['error']['warning'] = $this->language->get('error_payment');
            }

            if ($this->config->get('config_checkout_id')) {
                $this->load->model('catalog/information');

                $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

                if ($information_info && !isset($this->request->post['agree'])) {
                    $json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
                }
            }
            
            if ($settings['buy_min_order_sum'] && $settings['buy_min_order_sum'] && $this->cart->getTotal() < $settings['buy_min_order_sum']) {
                $json['error']['warning'] = sprintf($this->language->get('error_min_order_sum'), $this->currency->format($settings['buy_min_order_sum']));
            }
        }

        if (!$json) {
            $this->session->data['account'] = 'guest';

            $empty = '';

            $this->session->data['guest']['customer_group_id'] = $customer_group_id;
            $this->session->data['guest']['firstname'] = isset($this->request->post['firstname'])?$this->request->post['firstname']:$empty;
            $this->session->data['guest']['lastname'] = isset($this->request->post['lastname'])?$this->request->post['lastname']:$empty;
            $this->session->data['guest']['email'] = isset($this->request->post['email'])?$this->request->post['email']:$empty;
            $this->session->data['guest']['telephone'] = isset($this->request->post['telephone'])?$this->request->post['telephone']:$empty;
            $this->session->data['guest']['fax'] = isset($this->request->post['fax'])?$this->request->post['fax']:$empty;

            if (isset($this->request->post['custom_field']['account'])) {
                $this->session->data['guest']['custom_field'] = $this->request->post['custom_field']['account'];
            } else {
                $this->session->data['guest']['custom_field'] = array();
            }

            $this->session->data['payment_address']['firstname'] = isset($this->request->post['firstname'])?$this->request->post['firstname']:$empty;
            $this->session->data['payment_address']['lastname'] = isset($this->request->post['lastname'])?$this->request->post['lastname']:$empty;
            $this->session->data['payment_address']['company'] = isset($this->request->post['company'])?$this->request->post['company']:$empty;
            $this->session->data['payment_address']['address_1'] = isset($this->request->post['address_1'])?$this->request->post['address_1']:$empty;
            $this->session->data['payment_address']['address_2'] = isset($this->request->post['address_2'])?$this->request->post['address_2']:$empty;
            $this->session->data['payment_address']['postcode'] = isset($this->request->post['postcode'])?$this->request->post['postcode']:$empty;
            $this->session->data['payment_address']['city'] = isset($this->request->post['city'])?$this->request->post['city']:$empty;
            $this->session->data['payment_address']['country_id'] = isset($this->request->post['country_id'])?$this->request->post['country_id']:$empty;
            $this->session->data['payment_address']['zone_id'] = isset($this->request->post['zone_id'])?$this->request->post['zone_id']:$empty;

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

            if ($country_info) {
                $this->session->data['payment_address']['country'] = $country_info['name'];
                $this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
                $this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
                $this->session->data['payment_address']['address_format'] = $country_info['address_format'];
            } else {
                $this->session->data['payment_address']['country'] = '';
                $this->session->data['payment_address']['iso_code_2'] = '';
                $this->session->data['payment_address']['iso_code_3'] = '';
                $this->session->data['payment_address']['address_format'] = '';
            }

            if (isset($this->request->post['custom_field']['address'])) {
                $this->session->data['payment_address']['custom_field'] = $this->request->post['custom_field']['address'];
            } else {
                $this->session->data['payment_address']['custom_field'] = array();
            }

            $this->load->model('localisation/zone');

            $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

            if ($zone_info) {
                $this->session->data['payment_address']['zone'] = $zone_info['name'];
                $this->session->data['payment_address']['zone_code'] = $zone_info['code'];
            } else {
                $this->session->data['payment_address']['zone'] = '';
                $this->session->data['payment_address']['zone_code'] = '';
            }

            $this->session->data['guest']['shipping_address'] = $this->session->data['payment_address'];

            // Default Payment Address
            if ($this->session->data['guest']['shipping_address']) {
                $this->session->data['shipping_address']['firstname'] = isset($this->request->post['firstname'])?$this->request->post['firstname']:$empty;
                $this->session->data['shipping_address']['lastname'] = isset($this->request->post['lastname'])?$this->request->post['lastname']:$empty;
                $this->session->data['shipping_address']['company'] = isset($this->request->post['company'])?$this->request->post['company']:$empty;
                $this->session->data['shipping_address']['address_1'] = isset($this->request->post['address_1'])?$this->request->post['address_1']:$empty;
                $this->session->data['shipping_address']['address_2'] = isset($this->request->post['address_2'])?$this->request->post['address_2']:$empty;
                $this->session->data['shipping_address']['postcode'] = isset($this->request->post['postcode'])?$this->request->post['postcode']:$empty;
                $this->session->data['shipping_address']['city'] = isset($this->request->post['city'])?$this->request->post['city']:$empty;
                $this->session->data['shipping_address']['country_id'] = isset($this->request->post['country_id'])?$this->request->post['country_id']:$empty;
                $this->session->data['shipping_address']['zone_id'] = isset($this->request->post['zone_id'])?$this->request->post['zone_id']:$empty;

                if ($country_info) {
                    $this->session->data['shipping_address']['country'] = $country_info['name'];
                    $this->session->data['shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
                    $this->session->data['shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
                    $this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
                } else {
                    $this->session->data['shipping_address']['country'] = '';
                    $this->session->data['shipping_address']['iso_code_2'] = '';
                    $this->session->data['shipping_address']['iso_code_3'] = '';
                    $this->session->data['shipping_address']['address_format'] = '';
                }

                if ($zone_info) {
                    $this->session->data['shipping_address']['zone'] = $zone_info['name'];
                    $this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
                } else {
                    $this->session->data['shipping_address']['zone'] = '';
                    $this->session->data['shipping_address']['zone_code'] = '';
                }

                if (isset($this->request->post['custom_field']['address'])) {
                    $this->session->data['shipping_address']['custom_field'] = $this->request->post['custom_field']['address'];
                } else {
                    $this->session->data['shipping_address']['custom_field'] = array();
                }
            }

            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
            $this->session->data['comment']= isset($this->request->post['comment'])?$this->request->post['comment']:'';

            /* unset($this->session->data['shipping_method']);
              unset($this->session->data['shipping_methods']);
              unset($this->session->data['payment_method']);
              unset($this->session->data['payment_methods']); */
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getShippingMethods() {
        $this->load->language('checkout/checkout');

        if (isset($this->session->data['shipping_address'])) {
            $shipping_address = $this->session->data['shipping_address'];
        } else {
            $shipping_address = array();
            $shipping_address['country_id'] = $this->config->get('config_country_id');
            $shipping_address['zone_id'] = $this->request->get['zone_id'];
        }

        // Shipping Methods
        $method_data = array();

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('shipping/' . $result['code']);

                $quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);

                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title' => $quote['title'],
                        'quote' => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error' => $quote['error']
                    );
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['shipping_methods'] = $method_data;

        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_loading'] = $this->language->get('text_loading');

        if (empty($this->session->data['shipping_methods'])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['shipping_methods'])) {
            $data['shipping_methods'] = $this->session->data['shipping_methods'];
        } else {
            $data['shipping_methods'] = array();
        }

        if (isset($this->session->data['shipping_method']['code'])) {
            $data['code'] = $this->session->data['shipping_method']['code'];
        } else {
            $data['code'] = '';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/buy_shipping_method.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/buy_shipping_method.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/buy_shipping_method.tpl', $data));
        }
    }

    public function getPaymentMethods() {
        $this->load->language('checkout/checkout');

        if (isset($this->session->data['payment_address'])) {
            $payment_address = $this->session->data['payment_address'];
        } else {
            $payment_address = array();
            $payment_address['country_id'] = $this->config->get('config_country_id');
            $payment_address['zone_id'] = $this->request->get['zone_id'];
        }

        // Selected payment methods should be from cart sub total not total!
        $total = $this->cart->getSubTotal();

        // Payment Methods
        $method_data = array();

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('payment');

        $recurring = $this->cart->hasRecurringProducts();

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total);

                if ($method) {
                    if ($recurring) {
                        if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                            $method_data[$result['code']] = $method;
                        }
                    } else {
                        $method_data[$result['code']] = $method;
                    }
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['payment_methods'] = $method_data;

        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_continue'] = $this->language->get('button_continue');

        if (empty($this->session->data['payment_methods'])) {
            $data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['payment_methods'])) {
            $data['payment_methods'] = $this->session->data['payment_methods'];
        } else {
            $data['payment_methods'] = array();
        }

        if (isset($this->session->data['payment_method']['code'])) {
            $data['code'] = $this->session->data['payment_method']['code'];
        } else {
            $data['code'] = '';
        }

        $data['scripts'] = $this->document->getScripts();

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        if (isset($this->session->data['agree'])) {
            $data['agree'] = $this->session->data['agree'];
        } else {
            $data['agree'] = '';
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/buy_payment_method.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/buy_payment_method.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/checkout/buy_payment_method.tpl', $data));
        }
    }

    function getPaymentForm() {
        if (isset($this->session->data['payment_methods'])) {
            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
            if (isset($this->request->post['code'])) {
                $json = array();
                $json['output'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
            }
        }
    }

    public function confirm() {
        $redirect = '';
        $reason = '';

        if ($this->cart->hasShipping()) {
            // Validate if shipping address has been set.
            if (!isset($this->session->data['shipping_address'])) {
                $redirect = $this->url->link('checkout/buy', '', 'SSL');
                $reason = 'shipping_address';
            }

            // Validate if shipping method has been set.
            if (!isset($this->session->data['shipping_method'])) {
                $redirect = $this->url->link('checkout/buy', '', 'SSL');
                $reason = 'shipping_method';
            }
        } else {
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        // Validate if payment address has been set.
        if (!isset($this->session->data['payment_address'])) {
            $redirect = $this->url->link('checkout/buy', '', 'SSL');
            $reason = 'payment_address';
        }

        // Validate if payment method has been set.
        if (!isset($this->session->data['payment_method'])) {
            $redirect = $this->url->link('checkout/buy', '', 'SSL');
            $reason = 'payment_method';
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $redirect = $this->url->link('checkout/buy');
            $reason = 'Validate cart has products and has stock';
        }

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $redirect = $this->url->link('checkout/buy');
                $reason = 'minimum';

                break;
            }
        }

        if (!$redirect) {
            $order_data = array();

            $order_data['totals'] = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('extension/extension');

            $sort_order = array();

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
                }
            }

            $sort_order = array();

            foreach ($order_data['totals'] as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $order_data['totals']);

            $this->load->language('checkout/checkout');

            $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $order_data['store_id'] = $this->config->get('config_store_id');
            $order_data['store_name'] = $this->config->get('config_name');

            if ($order_data['store_id']) {
                $order_data['store_url'] = $this->config->get('config_url');
            } else {
                $order_data['store_url'] = HTTP_SERVER;
            }

            if ($this->customer->isLogged()) {
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                $order_data['customer_id'] = $this->customer->getId();
                $order_data['customer_group_id'] = $customer_info['customer_group_id'];
                $order_data['firstname'] = $customer_info['firstname'];
                $order_data['lastname'] = $customer_info['lastname'];
                $order_data['email'] = $customer_info['email'];
                $order_data['telephone'] = $customer_info['telephone'];
                $order_data['fax'] = $customer_info['fax'];
                $order_data['custom_field'] = unserialize($customer_info['custom_field']);
            } elseif (isset($this->session->data['guest'])) {
                $order_data['customer_id'] = 0;
                $order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                $order_data['firstname'] = $this->session->data['guest']['firstname'];
                $order_data['lastname'] = $this->session->data['guest']['lastname'];
                $order_data['email'] = $this->session->data['guest']['email'];
                $order_data['telephone'] = $this->session->data['guest']['telephone'];
                $order_data['fax'] = $this->session->data['guest']['fax'];
                $order_data['custom_field'] = $this->session->data['guest']['custom_field'];
            }

            $order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
            $order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
            $order_data['payment_company'] = $this->session->data['payment_address']['company'];
            $order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
            $order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
            $order_data['payment_city'] = $this->session->data['payment_address']['city'];
            $order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
            $order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
            $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
            $order_data['payment_country'] = $this->session->data['payment_address']['country'];
            $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
            $order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
            $order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];

            if (isset($this->session->data['payment_method']['title'])) {
                $order_data['payment_method'] = $this->session->data['payment_method']['title'];
            } else {
                $order_data['payment_method'] = '';
            }

            if (isset($this->session->data['payment_method']['code'])) {
                $order_data['payment_code'] = $this->session->data['payment_method']['code'];
            } else {
                $order_data['payment_code'] = '';
            }

            if ($this->cart->hasShipping()) {
                $order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
                $order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
                $order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
                $order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
                $order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
                $order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
                $order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
                $order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
                $order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
                $order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
                $order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
                $order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
                $order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];

                if (isset($this->session->data['shipping_method']['title'])) {
                    $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
                } else {
                    $order_data['shipping_method'] = '';
                }

                if (isset($this->session->data['shipping_method']['code'])) {
                    $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
                } else {
                    $order_data['shipping_code'] = '';
                }
            } else {
                $order_data['shipping_firstname'] = '';
                $order_data['shipping_lastname'] = '';
                $order_data['shipping_company'] = '';
                $order_data['shipping_address_1'] = '';
                $order_data['shipping_address_2'] = '';
                $order_data['shipping_city'] = '';
                $order_data['shipping_postcode'] = '';
                $order_data['shipping_zone'] = '';
                $order_data['shipping_zone_id'] = '';
                $order_data['shipping_country'] = '';
                $order_data['shipping_country_id'] = '';
                $order_data['shipping_address_format'] = '';
                $order_data['shipping_custom_field'] = array();
                $order_data['shipping_method'] = '';
                $order_data['shipping_code'] = '';
            }

            $order_data['products'] = array();

            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                foreach ($product['option'] as $option) {
                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'option_id' => $option['option_id'],
                        'option_value_id' => $option['option_value_id'],
                        'name' => $option['name'],
                        'value' => $option['value'],
                        'type' => $option['type']
                    );
                }

                $order_data['products'][] = array(
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'download' => $product['download'],
                    'quantity' => $product['quantity'],
                    'subtract' => $product['subtract'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward' => $product['reward']
                );
            }

            // Gift Voucher
            $order_data['vouchers'] = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $order_data['vouchers'][] = array(
                        'description' => $voucher['description'],
                        'code' => substr(md5(mt_rand()), 0, 10),
                        'to_name' => $voucher['to_name'],
                        'to_email' => $voucher['to_email'],
                        'from_name' => $voucher['from_name'],
                        'from_email' => $voucher['from_email'],
                        'voucher_theme_id' => $voucher['voucher_theme_id'],
                        'message' => $voucher['message'],
                        'amount' => $voucher['amount']
                    );
                }
            }

            $order_data['comment'] = $this->session->data['comment'];
            $order_data['total'] = $total;

            if (isset($this->request->cookie['tracking'])) {
                $order_data['tracking'] = $this->request->cookie['tracking'];

                $subtotal = $this->cart->getSubTotal();

                // Affiliate
                $this->load->model('affiliate/affiliate');

                $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

                if ($affiliate_info) {
                    $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                    $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                } else {
                    $order_data['affiliate_id'] = 0;
                    $order_data['commission'] = 0;
                }

                // Marketing
                $this->load->model('checkout/marketing');

                $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

                if ($marketing_info) {
                    $order_data['marketing_id'] = $marketing_info['marketing_id'];
                } else {
                    $order_data['marketing_id'] = 0;
                }
            } else {
                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
                $order_data['marketing_id'] = 0;
                $order_data['tracking'] = '';
            }

            $order_data['language_id'] = $this->config->get('config_language_id');
            $order_data['currency_id'] = $this->currency->getId();
            $order_data['currency_code'] = $this->currency->getCode();
            $order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
            $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $order_data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $order_data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $order_data['accept_language'] = '';
            }

            $this->load->model('checkout/order');

            $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);


        }
        $json = array();
        $json['success'] = 1;
        $json['redirect'] = $redirect;
        $json['reason'] = $reason;
        $json['session_data'] = $this->session->data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function selectShipping() {
        
        if(isset($this->request->post['code']) && isset($this->session->data['shipping_methods'])){
            $code = explode('.', $this->request->post['code']);
            
            if(isset($this->session->data['shipping_methods'][$code[0]]['quote'][$code[1]])){
                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$code[0]]['quote'][$code[1]];
                
                $this->load->model('extension/extension');

                $total_data = array();
                $total = 0;
                $taxes = $this->cart->getTaxes();

                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $sort_order = array();

                    $results = $this->model_extension_extension->getExtensions('total');

                    foreach ($results as $key => $value) {
                        $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                    }

                    array_multisort($sort_order, SORT_ASC, $results);

                    foreach ($results as $result) {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('total/' . $result['code']);

                            $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                        }
                    }

                    $sort_order = array();

                    foreach ($total_data as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $total_data);
                }

                $json = array();
                $json['totals'] = '';
                foreach ($total_data as $total) {
                    $json['totals'] .= '<tr class="total-item"><td colspan="4" style="border:none;"> </td><td class="text-right"><strong>'.$total['title'].'</strong></td><td class="text-right">'.$this->currency->format($total['value']).'</td></tr>';
                }
                
                $this->response->setOutput(json_encode($json));
            }
        }
    }

}