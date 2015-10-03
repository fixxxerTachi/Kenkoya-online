<?php
include __DIR__.'/../libraries/define_config.php';
include __DIR__.'/../libraries/define.php';
include __DIR__.'/../libraries/common.php';
include __DIR__.'/../libraries/sendmail.php';

class Admin_contents extends CI_Controller{
	public $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation'));
		$this->load->helper('form');
		$this->load->model('Mainvisual');
		$this->load->model('Mail_template');
		$this->load->model('Master_mail_reciever');
		$this->load->model('Master_hour');
		$this->load->model('Information');
		$this->load->model('Question_category');
		$this->load->model('Contents_question');
		$this->load->model('Advertise');
		$this->load->model('Recommend');
		$this->load->model('Top10');
		$this->load->model('Master_show_flag');
		$this->data['current'] = $this->router->class;
		$this->data['current_side'] = $this->router->method;
		$this->load->model('Admin_login');
		$this->data['user'] = $this->Admin_login->check_login();
		$this->load->model('Admin_urls');
		if(!empty($this->data['user'])){
			$this->Admin_urls->get_user_site_menu($this->data['user']->id,$this);
			$this->Admin_urls->is_access_url($this->data['user']->id,$this);
		}
	}
	
	public function index()
	{
		$this->data['h2title'] = 'メニューを選択';
		$this->load->view('index_admin',$this->data);
	}
	public function add_mainvisual()
	{
		$this->data['result'] = $this->Mainvisual->show_list();
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['h2title'] = 'メインビジュアル登録';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$form_data = $this->Mainvisual;
		$this->data['form_data'] = $form_data;
		$image_path = MAINVISUAL_IMAGE_PATH;
		if($this->input->post('submit')){
			if(!$this->input->post('end_date')){
				$end_date = '9999-12-31';
			}else{
				$end_date = $this->input->post('end_date');
			}
			$form_data=array(
				'image_name'=>$this->input->post('image_name'),
				'description'=>$this->input->post('description'),
				'thumb_image_name'=>$this->input->post('thumb_image_name'),
				'thumb_image_description'=>$this->input->post('thumb_image_description'),
				'url'=>$this->input->post('url'),
				'inside_url'=>$this->input->post('inside_url'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$end_date . ' ' . $this->input->post('end_time'),
				'create_date'=>date('Y-m-d H:i:s'),
				'sort_order'=>$this->Mainvisual->sort_order,
			);
			if(!empty($form_data['image_name']) && !$this->extentioncheck($form_data['image_name']) && !empty($form_data['thumb_image_name']) && !$this->extentioncheck($form_data['thumb_image_name'])){
				$this->data['error_message'] = 'jpgもしくはpng形式の画像名を入力してください';
				$this->data['form_data'] = (object)$form_data;
			}else{
				//メインビジュアル画像処理
				if(!empty($_FILES['image']['name'])){
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
							if(!empty($form_data['image_name'])){
								rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
							}else{
								$form_data['image_name'] = $_FILES['image']['name'];
							}
							//$result = $this->Mainvisual->save($form_data);	/* サムネイル画像登録時にsave*/
							//$this->session->set_flashdata('success','登録しました');
							//redirect(base_url('/admin_contents/add_mainvisual'));
							//サムネイル画像処理
							if(!empty($_FILES['thumb_image']['name'])){
								if(is_uploaded_file($_FILES['thumb_image']['tmp_name'])){
									if(move_uploaded_file($_FILES['thumb_image']['tmp_name'],$image_path.$_FILES['thumb_image']['name'])){
										if(!empty($form_data['thumb_image_name'])){
											rename($image_path.$_FILES['thumb_image']['name'],$image_path.$form_data['thumb_image_name']);
										}else{
											$form_data['thumb_image_name'] = $_FILES['thumb_image']['name'];
										}
										$result = $this->Mainvisual->save($form_data);
										$this->session->set_flashdata('success','登録しました');
										redirect(base_url('/admin_contents/add_mainvisual'));
									}else{
										$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
										unlink($_FILES['thumb_image']['tmp_name']);
									}
								}else{
									$this->data['error_message'] = 'thubmファイルのアップロードに失敗しました';
								}
							}else{
								$this->data['error_message'] = 'ファイルが選択されていません';
								$this->data['form_data'] = (object)$form_data;
							}
						}else{
							$this->data['error_message'] = 'mainファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
						}
					}else{
						$this->data['error_message'] = 'mainファイルのtmpアップロードに失敗しました';
					}
				}else{
					$this->data['error_message'] = 'ファイルが選択されていません';
					$this->data['form_data'] = (object)$form_data;
				}
			}
		}
		if($this->input->post('change_order')){
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data = array('sort_order'=>$sort_num);
				$this->Mainvisual->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			redirect(base_url('/admin_contents/add_mainvisual'));
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/add_mainvisual',$this->data);
	}

	public function edit_mainvisual()
	{
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['h2title'] = 'メインビジュアル変更';
		$id = $this->uri->segment(3);
		$form_data = $this->Mainvisual->get_by_id($id);
		$this->data['form_data'] = $form_data[0];
		$image_path = MAINVISUAL_IMAGE_PATH;
		$old_image = $image_path . $this->data['form_data']->image_name;
		$thumb_old_image = $image_path . $this->data['form_data']->thumb_image_name;
		if($this->input->post('submit')){
			if(!$this->input->post('end_date')){
				$end_date = '9999-12-31';
			}else{
				$end_date = $this->input->post('end_date');
			}
			$form_data=array(
				'image_name'=>$this->input->post('image_name'),
				'url'=>$this->input->post('url'),
				'inside_url'=>$this->input->post('inside_url'),
				'description'=>$this->input->post('description'),
				'thumb_image_name'=>$this->input->post('thumb_image_name'),
				'thumb_image_description'=>$this->input->post('thumb_image_description'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$end_date . ' ' . $this->input->post('end_time'),
			);
			if(!empty($form_data['image_name']) && !$this->extentioncheck($form_data['image_name'])&& !empty($form_data['thumb_image_name']) && !$this->extentioncheck($form_data['thumb_image_name'])){
				$this->data['error_message'] = 'jpgもしくはpng形式の画像名を入力してください';
			}else{
				if(!empty($_FILES['image']['name'])){
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
							if(!empty($form_data['image_name'])){
								rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
							}else{
								$form_data['image_name'] = $_FILES['image']['name'];
							}
							//前の画像名と登録画像名が異なる場合古い画像を削除
							if($old_image != $image_path . $form_data['image_name']){
								unlink($old_image);
							}
							$result = $this->Mainvisual->update($id,$form_data);
							$this->session->set_flashdata('success','登録しました');
							//redirect(base_url('/admin_contents/add_mainvisual'));
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					//画像がアップロードされない場合画像名変更
					rename($old_image,$image_path . $form_data['image_name']);
					$result = $this->Mainvisual->update($id,$form_data);
					$this->session->set_flashdata('success','登録しました');
					//redirect(base_url('/admin_contents/add_mainvisual'));
				}
				
				////サムネイル
				if(!empty($_FILES['thumb_image']['name'])){
					if(is_uploaded_file($_FILES['thumb_image']['tmp_name'])){
						if(move_uploaded_file($_FILES['thumb_image']['tmp_name'],$image_path.$_FILES['thumb_image']['name'])){
							if(!empty($form_data['thumb_image_name'])){
								rename($image_path.$_FILES['thumb_image']['name'],$image_path.$form_data['thumb_image_name']);
							}else{
								$form_data['thumb_image_name'] = $_FILES['thumb_image']['name'];
							}
							//前の画像名と登録画像名が異なる場合古い画像を削除
							if($thumb_old_image != $image_path . $form_data['thumb_image_name']){
								unlink($thumb_old_image);
							}
							$result = $this->Mainvisual->update($id,$form_data);
							$this->session->set_flashdata('success','登録しました');
							//redirect(base_url('/admin_contents/add_mainvisual'));
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['thumb_image']['tmp_name']);
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					//画像がアップロードされない場合画像名変更
					rename($thumb_old_image,$image_path . $form_data['thumb_image_name']);
					$result = $this->Mainvisual->update($id,$form_data);
					$this->session->set_flashdata('success','登録しました');
					//redirect(base_url('/admin_contents/add_mainvisual'));
				}
			}
			if(empty($this->data['error_message'] )){ return redirect(base_url('admin_contents/add_mainvisual')); }
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/edit_mainvisual',$this->data);	
	}
	
	public function delete_mainvisual()
	{
		$id = $this->uri->segment(3);
		$this->Mainvisual->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_contents/add_mainvisual'));
	}
	
	private function extentioncheck($data='')
	{
		$check = substr($data,strpos($data,'.')+1);
		if(!in_array($check,array('jpg','jpeg','png'))){
			return false;
		}
		return true;
	}
	
	public function mail_test()
	{
		$this->data['h2title'] = 'メール送信テスト';
		$this->data['message'] = 'メールアドレスを入力して下さい';
		if(isset($_POST['submit'])){
			$mail_address=$_POST['mail_address'];
			$result=sendMail($mail_address,'こんにちわ','こんにちわ内容です','info@akatome.co.jp','宅配スーパー健康屋');
			if($result){
				$this->session->set_flashdata('success','メールを送信しました');
				redirect(base_url('/admin_contents/mail_test'));
				$this->data['error_message'] = 'メール送信に失敗しました';
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/admin_mail_test.php',$this->data);
	}
	
	public function list_mail_template()
	{
		$this->data['h2title'] = 'メールテンプレート管理';
		/*** メール一覧の処理 ***/
		$this->data['result'] = $this->Mail_template->show_list();
		$show_detail = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		if($show_detail){
			$detail_result = $this->Mail_template->get_by_id($id);
			$this->data['show_detail'] = $show_detail;
			$this->data['detail_result'] = $detail_result;
		}
		/*** メール追加の処理 ***/
		$form_data= (object)array(
			'template_name'=>'',
			'for_customer'=>'',
			'mail_title'=>'',
			'mail_body'=>'',
		);
		if($this->input->post('submit')){
			$template_name = $this->input->post('template_name');
			$for_customer = $this->input->post('for_customer');
			$mail_title = $this->input->post('mail_title');
			$mail_body = $this->input->post('mail_body');
			$this->form_validation->set_rules('template_name','表示名','required');
			$this->form_validation->set_rules('mail_title','件名','required');
			$this->form_validation->set_rules('mail_body','メール本文','required');
			$this->form_validation->set_message('required','%sが未入力です');	
			if($this->form_validation->run() == FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$form_data->template_name = $this->input->post('template_name');
				$form_data->for_customer = $this->input->post('for_customer');
				$form_data->mail_title = $this->input->post('mail_title');
				$form_data->mail_body = $this->input->post('mail_body');
			}else{
				$data = array(
					'template_name'=>$template_name,
					'for_customer'=>$for_customer,
					'mail_title'=>$mail_title,
					'mail_body'=>$mail_body,
				);
				$result=$this->Mail_template->save($data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('admin_contents/list_mail_template'));
			}
		}
		$this->data['reciever'] = $this->Master_mail_reciever->reciever;
		$this->data['form_data'] = $form_data;
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/admin_mail_template.php',$this->data);
	}
	
	public function edit_mail_template()
	{
		$this->data['h2title'] = 'メールテンプレートの変更';
		$this->data['message'] = '内容を修正して登録ボタンを押してください';
		$id = $this->uri->segment(3);
		$result= $this->Mail_template->get_by_id($id);
		$this->data['form_data'] = $result;
		$this->data['reciever'] = $this->Master_mail_reciever->reciever;
		if($this->input->post('submit')){
			$template_name = $this->input->post('template_name');
			$for_customer = $this->input->post('for_customer');
			$mail_title = $this->input->post('mail_title');
			$mail_body = $this->input->post('mail_body');
		//add_validateion
			if(empty($template_name)){
				$result->template_name = $template_name;
				$result->for_customer = $for_customer;
				$result->mail_title = $mail_title;
				$result->mail_body = $mail_body;
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$data=array(
					'template_name'=>$template_name,
					'for_customer'=>$for_customer,
					'mail_title'=>$mail_title,
					'mail_body'=>$mail_body,
				);
				$result = $this->Mail_template->update($id,$data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('/admin_contents/list_mail_template'));
			}
		}
		$this->load->view('admin_contents/admin_add_mail_template',$this->data);
	}
	/*
	public function add_mail_template()
	{
		$this->data['h2title'] = 'メールテンプレートの追加';
		$form_data= (object)array(
			'template_name'=>'',
			'for_customer'=>'',
			'mail_title'=>'',
			'mail_body'=>'',
		);
		$this->data['form_data'] = $form_data;
		$this->data['reciever'] = $this->Master_mail_reciever->reciever;
		if($this->input->post('submit')){
			$template_name = $this->input->post('template_name');
			$for_customer = $this->input->post('for_customer');
			$mail_title = $this->input->post('mail_title');
			$mail_body = $this->input->post('mail_body');
		//add validation
		
			$this->form_validation->set_rules('template_name','表示名','required');
			$this->form_validation->set_rules('mail_title','件名','required');
			$this->form_validation->set_rules('mail_body','メール本文','required');
			$this->form_validation->set_message('required','%sが未入力です');	
			if($this->form_validation->run() == FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$form_data->template_name = $this->input->post('template_name');
				$form_data->for_customer = $this->input->post('for_customer');
				$form_data->mail_title = $this->input->post('mail_title');
				$form_data->mail_body = $this->input->post('mail_body');
			*/
			/*
				$form_data = (object)array(
					'template_name'=>$this->input->post('template_name'),
					'for_customer'=>$this->input->post('for_customer'),
					'mail_title'=>$this->input->post('mail_template'),
					'mail_body'=>$this->input->post('mail_body'),
				);
				$this->data['form_data'] = $form_data;
			*/
		/*
			}else{
				$data = array(
					'template_name'=>$template_name,
					'for_customer'=>$for_customer,
					'mail_title'=>$mail_title,
					'mail_body'=>$mail_body,
				);
				$result=$this->Mail_template->save($data);
				$this->session->set_flashdata('success','登録しました');
				redirect(base_url('/admin_contents/list_mail_template'));
			}
		}
		$this->data['result'] = $this->Mail_template->show_list();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/admin_add_mail_template.php',$this->data);		
	}
	*/
	public function delete_mail_template()
	{
		$id = $this->uri->segment(3);
		$this->Mail_template->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_contents/list_mail_template'));
	}
	
	/*
	public function add_information()
	{
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['h2title'] = 'お知らせ登録';
		$form_data = $this->Information;
		$this->data['form_data'] = $form_data;
		$image_path = INFORMATION_IMAGE_PATH;
		if($this->input->post('submit')){
			$form_data=array(
				'title'=>$this->input->post('title'),
				'content'=>$this->input->post('content'),
				'url'=>$this->input->post('url'),
				'image_name'=>$this->input->post('image_name'),
				'image_description'=>$this->input->post('image_description'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$this->input->post('end_date') . ' ' . $this->input->post('end_time'),
				'create_date'=>date('Y-m-d H:i:s'),
				'sort_order'=>$this->Information->sort_order,
			);
			if(!empty($form_data['image_name']) && !$this->extentioncheck($form_data['image_name'])){
				$this->data['error_message'] = 'jpgもしくはpng形式の画像名を入力してください';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$this->form_validation->set_rules('title','タイトル','required');
				if($this->form_validation->run() === FALSE){
					$this->data['error_message'] = '未入力項目があります';
					$this->data['form_data'] = (object)$form_data;
				}else{
					if(!empty($_FILES['image']['name'])){
						if(is_uploaded_file($_FILES['image']['tmp_name'])){
							if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
								if(!empty($form_data['image_name'])){
									rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
								}else{
									$form_data['image_name'] = $_FILES['image']['name'];
								}
								$result = $this->Information->save($form_data);
								$this->session->set_flashdata('success','登録しました');
								redirect(base_url('/admin_contents/list_information'));
							}else{
								$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
								unlink($_FILES['image']['tmp_name']);
							}
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						}
					}else{
						$result = $this->Information->save($form_data);
						$this->session->set_flashdata('success','登録しました');
						redirect(base_url('/admin_contents/list_information'));
					}
				}
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/add_information',$this->data);
	}
	*/
	
	public function edit_information()
	{
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['h2title'] = 'おしらせ変更';
		$id = $this->uri->segment(3);
		$form_data = $this->Information->get_by_id($id);
		$this->data['form_data'] = $form_data;
		$image_path = INFORMATION_IMAGE_PATH;
		$old_image = $image_path . $this->data['form_data']->image_name;
		if($this->input->post('submit')){
			$form_data=array(
				'title'=>$this->input->post('title'),
				'content'=>$this->input->post('content'),
				'url'=>$this->input->post('url'),
				'image_name'=>$this->input->post('image_name'),
				'image_description'=>$this->input->post('image_description'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$this->input->post('end_date') . ' ' . $this->input->post('end_time'),
			);
			if(!empty($form_data['image_name']) && !$this->extentioncheck($form_data['image_name'])){
				$this->data['error_message'] = 'jpgもしくはpng形式の画像名を入力してください';
			}else{
				if(!empty($_FILES['image']['name'])){
					if(is_uploaded_file($_FILES['image']['tmp_name'])){
						if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
							if(!empty($form_data['image_name'])){
								rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
							}else{
								$form_data['image_name'] = $_FILES['image']['name'];
							}
							//前の画像名と登録画像名が異なる場合古い画像を削除
							if($old_image != $image_path . $form_data['image_name']){
								unlink($old_image);
							}
							$result = $this->Information->update($id,$form_data);
							$this->session->set_flashdata('success','登録しました');
							redirect(base_url('/admin_contents/list_information'));
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
							unlink($_FILES['image']['tmp_name']);
						}
					}else{
						$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
					}
				}else{
					//画像がアップロードされない場合画像名変更
					rename($old_image,$image_path . $form_data['image_name']);
					$result = $this->Information->update($id,$form_data);
					$this->session->set_flashdata('success','登録しました');
					redirect(base_url('/admin_contents/list_information'));
				}
			}
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/add_information',$this->data);
	}
	
	public function list_information()
	{
		/** お知らせ一覧表示処理 **/
		$this->data['h2title'] = 'お知らせ一覧';
		$this->data['result'] = $this->Information->show_list();
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		/** お知らせ詳細表示処理 **/
		$id = $this->uri->segment(3);
		$this->data['show_detail'] = $this->Information->get_by_id($id);
		/** お知らせ追加処理 **/
		$this->data['hour_list'] = $this->Master_hour->hour;
		$this->data['h2title'] = 'お知らせ登録';
		$form_data = $this->Information;
		$this->data['form_data'] = $form_data;
		$image_path = INFORMATION_IMAGE_PATH;
		if($this->input->post('submit')){
			$form_data=array(
				'title'=>$this->input->post('title'),
				'content'=>$this->input->post('content'),
				'url'=>$this->input->post('url'),
				'image_name'=>$this->input->post('image_name'),
				'image_description'=>$this->input->post('image_description'),
				'start_datetime'=>$this->input->post('start_date') . ' ' . $this->input->post('start_time'),
				'end_datetime'=>$this->input->post('end_date') . ' ' . $this->input->post('end_time'),
				'create_date'=>date('Y-m-d H:i:s'),
				'sort_order'=>$this->Information->sort_order,
			);
			if(!empty($form_data['image_name']) && !$this->extentioncheck($form_data['image_name'])){
				$this->data['error_message'] = 'jpgもしくはpng形式の画像名を入力してください';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$this->form_validation->set_rules('title','タイトル','required');
				if($this->form_validation->run() === FALSE){
					$this->data['error_message'] = '未入力項目があります';
					$this->data['form_data'] = (object)$form_data;
				}else{
					if(!empty($_FILES['image']['name'])){
						if(is_uploaded_file($_FILES['image']['tmp_name'])){
							if(move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$_FILES['image']['name'])){
								if(!empty($form_data['image_name'])){
									rename($image_path.$_FILES['image']['name'],$image_path.$form_data['image_name']);
								}else{
									$form_data['image_name'] = $_FILES['image']['name'];
								}
								$result = $this->Information->save($form_data);
								$this->session->set_flashdata('success','登録しました');
								redirect(base_url('/admin_contents/list_information'));
							}else{
								$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
								unlink($_FILES['image']['tmp_name']);
							}
						}else{
							$this->data['error_message'] = 'ファイルのアップロードに失敗しました';
						}
					}else{
						$result = $this->Information->save($form_data);
						$this->session->set_flashdata('success','登録しました');
						redirect(base_url('/admin_contents/list_information'));
					}
				}
			}
		}

		/** 表示順変更処理 **/
		if($this->input->post('change_order')){
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data = array('sort_order'=>$sort_num);
				$this->Information->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			redirect(base_url('/admin_contents/list_information'));
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/list_information',$this->data);
	}
	
	/*
	public function detail_information()
	{
		$this->data['h2title'] = 'おしらせ詳細';
		$id = $this->uri->segment(3);
		$result = $this->Information->get_by_id($id);
		$this->data['result'] = $result;
		$this->load->view('admin_contents/detail_information',$this->data);
	}
	*/
	
	public function delete_information()
	{
		$id = $this->uri->segment(3);
		$this->Information->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_contents/list_information'));
	}
	
	public function question_category()
	{
		$this->data['h2title'] = 'よくある質問カテゴリの追加';
		$form_data = $this->Question_category;
		$this->data['form_data']  = $form_data;
		$result = $this->Question_category->show_list();
		$this->data['result'] = $result;
		if($this->input->post('submit')){
			$form_data=array(
				'name'=>$this->input->post('name'),
			);
			$this->form_validation->set_rules('name','カテゴリ名','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力です';
			}else{
				$result = $this->Question_category->save_category($form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/question_category');
			}
		}
		$this->data['success_message'] = '登録しました';
		$this->load->view('admin_contents/question_category',$this->data);
	}

	public function edit_question_category()
	{
		$this->data['h2title'] = 'よくある質問かてごり変更';
		$this->data['result'] = $this->Question_category->show_list();
		$id = $this->uri->segment(3);
		$result = $this->Question_category->get_category_by_id($id);
		$this->data['form_data']  = $result[0];
		if($this->input->post('submit')){
			$form_data = array(
				'name'=>$this->input->post('name'),
			);
			$this->form_validation->set_rules('name','カテゴリ名','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message']  = '未入力です';
			}else{
				$result = $this->Question_category->change_category($id,$form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/question_category');
			}
		}
		$this->load->view('admin_contents/question_category' ,$this->data);
	}
	public function add_contents_question()
	{
		$this->data['h2title'] = 'よくあるしつもん登録';
		$this->data['form_data'] = $this->Contents_question;
		$this->data['category_list'] = $this->Question_category->show_list_array();
		if($this->input->post('submit')){
			$form_data = array(
				'question'=>$this->input->post('question'),
				'answer'=>$this->input->post('answer'),
				'category_id'=>$this->input->post('category_id'),
				'create_date'=>date('Y-m-d H:i:s'),
			);
			$this->form_validation->set_rules('answer','質問','required');
			if($this->form_validation->run() === FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data'] = (object)$form_data;
			}else{
				$result = $this->Contents_question->save($form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/list_contents_question');
			}
		}
		$this->load->view('admin_contents/add_contents_question',$this->data);
	}
	
	public function edit_contents_question()
	{
		$this->data['h2title'] = 'よくある質問変更';
		$id = $this->uri->segment(3);
		$result = $this->Contents_question->get_by_id($id);
		$this->data['form_data'] = $result;
		$this->data['category_list'] = $this->Question_category->show_list_array();
		if($this->input->post('submit')){
			$form_data = array(
				'question'=>$this->input->post('question'),
				'answer'=>$this->input->post('answer'),
				'category_id'=>$this->input->post('category_id'),
			);
			$this->form_validation->set_rules('answer','質問','required');
			if($this->form_validation->run() ===FALSE){
				$this->data['error_message'] = '未入力項目があります';
				$this->data['form_data']  = (object)$form_data;
			}else{
				$result = $this->Contents_question->update($id,$form_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/list_contents_question');
			}
		}
		$this->load->view('admin_contents/add_contents_question',$this->data);
	}
	
	public function list_contents_question()
	{
		$this->data['h2title'] = 'よくある質問一覧';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$result = $this->Contents_question->show_list();
		$this->data['result'] = $result;
		$this->data['category_id'] = '';
		$this->data['category_list'] = $this->Question_category->show_list_array();
		if($this->input->post('category_search')){
			$category_id =$this->input->post('category_id');
			$result = $this->Contents_question->show_list_by_category($category_id);
			$this->data['result'] = $result;
			$this->data['category_id'] = $category_id;
		}
		if($this->input->post('change_order')){
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data = array('sort_order'=>$sort_num);
				$this->Contents_question->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			redirect(base_url('/admin_contents/list_contents_question'));
		}
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/list_contents_question',$this->data);
	}
	
	public function detail_contents_question()
	{
		$this->data['h2title'] = 'よくあるしつもん詳細';
		$id =$this->uri->segment(3);
		$result = $this->Contents_question->get_by_id($id);
		$this->data['result'] = $result;
		$this->load->view('admin_contents/detail_contents_question',$this->data);
	}
	public function delete_contents_question()
	{
		$id = $this->uri->segment(3);
		$this->Contents_question->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_contents/list_contents_question'));
	}

	public function add_recommend()
	{
		$this->data['h2title'] = 'おすすめ商品登録';
		$this->data['message'] = '商品コードもしくは対象広告を選んで広告商品コード入力してください';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$this->data['form_data'] = $this->Recommend;
		$this->data['result'] = $this->Recommend->show_list_with_advertise_and_product();
		if($this->input->post('change_order')){
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data = array('sort_order'=>$sort_num);
				$this->Recommend->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			redirect(base_url('/admin_contents/add_recommend'));
		}
		if($this->input->post('submit')){
			$form_data = array(
				'product_code'=>$this->input->post('product_code'),
				'advertise_id'=>$this->input->post('advertise_id'),
				'advertise_product_code'=>$this->input->post('advertise_product_code'),
				'comment'=>$this->input->post('comment'),
				'create_date'=>date('Y-m-d H:i:s'),
				'sort_order'=>$this->Recommend->sort_order
			);
			$form_data = (object)$form_data;
			if(empty($form_data->advertise_product_code) && empty($form_data->advertise_id)){
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$db_data=new StdClass();
//商品マスタからの登録は現在中止
//				if($form_data->product_code){
//					$db_data->product_code = $form_data->product_code;
			//チラシからの登録
//				}elseif($form_data->advertise_id){
//					$db_data->advertise_id  = $form_data->advertise_id;
//					$product_code = $this->Recommend->get_product_code_by_advertise_product_code($form_data->advertise_product_code);
//					$db_data->product_code = $product_code->product_code;
//					$db_data->advertise_product_code = $form_data->advertise_product_code;
//				}
				$db_data->advertise_id = $form_data->advertise_id;
				$db_data->advertise_product_code = $form_data->advertise_product_code;
				$db_data->comment = $form_data->comment;
				$db_data->create_date = $form_data->create_date;
				$db_data->sort_order = $form_data->sort_order;
				$result = $this->Recommend->save($db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/add_recommend');
			}
		}
		$this->data['ad_list'] = $this->Advertise->show_list_arr();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/add_recommend',$this->data);
	}
	
	public function edit_recommend()
	{
		$this->data['h2title'] = 'おすすめ商品情報変更';
		$this->data['message'] = '商品コードもしくは対象広告を選んで広告商品コード入力してください';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$id = $this->uri->segment(3);
		$form_data = $this->Recommend->get_by_id($id);
		$this->data['form_data'] = $form_data[0];
		$this->data['result'] = $this->Recommend->show_list_with_advertise_and_product();
		if($this->input->post('submit')){
			$form_data = array(
				'product_code'=>$this->input->post('product_code'),
				'advertise_id'=>$this->input->post('advertise_id'),
				'advertise_product_code'=>$this->input->post('advertise_product_code'),
				'comment'=>$this->input->post('comment'),
			);
			$form_data = (object)$form_data;
			if(empty($form_data->product_code) && empty($form_data->advertise_id)){
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$db_data=new StdClass();
				if($form_data->product_code){
					$db_data->product_code = $form_data->product_code;
				}elseif($form_data->advertise_id){
					$db_data->advertise_id  = $form_data->advertise_id;
					$db_data->advertise_product_code = $form_data->advertise_product_code;
				}
				$db_data->comment = $form_data->comment;
				$db_data->create_date = $form_data->create_date;
				$result = $this->Recommend->update($id,$db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/add_recommend');
			}
		}
		$this->data['ad_list'] = $this->Advertise->show_list_arr();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/add_recommend',$this->data);
	}
	
	public function detail_recommend()
	{
		$this->data['h2title'] = 'おすすめ商品情報詳細';
		$id = $this->uri->segment(3);
		$form_data = $this->Recommend->show_list_with_advertise_and_product_by_id($id);
		$this->data['form_data'] = $form_data[0];
		$this->data['result'] = $this->Recommend->show_list_with_advertise_and_product();
		$this->data['ad_list'] = $this->Advertise->show_list_arr();
		$this->load->view('admin_contents/detail_recommend',$this->data);
	}
	
	public function delete_recommend()
	{
		$id = $this->uri->segment(3);
		$this->Recommend->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_contents/add_recommend'));
	}
	
	public function add_top10()
	{
		$this->data['h2title'] = '売れ筋商品登録';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$this->data['message'] = '対象広告を選んで広告商品コード入力してください';
		$this->data['form_data'] = $this->Top10;
		$this->data['result'] = $this->Top10->show_list_with_advertise_and_product();
		if($this->input->post('change_order')){
			foreach($this->data['result'] as $obj){
				$sort_num = $this->input->post("sort_order{$obj->id}");
				$db_data = array('sort_order'=>$sort_num);
				$this->Top10->update($obj->id,$db_data);
			}
			$this->session->set_flashdata('success','表示順を変更しました');
			return redirect(base_url('/admin_contents/add_top10'));
		}
		if($this->input->post('submit')){
			$form_data = array(
				'product_code'=>$this->input->post('product_code'),
				'advertise_id'=>$this->input->post('advertise_id'),
				'advertise_product_code'=>$this->input->post('advertise_product_code'),
				'comment'=>$this->input->post('comment'),
				'create_date'=>date('Y-m-d H:i:s'),
				'sort_order'=>$this->Top10->sort_order,
			);
			$form_data = (object)$form_data;
			if(empty($form_data->advertise_product_code) && empty($form_data->advertise_id)){
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$db_data=new StdClass();
/*商品マスタからの登録は現在中止
				if($form_data->product_code){
					$db_data->product_code = $form_data->product_code;
			//チラシからの登録
				}elseif($form_data->advertise_id){
					$db_data->advertise_id  = $form_data->advertise_id;
					$product_code = $this->Top10->get_product_code_by_advertise_product_code($form_data->advertise_product_code);
					$db_data->product_code = $product_code->product_code;
					$db_data->advertise_product_code = $form_data->advertise_product_code;
				}
*/
				$db_data->advertise_id = $form_data->advertise_id;
				$db_data->advertise_product_code = $form_data->advertise_product_code;
				$db_data->comment = $form_data->comment;
				$db_data->create_date = $form_data->create_date;
				$db_data->sort_order = $form_data->sort_order;
				$result = $this->Top10->save($db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/add_top10');
			}
		}
		$this->data['ad_list'] = $this->Advertise->show_list_arr();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/add_top10',$this->data);
	}

	public function edit_top10()
	{
		$this->data['h2title'] = '売れ筋商品情報変更';
		$this->data['message'] = '商品コードもしくは対象広告を選んで広告商品コード入力してください';
		$this->data['show_flag'] = $this->Master_show_flag->show_flag;
		$id = $this->uri->segment(3);
		$form_data = $this->Top10->get_by_id($id);
		$this->data['form_data'] = $form_data[0];
		$this->data['result'] = $this->Top10->show_list_with_advertise_and_product();
		if($this->input->post('submit')){
			$form_data = array(
				'product_code'=>$this->input->post('product_code'),
				'advertise_id'=>$this->input->post('advertise_id'),
				'advertise_product_code'=>$this->input->post('advertise_product_code'),
				'comment'=>$this->input->post('comment'),
			);
			$form_data = (object)$form_data;
			if(empty($form_data->product_code) && empty($form_data->advertise_id)){
				$this->data['error_message'] = '未入力項目があります';
			}else{
				$db_data=new StdClass();
				if($form_data->product_code){
					$db_data->product_code = $form_data->product_code;
				}elseif($form_data->advertise_id){
					$db_data->advertise_id  = $form_data->advertise_id;
					$db_data->advertise_product_code = $form_data->advertise_product_code;
				}
				$db_data->comment = $form_data->comment;
				$db_data->create_date = $form_data->create_date;
				$result = $this->Top10->update($id,$db_data);
				$this->session->set_flashdata('success','登録しました');
				redirect('admin_contents/add_top10');
			}
		}
		$this->data['ad_list'] = $this->Advertise->show_list_arr();
		$this->data['success_message'] = $this->session->flashdata('success');
		$this->load->view('admin_contents/add_top10',$this->data);
	}
	
	public function detail_top10()
	{
		$this->data['h2title'] = 'おすすめ商品情報詳細';
		$id = $this->uri->segment(3);
		$form_data = $this->Top10->show_list_with_advertise_and_product_by_id($id);
		$this->data['form_data'] = $form_data[0];
		$this->data['result'] = $this->Top10->show_list_with_advertise_and_product();
		$this->data['ad_list'] = $this->Advertise->show_list_arr();
		$this->load->view('admin_contents/detail_top10',$this->data);
	}
	
	public function delete_top10()
	{
		$id = $this->uri->segment(3);
		$this->Top10->delete($id);
		$this->session->set_flashdata('success','削除しました');
		redirect(base_url('/admin_contents/add_top10'));
	}
	
	public function change_show_flag_mainvisual()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Mainvisual->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_contents/add_mainvisual');
	}
	public function change_show_flag_information()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Information->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_contents/list_information');
	}
	public function change_show_flag_question()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Contents_question->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_contents/list_contents_question');
	}

	public function change_show_flag_top10()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Top10->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_contents/add_top10');
	}

	public function change_show_flag_recommend()
	{
		$str = $this->uri->segment(3);
		$id = substr($str,strpos($str,'_')+1);
		$data = $this->uri->segment(4);
		$db_data = array('show_flag'=> $data);
		$result  = $this->Recommend->update($id,$db_data);
		$this->session->set_flashdata('success','変更しました');
		redirect('admin_contents/add_recommend');
	}

}