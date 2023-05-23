<?php
namespace Opencart\Admin\Controller\Extension\Eservice\Payment;
class Eservice extends \Opencart\System\Engine\Controller {


    //Define the $integration_modes,specifies whether integration mode should be shown or not, 1 means to show, 0 means not
    private $integration_show_iframe = "1";
    private $integration_show_redirect = "1";
    private $integration_show_hostedpay = "1";
    /*
     * The index of the mode:
     * hostedpay:0
     * iframe:1
     * redirect: 2
     */
    //specifies the default index of integration mode
    private $default_integration_mode = "0";

    public function index() {
        $this->load->language('extension/eservice/payment/eservice');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        $data = array();
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/eservice/payment/eservice', 'user_token=' . $this->session->data['user_token'])
        );

        $data['save'] = $this->url->link('extension/eservice/payment/eservice|save', 'user_token=' . $this->session->data['user_token']);

        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

       

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');


        $data['entry_clientid'] = $this->language->get('entry_clientid');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_testmode'] = $this->language->get('entry_testmode');
        $data['entry_status'] = $this->language->get('entry_status');


        


        if (isset($this->request->post['payment_eservice_clientid'])) {
            $data['payment_eservice_clientid'] = $this->request->post['payment_eservice_clientid'];
        } else {
            $data['payment_eservice_clientid'] = $this->config->get('payment_eservice_clientid');
        }
        if (isset($this->request->post['payment_eservice_password'])) {
            $data['payment_eservice_password'] = $this->request->post['payment_eservice_password'];
        } else {
            $data['payment_eservice_password'] = $this->config->get('payment_eservice_password');
        }
        if (isset($this->request->post['payment_eservice_brandid'])) {
            $data['payment_eservice_brandid'] = $this->request->post['payment_eservice_brandid'];
        } else {
            $data['payment_eservice_brandid'] = $this->config->get('payment_eservice_brandid');
        }
        if (isset($this->request->post['payment_eservice_status'])) {
            $data['payment_eservice_status'] = $this->request->post['payment_eservice_status'];
        } else {
            $data['payment_eservice_status'] = $this->config->get('payment_eservice_status');
        }



        if (isset($this->request->post['payment_eservice_testmode'])) {
            $data['payment_eservice_testmode'] = $this->request->post['payment_eservice_testmode'];
        } else {
            $data['payment_eservice_testmode'] = $this->config->get('payment_eservice_testmode');
        }
        
        if(empty($this->integration_show_iframe) && empty($this->integration_show_redirect) && empty($this->integration_show_hostedpay)){
            $data['payment_eservice_pay_type'] = $this->default_integration_mode;
            $data['showPayTypeField'] = 0;
        }else{
            $data['showPayTypeField'] = 1;
            if (isset($this->request->post['payment_eservice_pay_type'])) {
                $data['payment_eservice_pay_type'] = $this->request->post['payment_eservice_pay_type'];
            } else {
                $data['payment_eservice_pay_type'] = $this->config->get('payment_eservice_pay_type');
            }
        }


        if (isset($this->request->post['payment_eservice_pay_action'])) {
            $data['payment_eservice_pay_action'] = $this->request->post['payment_eservice_pay_action'];
        } else {
            $data['payment_eservice_pay_action'] = $this->config->get('payment_eservice_pay_action');
        }

        //set up order status
        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        if (isset($this->request->post['payment_eservice_success_status_id'])) {
            $data['payment_eservice_success_status_id'] = $this->request->post['payment_eservice_success_status_id'];
        } else {
            $data['payment_eservice_success_status_id'] = $this->config->get('payment_eservice_success_status_id');
        }
        if (isset($this->request->post['payment_eservice_failed_status_id'])) {
            $data['payment_eservice_failed_status_id'] = $this->request->post['payment_eservice_failed_status_id'];
        } else {
            $data['payment_eservice_failed_status_id'] = $this->config->get('payment_eservice_failed_status_id');
        }
        if (isset($this->request->post['payment_eservice_refunded_status_id'])) {
            $data['payment_eservice_refunded_status_id'] = $this->request->post['payment_eservice_refunded_status_id'];
        } else {
            $data['payment_eservice_refunded_status_id'] = $this->config->get('payment_eservice_refunded_status_id');
        }
        if (isset($this->request->post['payment_eservice_auth_status_id'])) {
            $data['payment_eservice_auth_status_id'] = $this->request->post['payment_eservice_auth_status_id'];
        } else {
            $data['payment_eservice_auth_status_id'] = $this->config->get('payment_eservice_auth_status_id');
        }
        if (isset($this->request->post['payment_eservice_voided_status_id'])) {
            $data['payment_eservice_voided_status_id'] = $this->request->post['payment_eservice_voided_status_id'];
        } else {
            $data['payment_eservice_voided_status_id'] = $this->config->get('payment_eservice_voided_status_id');
        }
        if (isset($this->request->post['payment_eservice_canceled_status_id'])) {
            $data['payment_eservice_canceled_status_id'] = $this->request->post['payment_eservice_canceled_status_id'];
        } else {
            $data['payment_eservice_canceled_status_id'] = $this->config->get('payment_eservice_canceled_status_id');
        }

