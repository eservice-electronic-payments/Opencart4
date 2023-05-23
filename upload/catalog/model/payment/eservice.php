<?php
namespace Opencart\Catalog\Model\Extension\Eservice\Payment;
class Eservice extends \Opencart\System\Engine\Model {
	

	public function getMethod($address) {
	    $this->load->language('extension/eservice/payment/eservice');
		$method_data = array(
			'code'       => 'eservice',
			'title'      => $this->language->get('text_title'),
			'sort_order' => $this->config->get('payment_eservice_sort_order')
		);
	    return $method_data;
	}
	//insert the Payment data into the table when a transaction is created through the checkout page
	public function addOrder($order_data) {
	    
	    $this->db->query("INSERT INTO `" . DB_PREFIX . "eservice_order` SET `order_id` = '" . (int)$order_data['order_id'] . "', `created` = NOW(), `modified` = NOW(), "  . "`total` = '" . $order_data['total'] . "', `currency_code` = '" . $this->db->escape($order_data['currency_code']) . "', `merchant_tx_id` = '" . $this->db->escape($order_data['merchant_tx_id']) . "'");
	}
	//get the Payment data from the table
	public function getPaymentData($order_id,$merchantTxId) {
	    $qry = $this->db->query("select * FROM " . DB_PREFIX."eservice_order WHERE order_id = ".(int)($order_id). " AND `merchant_tx_id` = '". $this->db->escape($merchantTxId) . "'");
		if ($qry->num_rows) {
				$row = $qry->row;
				return $row;
		} else {
				return false;
		}
	}
	//update the Payment data when a transaction is authorized:1 or purchased:2
	public function updatePaymentData($order_id,$status) {
	    $this->db->query("UPDATE `" . DB_PREFIX . "eservice_order` SET `capture_status` = '" . (int)$status . "' WHERE `eservice_order_id` = '".(int)($order_id). "'");
	}

	public function addTransaction($eservice_order_id, $type, $amount) {
	    
	    $this->db->query("INSERT INTO `" . DB_PREFIX . "eservice_transaction` SET `eservice_order_id` = '" . (int)$eservice_order_id . "', `created` = NOW(), "  . " `type` = '" . $this->db->escape($type) . "', `amount` = '" . $amount . "'");
	}
	public function hasOrder($order_id){
	    $query = $this->db->query("SELECT `eservice_order_id` FROM `" . DB_PREFIX . "eservice_order` WHERE `order_id` = '" . (int)$order_id . "'");
	    if($query->num_rows){
	        return true;
	    }else{
	        return false;
	    }
	}
	
	public function getOrder($order_id) {
	    $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "eservice_order` WHERE `order_id` = '" . (int)$order_id . "' and `capture_status` is not NULL");
	    
	    if ($qry->num_rows) {
	        $order = $qry->row;
	        $order['transactions'] = $this->getTransactions($order['eservice_order_id']);
	        return $order;
	    } else {
	        return false;
	    }
	}
	private function getTransactions($eservice_order_id) {
	    $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "eservice_transaction` WHERE `eservice_order_id` = '" . (int)$eservice_order_id  . "'");
	    
	    if ($qry->num_rows) {
	        return $qry->rows;
	    } else {
	        return false;
	    }
	}
}
