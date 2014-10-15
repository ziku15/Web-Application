<?php

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
	//$email= $this->session->get('auth');
	//print_r("username:".$username['name']);
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
			$data['value']=$con;
		
		
		$this->view->setVar('data',$data);
		
		$user_id=$con->id;
		$User=UserBankInfo::findFirst("user_id="."'".$user_id."'");
		$record['value']=$User;
		$this->view->setVar('record',$record);
		
    }
	
	
	public function editAction(	){
	
		$username = $this->session->get('auth');
        $User=UserMaster::findFirst("username="."'".$username['name']."'");
		$form = new Form();
		$username = new Text("username", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'username',
            'placeholder' => $User->username,
            'autocomplete' => 'off'
        ));
		$email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'email',
            'placeholder' => $User->email,
            'autocomplete' => 'off'
        ));
		$contactno = new Text("contactno", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'contactno',
            'placeholder' => $User->contact_no,
            'autocomplete' => 'off'
        ));
		$dob = new Text("dob", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'dob',
            'placeholder' => $User->dob,
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
	
	
	
	
	
	
}