        if (isset($this->request->post['payment_eservice_sort_order'])) {
            $data['payment_eservice_sort_order'] = $this->request->post['payment_eservice_sort_order'];
        } else {
            $data['payment_eservice_sort_order'] = $this->config->get('payment_eservice_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/eservice/payment/eservice', $data));
    }
    public function save() {
		$this->load->language('extension/eservice/payment/eservice');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/eservice/payment/eservice')) {
			$json['error'] = $this->language->get('error_permission');
		}
        if (!$this->request->post['payment_eservice_brandid']) {
			$json['error'] = $this->language->get('error_brandid');
		}
        if (!$this->request->post['payment_eservice_password']) {
			$json['error'] = $this->language->get('error_password');
		}
		if (!$this->request->post['payment_eservice_clientid']) {
			$json['error'] = $this->language->get('error_clientid');
		}
		if (!$json) {
            $post_data = $this->request->post;
            $post_data['payment_eservice_test_token_url'] = "https://apiuat.test.secure.eservice.com.pl/token";
            $post_data['payment_eservice_test_payments_url'] = "https://apiuat.test.secure.eservice.com.pl/payments";
            $post_data['payment_eservice_test_javascript_url'] = "https://cashierui-apiuat.test.secure.eservice.com.pl/js/api.js";
            $post_data['payment_eservice_test_cashier_url'] = "https://cashierui-apiuat.test.secure.eservice.com.pl/ui/cashier";
            $post_data['payment_eservice_token_url'] = "https://api.secure.eservice.com.pl/token";
            $post_data['payment_eservice_payments_url'] = "https://api.secure.eservice.com.pl/payments";
            $post_data['payment_eservice_javascript_url'] = "https://cashierui-api.secure.eservice.com.pl/js/api.js";
            $post_data['payment_eservice_cashier_url'] = "https://cashierui-api.secure.eservice.com.pl/ui/cashier";
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_eservice', $post_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function install() {
        //by default, the OpenCart4 system sets the session samesite attribute to be Strict, 
        //it causes the cookie can not be carried when the customers are redirected back from Gateway even through a GET request
        //so have to change the samesite attribute to be Lax 
        $this->load->model('setting/setting');
		$this->model_setting_setting->editValue('config', 'config_session_samesite', 'Lax');
        $this->load->model('extension/eservice/payment/eservice');
        $this->model_extension_eservice_payment_eservice->install();
    }

    public function uninstall() {
        $this->load->model('extension/eservice/payment/eservice');
        $this->model_extension_eservice_payment_eservice->uninstall();
    }

    //show the order tab in the back office
    public function order() {
        if ($this->config->get('payment_eservice_status')) {
            $this->load->model('extension/eservice/payment/eservice');

            $order_info = $this->model_extension_eservice_payment_eservice->getOrder($this->request->get['order_id']);
            if (!empty($order_info)) {
                $this->load->language('extension/eservice/payment/eservice');

                $order_info['total_formatted'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1, true);

                $order_info['total_captured'] = $this->model_extension_eservice_payment_eservice->getTotalCaptured($order_info['eservice_order_id']);
                $order_info['total_captured_formatted'] = $this->currency->format($order_info['total_captured'], $order_info['currency_code'], 1, true);

                $order_info['total_refunded'] = $this->model_extension_eservice_payment_eservice->getTotalRefunded($order_info['eservice_order_id']);
                $order_info['total_refunded_formatted'] = $this->currency->format($order_info['total_refunded'], $order_info['currency_code'], 1, true);

                $order_info['unrefunded'] = number_format($order_info['total_captured'] - $order_info['total_refunded'],2,'.','');

                $data = array();
                $data['user_token'] = $this->session->data['user_token'];
                $data['order_info'] = $order_info;
                $data['order_id'] = $this->request->get['order_id'];

                return $this->load->view('extension/eservice/payment/eservice_order', $data);
            }
        }
    }
    public function capture() {
        $this->load->language('extension/eservice/payment/eservice');
        $json = array();

        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
            $this->load->model('extension/eservice/payment/eservice');

            $order = $this->model_extension_eservice_payment_eservice->getOrder($this->request->post['order_id']);
            if(empty($order)){
                $json['error'] = true;
                $json['msg'] = 'Order does not exsit';
            }else{
                //does not support partial capture yet, so capture only one time
                $capture_amount = $order['total'];

                try {
                    $capture_response = $this->model_extension_eservice_payment_eservice->capture($this->request->post['order_id'], $capture_amount);
                } catch (\Exception $e) {
                    $json['error'] = true;
                    $json['msg'] = $this->language->get('text_error_connect_gateway');
                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($json));
                    return;
                }

                if ($capture_response) {
                    $this->model_extension_eservice_payment_eservice->addTransaction($order['eservice_order_id'], 'payment', $capture_amount);
                    $json['msg'] = $this->language->get('text_capture_ok');
                    $this->model_extension_eservice_payment_eservice->updateCaptureStatus($order['eservice_order_id'], 2);

                    $this->model_extension_eservice_payment_eservice->updateOrderHistory($this->request->post['order_id'],$this->config->get('payment_eservice_success_status_id'),'Captured');

                    $json['data'] = array();
                    $json['data']['created'] = $this->model_extension_eservice_payment_eservice->getMysqlNowTime();
                    $json['data']['amount'] = $capture_amount;
                    $json['data']['total_captured_formatted'] = $this->currency->format($capture_amount, $order['currency_code'], 1, true);
                    $json['data']['capture_status'] = 1;
                    $json['data']['total'] = $capture_amount;
                    $json['error'] = false;
                } else {
                    $json['error'] = true;
                    $json['msg'] = 'Capture error';
                }
            }
        } else {
            $json['error'] = true;
            $json['msg'] = 'Missing data';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function void() {
        $this->load->language('extension/eservice/payment/eservice');
        $json = array();

        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
            $this->load->model('extension/eservice/payment/eservice');

            $order = $this->model_extension_eservice_payment_eservice->getOrder($this->request->post['order_id']);

            try {
                $void_response = $this->model_extension_eservice_payment_eservice->void($this->request->post['order_id']);
            } catch (\Exception $e) {
                $json['error'] = true;
                $json['msg'] = $this->language->get('text_error_connect_gateway');
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }


            if ($void_response) {
                $this->model_extension_eservice_payment_eservice->addTransaction($order['eservice_order_id'], 'void', 0.00);
                $this->model_extension_eservice_payment_eservice->updateVoidStatus($order['eservice_order_id'], 1);

                $this->model_extension_eservice_payment_eservice->updateOrderHistory($this->request->post['order_id'],$this->config->get('payment_eservice_voided_status_id'),'Voided');

                $json['msg'] = $this->language->get('text_void_ok');
                $json['data'] = array();
                $json['data']['created'] = $this->model_extension_eservice_payment_eservice->getMysqlNowTime();
                $json['error'] = false;
            } else {
                $json['error'] = true;
                $json['msg'] = 'Void error';
            }
        } else {
            $json['error'] = true;
            $json['msg'] = 'Missing data';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function refund() {
        $this->load->language('extension/eservice/payment/eservice');
        $json = array();

        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '' && isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
            $refund_amount = (double)$this->request->post['amount'];

            $this->load->model('extension/eservice/payment/eservice');

            $order = $this->model_extension_eservice_payment_eservice->getOrder($this->request->post['order_id']);

            try {
                $refund_response = $this->model_extension_eservice_payment_eservice->refund($this->request->post['order_id'],$refund_amount);
            } catch (\Exception $e) {
                $json['error'] = true;
                $json['msg'] = $this->language->get('text_error_connect_gateway');
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }


            if ($refund_response) {
                $this->model_extension_eservice_payment_eservice->addTransaction($order['eservice_order_id'], 'refund', $refund_amount);
                $total_captured = $this->model_extension_eservice_payment_eservice->getTotalCaptured($order['eservice_order_id']);
                $total_refunded = $this->model_extension_eservice_payment_eservice->getTotalRefunded($order['eservice_order_id']);
                $refund_status = 0;
                if ($total_captured == $total_refunded) {
                    $refund_status = 1;
                    $this->model_extension_eservice_payment_eservice->updateRefundStatus($order['eservice_order_id'], $refund_status);
                    $this->model_extension_eservice_payment_eservice->updateOrderHistory($this->request->post['order_id'],$this->config->get('payment_eservice_refunded_status_id'),'Refunded');
                }
                $remaining = number_format($total_captured - $total_refunded, 2, '.', '');


                $json['data'] = array();
                $json['data']['amount'] = number_format($refund_amount, 2, '.', '');
                $json['data']['total_refunded_formatted'] = $this->currency->format($total_refunded, $order['currency_code'], 1, true);
                $json['data']['refund_status'] = $refund_status;
                $json['data']['remaining'] = $remaining;
                $json['msg'] = $this->language->get('text_refund_ok');
                $json['data']['created'] = $this->model_extension_eservice_payment_eservice->getMysqlNowTime();
                $json['error'] = false;
            } else {
                $json['error'] = true;
                $json['msg'] = 'Refund error';
            }
        } else {
            $json['error'] = true;
            $json['msg'] = 'Missing data';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
