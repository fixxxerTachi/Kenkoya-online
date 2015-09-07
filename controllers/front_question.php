<?php
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/define_config.php';
class Front_question extends CI_Controller {
	public $data;
	public function __construct()
	{
		parent::__construct();
		$this->data = new StdClass();
		$this->load->model(array('Contents_question','Question_category','Bread'));
		$this->load->helper(array('form'));
		$this->load->library('session');
		$this->data->categories = $this->Question_category->show_list();
		$this->data->customer = $this->session->userdata('customer');
		$this->data->cart_count = $this->session->userdata('carts') ? count($this->session->userdata('carts')) : 0;
	}
	
	public function index()
	{
		$category_id = $this->uri->segment(3);
		if(empty($category_id)){
			$category_id = null;
		}else{
			if(!is_numeric($category_id)){
				throw new Exception('no numeric');
			}
		}
		$questions = $this->Contents_question->show_list_by_category($category_id,true,true,10);
		$this->data->title = 'よくあるご質問（FAQ）';
		$this->h2title = 'よくあるご質問（FAQ)';
		if(empty($category_id) || empty($questions)){
			$this->data->h3title = 'お問い合わせ一覧 ';
		}else{
			$question = reset($questions);
			$this->data->h3title = "<span class='logo_header'>{$question->short_name}</span>{$question->category_name}";
		}
		$this->data->questions = $questions;
		$bread = $this->Bread;
		$bread->text = $this->data->title;
		$this->data->breads = $this->Bread->create_bread($bread);
		$this->load->view('front_question/index',$this->data);
	}
	
	public function detail()
	{
		$id = $this->uri->segment(3);
		if(empty($id) || !is_numeric($id))
		{
			return redirect('front_question/index');
		}
		$result = $this->Contents_question->get_by_id($id);
		$this->data->result = $result;
		$this->data->title = $result->category;
		//bread
		$bread = $this->Bread;
		$bread->link = base_url('front_question');
		$bread->text = 'よくあるご質問（FAQ）';
		$bread1 = new StdClass();
		$bread1->text = $this->data->title;
		$this->data->breads = $this->Bread->create_bread($bread,$bread1);
		$this->load->view('front_question/detail',$this->data);
	}
}