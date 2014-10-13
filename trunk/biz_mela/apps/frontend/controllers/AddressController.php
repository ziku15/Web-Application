<?php

use Phalcon\Tag as Tag,
	Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text;
	
	
	class AddressController extends ControllerBase
	{
	
		public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'Address Book'));
        
        //Tag::setTitle('Account Information');
        //parent::initialize();
    }

    public function indexAction()
    {
		
	}
	
	public function addressAction() {
	$username = $this->session->get('auth');
	
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
			//$data['value']=$con;
		
		
		//$this->view->setVar('data',$data);
		
		$user_id=$con->id;
		$User=UserBankInfo::findFirst("user_id="."'".$user_id."'");
		$record['value']=$User;
		$this->view->setVar('record',$record);
		
    }
	
	public function editAction(){
	
		
		$username = $this->session->get('auth');
		$con=UserMaster::findFirst("username="."'".$username['name']."'");
		$user_id=$con->id;
		$User=UserBankInfo::findFirst("user_id="."'".$user_id."'");
		
		
	$form = new Form();

        $bankname = new Text("bankname", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'bankname',
            'placeholder' => $User->bank_name,
            'autocomplete' => 'off'
        ));
		$branchname = new Text("branchname", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'bankname',
            'placeholder' => $User->branch_name,
            'autocomplete' => 'off'
        ));
		/*$swiftcode = new Text("swiftcode", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'swiftcode',
            'placeholder' => $User->swift_code,
            'autocomplete' => 'off'
        ));*/
		$accountholder = new Text("accountholder", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'accountholder',
            'placeholder' => $User->account_holder_name,
            'autocomplete' => 'off'
        ));
		$accountno = new Text("accountno", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'accountno',
            'placeholder' => $User->account_no,
            'autocomplete' => 'off'
        ));
		
		$form->add($bankname);
		$form->add($branchname);
		//$form->add($swiftcode);
		$form->add($accountholder);
		$form->add($accountno);
		$data['form'] = $form;
        $this->view->setVars($data);
		
		if ($this->request->isPost()) {
			
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('wallet/wallet/');
            }
		
			$bankname = $this->request->getPost('bankname');
			$branchname = $this->request->getPost('branchname');
			//$swiftcode = $this->request->getPost('swiftcode');
			$accountholder = $this->request->getPost('accountholder');
			$accountno = $this->request->getPost('accountno');
		
			$User->bank_name = $bankname;
			$User->branch_name = $branchname;
			//$User->swift_code = $swiftcode;
			$User->account_holder_name = $accountholder;
			$User->account_no = $accountno;
			
			if ($User->save()) {
						$this->flash->success("Address  updated successfully!!");
						$this->response->redirect('address/address/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
			
		
		
		}
	
	}
	}