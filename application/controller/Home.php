<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home controller - realete with the main page
 *
 * This file is main controll of main page LadyFirstproject 
 * @author Suphanut Thanyaboon <suphanut@gmail.com>
 * @version 0.0.1
 *
 */

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
	/**
 	 * index page show on default
     */
	function index() {
		
			$this->load->model('category/m_category');
			$this->load->model('shop/m_media','m_media');
			$this->load->model('shop/m_shop','shop');
			
			
			$query = $this->m_category->get_category();
			$gallery = $this->shop->gallery();
			$media_ads = $this->m_media->media_ads();
			$media = $this->m_media->media();
			
			
			//print_r ($tag); die;	
			$data['category'] = $query;
			$data['gallery_shop'] = $gallery;
			$data['media_ads'] = $media_ads;
			$data['limit_media'] = $media;
			
			$this->load->view('temp/header',$data);
			$this->load->view('temp/home',$data);
			$this->load->view('temp/footer');
			//$this->load->view('footer');	
			
		
	}
	
	
    /**
     * test jquery scrolling content
     *
     */
	
    public function fetch_products() {

        $data = array();

		$this->load->model('shop/m_shop','m_shop');
		$data['prods'] = $this->m_shop->fetch_product(0,4);

		$this->load->view('temp/productaj',$data);
    
    }

    /***
     * regist() - used for member registration get data from the registration form and save to database
     *
     */
    
    public function register() {

        //do_dump($_POST,'POST');
			if ($this->input->post('bts') != 0){
				$area=$this->input->post('bts');
			} else {
				$area=$this->input->post('mrt');	
			}
						
        // prepare data
        $data = array( 
            'photo' => '',
            'username' => $this->input->post('username')?$this->input->post('username'):'',
            'password' => $this->input->post('password')?$this->input->post('username'):'',
            'fammilly_name' => $this->input->post('fname'),
            'lastname' => $this->input->post('lname'),
            'email' => $this->input->post('email'),
            'sex' => $this->input->post('sex'),
            'birthday' => $this->input->post('birthday'),
            'occupation' => $this->input->post('occupation'),
            'address' => $this->input->post('address'),
            'social_id' => $this->input->post('socialid'),
            'socialtype' => $this->input->post('socialtype'),
            'fb_photo' => $this->input->post('facebookpicture'),
            'is_premium' => 0,
            'transportation_id' => $area,
            'has_children' => $this->input->post('has_children'),
            'children_status' => $this->input->post('children_status'),
            'to_thai' => $this->input->post('to_thai'),
        );
		//do_dump ($data); die;

        // load model for member management
        $this->load->model('T_member'); 

        //insert member
        $this->T_member->regist($data);
        $user_id = $this->db->insert_id();
		
		// Upload Photo
			$config['upload_path'] = 'img_user/'; 			
			$config['allowed_types'] = 'gif|jpeg|jpg|png'; 
			$config['max_size']	= '4096';	
			$config['max_width']  = '2272';
			$config['max_height']  = '1704';
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			if($this->upload->do_upload("picture")){
				
				$data=$this->upload->data();
				$query = $this->T_member->insert_image($user_id,$data['file_name']);
				//do_dump($data);
			
			} else {

				echo $this->upload->display_errors();
			} 
		// End Upload image

        // go login success
        redirect ('/c_login/login_access/'.$user_id.'');
    }

	
	
	public function mypage()
	{

		$this->load->model('category/m_category');
		
        $query = $this->m_category->get_category();
		
		//do_dump ($gallery);die;
		$data['category'] = $query;
		
		$this->load->view('temp/header');
		$this->load->view('shop/my_page',$data);	
		$this->load->view('temp/footer');
	}
	
}

