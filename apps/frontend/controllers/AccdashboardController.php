<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\UserBankInfo as UserBankInfo;
use Biz_mela\Models\OrderMaster as OrderMaster;
use Biz_mela\Models\OrderUserBillingInfo as OrderUserBillingInfo;
use Biz_mela\Models\OrderUserShippinInfo as OrderUserShippinInfo;

use Phalcon\Tag as Tag,
	Phalcon\Forms\Form,
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

        $this->view->setVars(array('title' => 'Account Dashboard'));
        
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
		$User=UserBankInfo::findFirst("user_id="."'".$user_id."'");
		$record['value']=$User;
		$this->view->setVar('record',$record);

		$bill=OrderMaster::findFirst("user_id="."'".$user_id."'");

		$master=$bill->id;

		$bill_addr=OrderUserBillingInfo::findFirst("id="."'".$master."'");
		$address['value']=$bill_addr;

		$this->view->setVar('address',$address);

		$ship_addr=OrderUserShippinInfo::findFirst("id="."'".$master."'");
		$shipment['value']=$ship_addr;
		$this->view->setVar('shipment',$shipment);

		
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
		$bill_addr=OrderUserBillingInfo::findFirst("id="."'".$master."'");

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
		$ship_addr=OrderUserShippinInfo::findFirst("id="."'".$master."'");

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
	
	
	
	
	
	
}