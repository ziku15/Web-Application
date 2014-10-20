<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\UserBankInfo as UserBankInfo;

use Phalcon\Tag as Tag,
	Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text;

class AccinfoController extends ControllerBase
{
    public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'Account Information'));
        
        //Tag::setTitle('Account Information');
        //parent::initialize();
    }

    public function indexAction()
    {
		
	}
	
	
	
	
	public function accinfoAction() {
		$username = $this->session->get('auth');
		$con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
		$data['value']=$con;
		$this->view->setVar('data',$data);
		$userid=$con->id;
		$user=UserBankInfo::findFirst("user_id="."'".$userid."'");
		$record['value']=$user;
		$this->view->setVar('record',$record);

    }



    public function editcontactAction(){


    	$username = $this->session->get('auth');
		$user=UserMaster::findFirst("username="."'".$username['name']."'");
		//$this->view->setVar('data',$data);
		

		$form = new Form();

		$username = new Text("username", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'username',
            'value' => $user->username,
            'autocomplete' => 'off'
        ));

        $email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'email',
            'value' => $user->email,
            'autocomplete' => 'off'
        ));

        $cellphone = new Text("cellphone", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'cellphone',
            'value' => $user->contact_no,
            'autocomplete' => 'off'
        ));

        $address = new Text("address", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'address',
            'value' => $user->address,
            'autocomplete' => 'off'
        ));
		

		$form->add($username);
        $form->add($email);
		$form->add($cellphone);
		$form->add($address);
		
		//$form->add($dob);
		
		$data['form'] = $form;
        $this->view->setVars($data);


        if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('accinfo/accinfo/');
            }
		
			$username = $this->request->getPost('username');
			$email = $this->request->getPost('email');
			$cellphone = $this->request->getPost('cellphone');
			$address = $this->request->getPost('address');
			
			//$dob = $this->request->getPost('dob');
			//$accountno = $this->request->getPost('accountno');
			$user->username = $username;
			$user->email = $email;
			$user->contact_no = $cellphone;
			$user->address = $address;
			
			//$User->account_holder_name = $accountholder;
			//$User->dob = $dob;
			
			if ($user->save()) {
						$this->flash->success("Adress  updated successfully!!");
						$this->response->redirect('accinfo/accinfo/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
			}

	}


	public function editbankAction(){


    	//$username = $this->session->get('auth');
		//$user=UserMaster::findFirst("username="."'".$username['name']."'");
		//$this->view->setVar('data',$data);
		$username = $this->session->get('auth');
		$con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
		$userid=$con->id;
		$info=UserBankInfo::findFirst("user_id="."'".$userid."'");
		
		
		$form = new Form();

		$accountholder = new Text("accountholder", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'accountholder',
            'value' => $info->account_holder_name,
            'autocomplete' => 'off'
        ));

        $accountno = new Text("accountno", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'accountno',
            'value' => $info->account_no,
            'autocomplete' => 'off'
        ));

        $bank = new Text("bank", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'bank',
            'value' => $info->bank_name,
            'autocomplete' => 'off'
        ));

        $branch = new Text("branch", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'branch',
            'value' => $info->branch_name,
            'autocomplete' => 'off'
        ));

        $swiftcode = new Text("swiftcode", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'swiftcode',
            'value' => $info->swift_code,
            'autocomplete' => 'off'
        ));
		

		$form->add($accountholder);
        $form->add($accountno);
		$form->add($bank);
		$form->add($branch);
		$form->add($swiftcode);
		
		//$form->add($dob);
		
		$data['form'] = $form;
        $this->view->setVars($data);


        if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('accinfo/accinfo/');
            }
		
			$accountholder = $this->request->getPost('accountholder');
			$accountno = $this->request->getPost('accountno');
			$bank = $this->request->getPost('bank');
			$branch = $this->request->getPost('branch');
			$swiftcode = $this->request->getPost('swiftcode');
			
			//$dob = $this->request->getPost('dob');
			//$accountno = $this->request->getPost('accountno');
			$info->account_holder_name = $accountholder;
			$info->account_no = $accountno;
			$info->bank_name = $bank;
			$info->branch_name = $branch;
			$info->swift_code = $swiftcode;
			
			//$User->account_holder_name = $accountholder;
			//$User->dob = $dob;
			
			if ($info->save()) {
						$this->flash->success("Account  updated successfully!!");
						$this->response->redirect('accinfo/accinfo/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
			}

	}
	
}