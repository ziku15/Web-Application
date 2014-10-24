<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\UserBankInfo as UserBankInfo;
use Biz_mela\Models\OrderMaster as OrderMaster;
use Biz_mela\Models\OrderUserBillingInfo as OrderUserBillingInfo;
use Biz_mela\Models\OrderUserShippinInfo as OrderUserShippinInfo;
use Biz_mela\Models\Newsletter as Newsletter;


use Phalcon\Tag as Tag,
	Phalcon\Forms\Form,
	Phalcon\Forms\Element\Password,
	Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength,
	Phalcon\Forms\Element\Text;

class AccdashboardController extends ControllerBase
{
    public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'MY DASHBOARD'));
        
        //Tag::setTitle('Account Information');
        //parent::initialize();
    }

    public function indexAction()
    {
		
	}
	
	
	
	
	public function dashinfoAction(	) {
		$username = $this->session->get('auth');
		
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
		$data['value']=$con;
		$this->view->setVar('data',$data);
		$user_id=$con->id;
		$user_email=$con->email;
		$User=UserBankInfo::findFirst("user_id="."'".$user_id."'");
		$record['value']=$User;
		$this->view->setVar('record',$record);

		$bill=OrderMaster::findFirst("user_id="."'".$user_id."'");

		$master=$bill->id;

		$bill_addr=OrderUserBillingInfo::findFirst("order_master_id="."'".$master."'");
		$address['value']=$bill_addr;

		$this->view->setVar('address',$address);

		$ship_addr=OrderUserShippinInfo::findFirst("order_master_id="."'".$master."'");
		$shipment['value']=$ship_addr;
		$this->view->setVar('shipment',$shipment);

		$news=Newsletter::findFirst("email="."'".$user_email."'");
		$type=$news->shop_id;
		$msg="";
		if($type!=Null){
			$msg="Subscribed to Full Subscription";
		}
		$subs['value']=$msg;
		$this->view->setVar('subs',$subs);


		
    }
	
	
	public function editAction(	){
	
		$username = $this->session->get('auth');
        $User=UserMaster::findFirst("username="."'".$username['name']."'");
		$form = new Form();
		$username = new Text("username", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'username',
            'value' => $User->username,
            'autocomplete' => 'off'
        ));
		$email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'email',
            'value' => $User->email,
            'autocomplete' => 'off'
        ));
		$contactno = new Text("contactno", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'contactno',
            'value' => $User->contact_no,
            'autocomplete' => 'off'
        ));
		$dob = new Text("dob", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'dob',
            'value' => $User->dob,
            'autocomplete' => 'off'
        ));
	
		$form->add($username);
		$form->add($email);
		$form->add($contactno);
		$form->add($dob);
		
		$data['form'] = $form;
        $this->view->setVars($data);
		
		if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('accdashboard/dashinfo/');
            }
		
			$username = $this->request->getPost('username');
			$email = $this->request->getPost('email');
			$contactno = $this->request->getPost('contactno');
			$dob = $this->request->getPost('dob');
			//$accountno = $this->request->getPost('accountno');
		
			$User->username = $username;
			$User->email = $email;
			$User->contact_no = $contactno;
			//$User->account_holder_name = $accountholder;
			$User->dob = $dob;
			
			if ($User->save()) {
						$this->flash->success("Admin  updated successfully!!");
						$this->response->redirect('sell/index/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
	}
		
	
	
	}



	public function editaddressAction()
	{


		$username = $this->session->get('auth');
		$con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$this->view->setVar('data',$data);
		$user_id=$con->id;
		$bill=OrderMaster::findFirst("user_id="."'".$user_id."'");
		$master=$bill->id;
		$bill_addr=OrderUserBillingInfo::findFirst("order_master_id="."'".$master."'");

		$form = new Form();

		$address = new Text("address", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'address',
            'value' => $bill_addr->billing_address,
            'autocomplete' => 'off'
        ));
		$country = new Text("country", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'country',
            'value' => $bill_addr->country,
            'autocomplete' => 'off'
        ));
		$zipcode = new Text("zipcode", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'zipcode',
            'value' => $bill_addr->zip_code,
            'autocomplete' => 'off'
        ));


		$form->add($address);
		$form->add($country);
		$form->add($zipcode);
		//$form->add($dob);
		
		$data['form'] = $form;
        $this->view->setVars($data);


        if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('accdashboard/dashinfo/');
            }
		
			$address = $this->request->getPost('address');
			$country = $this->request->getPost('country');
			$zipcode = $this->request->getPost('zipcode');
			//$dob = $this->request->getPost('dob');
			//$accountno = $this->request->getPost('accountno');
		
			$bill_addr->billing_address = $address;
			$bill_addr->country = $country;
			$bill_addr->zip_code = $zipcode;
			//$User->account_holder_name = $accountholder;
			//$User->dob = $dob;
			
			if ($bill_addr->save()) {
						$this->flash->success("Adress  updated successfully!!");
						$this->response->redirect('accdashboard/dashinfo/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
			}




	}


	public function editshipmentAction()
	{


		$username = $this->session->get('auth');
		$con=UserMaster::findFirst("username="."'".$username['name']."'");
		
		$user_id=$con->id;
		$bill=OrderMaster::findFirst("user_id="."'".$user_id."'");
		$master=$bill->id;
		$ship_addr=OrderUserShippinInfo::findFirst("order_master_id="."'".$master."'");

		$form = new Form();

		$address = new Text("address", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'address',
            'value' => $ship_addr->shipping_address,
            'autocomplete' => 'off'
        ));
		$country = new Text("country", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'country',
            'value' => $ship_addr->country,
            'autocomplete' => 'off'
        ));
		$zipcode = new Text("zipcode", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'zipcode',
            'value' => $ship_addr->zip_code,
            'autocomplete' => 'off'
        ));

		$contactno = new Text("contactno", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'contactno',
            'value' => $ship_addr->contact_no,
            'autocomplete' => 'off'
        ));

		$form->add($address);
		$form->add($country);
		$form->add($zipcode);
		$form->add($contactno);
		
		$data['form'] = $form;
        $this->view->setVars($data);


        if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('accdashboard/dashinfo/');
            }
		
			$address = $this->request->getPost('address');
			$country = $this->request->getPost('country');
			$zipcode = $this->request->getPost('zipcode');
			$contactno = $this->request->getPost('contactno');
			//$dob = $this->request->getPost('dob');
			//$accountno = $this->request->getPost('accountno');
		
			$ship_addr->shipping_address = $address;
			$ship_addr->country = $country;
			$ship_addr->zip_code = $zipcode;
			$ship_addr->contact_no = $contactno;
			//$User->account_holder_name = $accountholder;
			//$User->dob = $dob;
			
			if ($ship_addr->save()) {
						$this->flash->success("Adress  updated successfully!!");
						$this->response->redirect('accdashboard/dashinfo/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
			}




	}

	public function editsubscriptionAction()
	{

		$username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
        $user_email=$con->email;
        $news=Newsletter::findFirst("email="."'".$user_email."'");

        $form = new Form();
        $email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'email',
            'value' => $news->email,
            'autocomplete' => 'off'
        ));

        $form->add($email);
        $data['form'] = $form;
        $this->view->setVars($data);

        if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('accdashboard/dashinfo/');
            }
		
			
			
			$email = $this->request->getPost('email');
			//$dob = $this->request->getPost('dob');
			//$accountno = $this->request->getPost('accountno');
		
			
			$news->email = $email;
			//$User->account_holder_name = $accountholder;
			//$User->dob = $dob;
			
			if ($news->save()) {
						$this->flash->success("Email  updated successfully!!");
						$this->response->redirect('accdashboard/dashinfo/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
			}








	}

	public function changepasswordAction()
	{



    	$form = new Form();

        $current_password = new Password("current_pass", array(
                'class' => 'form-control input-lg form-element',
                'id' => 'current_pass',
                'placeholder' => 'current password'
            ));
        $current_password->addValidator(new PresenceOf(array(
            'message' => 'The current password field is required'
        )));


        $new_password = new Password("new_pass", array(
                'class' => 'form-control input-lg form-element',
                'id' => 'new_pass',
                'placeholder' => 'new password'
            ));
        $new_password->addValidator(new PresenceOf(array(
            'message' => 'The new password field is required'
        )));

        $confirm_password = new Password("confirm_pass", array(
                'class' => 'form-control input-lg form-element',
                'id' => 'confirm_pass',
                'placeholder' => 'confirm password'
            ));
        $confirm_password->addValidator(new PresenceOf(array(
            'message' => 'The confirm password field is required'
        )));
		
		
		
		$new_password->addValidator(new StringLength(array(
			  'max'=>50,
			  'min' => 8,
			  'messageMaximum' => '',
			  'messageMinimum' => 'Password should be minimum 8 characters long'
		)));

        $form->add($current_password);
        $form->add($new_password);
        $form->add($confirm_password);

        $data['form'] = $form;
        $this->view->setVars($data);

        if($this->request->isPost()){
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            }else{
                //$id = $this->auth['id'];
                $current_password = $this->request->getPost('current_pass');
                $current_password = sha1($current_password);
				$new_password = $this->request->getPost('new_pass');
				$new_password = sha1($new_password);
                $confirm_password = $this->request->getPost('confirm_pass');
                $confirm_password=sha1($confirm_password);

                // echo $current_password;

               // $user = UserMaster::findFirst("id = '$id' AND status = 1");

                $username = $this->session->get('auth');	
				$user = UserMaster::findFirst("username="."'".$username['name']."'");
				$prev=$user->password;
				//$prev=sha1($prev);
                if ($user != false) {
					

					 if($new_password != $confirm_password){
						$this->flash->error("Confirm password doesn't match !!");
					}

					else if($prev!= $current_password){
						$this->flash->error("Wrong Current Password !!");
					}

					else{
								// $new_password = sha1($new_password);
								
								$user->password = $new_password;
								$user->save();
								$this->flash->success("Password successfully changed !!");
						}
					}
					}

                }else{
                    $this->flash->error("");
                }
                //$data['form'] = $form;
       			// $this->view->setVars($data);

    }
        





}
        

        //$data['form'] = $form;
       // $this->view->setVars($data);
    	
    














	
	
	
	
	
	
	
