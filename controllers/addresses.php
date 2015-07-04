<?php
class Addresses extends CI_Controller{
	public $data = array();
	public $customer;
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','pagination','myclass','encrypt'));
		$this->load->library('my_validation');
		$this->load->helper('form');
		$this->load->model('Customer');
		$this->load->model('Address');
		$this->data['customer'] = $this->session->userdata('customer') ? $this->session->userdata('customer'): $this->session->userdata('no-member');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->data['cart_count'] = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
		$this->customer = $this->myclass->_checklogin($this->data['customer']);
	}
		
	public function select_address()
	{
		$form_data = new StdClass();
		if($form_data = $this->session->userdata('add_address'))
		{
			$form_data = $form_data;
		}else{
			$form_data = $this->Address;
		}
		$this->data['title'] = '配送先選択';
		$this->data['h2title'] = '配送先選択';
		$customer = $this->customer;
		$userdata = $this->Customer->get_by_username($customer);
		$addresses = $this->Address->get_addresses($customer);
		if($this->input->post('submit'))
		{
			$this->my_validation->add_address_validation_rules();
			$form_data = $this->input->post();
			$form_data = (object)$form_data;
			if(!$this->form_validation->run() === FALSE){
				$this->session->set_userdata('add_address',$form_data);
				return redirect("addresses/confirm_address");
			}
		}
		$this->data['userdata'] = $userdata;
		$this->data['addresses'] = $addresses;
		$this->data['form_data'] = $form_data;
		$this->load->view('addresses/select_address',$this->data);
	}
	
	public function confirm_address()
	{
		if(!$form_data = $this->session->userdata('add_address'))
		{
			return redirect('addresses/select_address');
		}
		$this->data['title'] = '追加住所の確認';
		$this->data['h2title'] = '追加住所の確認';
		$this->data['button_message'] = 'この住所に配達する';
		if($this->input->post('submit')){
			$_POST = (array)$form_data;
			$this->my_validation->add_address_validation_rules();
			if(!$this->form_validation->run() === FALSE)
			{
				return redirect('addresses/add_address');
			}else{
				return redirect('addresses/select_address');
			};
		}
		$this->data['form_data'] = $form_data;
		$this->load->view('addresses/confirm_address',$this->data);
	}
	
	public function add_address()
	{
		if(!$data = $this->session->userdata('add_address'))
		{
			return redirect('addresses/select_address');
		}
		$customer_data = $this->Customer->get_by_username($this->data['customer']);
		$db_data = array(
			'customer_code'=>$customer_data->code,
			'customer_id'=>$this->customer->id,
			'name'=>$data->name,
			'furigana'=>$data->furigana,
			'zipcode'=>$data->zipcode1 . $data->zipcode2,
			'address1'=>$data->address1,
			'address2'=>$data->address2,
			'tel'=>$data->tel,
			'create_datetime'=>date('Y-m-d H:i:s'),
		);		
		$id = $this->Address->save($db_data);
		$this->session->set_userdata('destination',$id);
		$this->session->unset_userdata('add_address');
		if(!empty($id))
		{
			$this->session->unset_userdata('address');
		}
		return redirect('front_order/delivery_info');
	}	
	
	public function check_address()
	{	
		$this->session->unset_userdata('destination');
		$address_id = '';
		if($this->uri->segment(3) === FALSE){
			return redirect('addresses/select_address');
		}else{
			$address_id = $this->uri->segment(3);
		}
		if(!is_numeric($address_id)){
			return redirect('addresses/select_address');
		}
		if($address_id != 0){
			$this->session->set_userdata('destination',$address_id);
		}
		return redirect('front_order/delivery_info');
	}
		
}
