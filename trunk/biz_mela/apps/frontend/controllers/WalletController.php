<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\UserBankInfo as UserBankInfo;
use Biz_mela\Models\PaymentTransaction as PaymentTransaction;
use Biz_mela\Models\PaymentMethod as PaymentMethod;
use Biz_mela\Models\OrderMaster as OrderMaster;



use Phalcon\Tag as Tag,
Phalcon\Forms\Form,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Password,
Phalcon\Validation\Validator\PresenceOf;

class WalletController extends ControllerBase
{

	public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'My Wallet'));
        
        
    }
	
	
	public function indexAction()
    {
		
	}
	
	public function passAction()
	{	
		
		$form = new Form();

        $password = new Password("password", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'password'
        ));
		$form->add($password);
		$data['form'] = $form;
        $this->view->setVars($data);

		if ($this->request->isPost()) {
			$password = $this->request->getPost('password');
            $pass = sha1($password);
		
			$username = $this->session->get('auth');	
			$user = UserMaster::findFirst("username="."'".$username['name']."'");
			$prev=$user->password;
			$prev=sha1($prev);

		
			if ($pass=$prev){
				//return $this->forward('wallet/wallet/');
				return $this->response->redirect('wallet/wallet/');
			
			}
		}
		
	}
	
	public function walletAction() {
		
		$username = $this->session->get('auth');
		
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
		$userid=$con->id;
		$a=UserBankInfo::findFirst("user_id="."'".$userid."'");
		$data['value']=$a;
		
		$this->view->setVar('data',$data);
		
		
    }
	
    public function accountAction() {

    	$username = $this->session->get('auth');
		
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
		$userid=$con->id;
		$a=UserBankInfo::findFirst("user_id="."'".$userid."'");
		$data['value']=$a;
		
		$this->view->setVar('data',$data);
    }


    public function paymentAction() {

    	$username = $this->session->get('auth');
		
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
		$userid=$con->id;

		/*$paymentResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderMaster')
                  ->columns('Biz_mela\Models\OrderMaster.order_no, Biz_mela\Models\OrderMaster.total, m.method_name')
                  ->leftJoin('Biz_mela\Models\PaymentTransaction', 't.order_master_id = Biz_mela\Models\OrderMaster.id', 't')
                  ->join('Biz_mela\Models\PaymentMethod', 'm.id = t.payment_method_id', 'm','right')
                  ->andWhere('Biz_mela\Models\OrderMaster.user_id = $userid')
                  ->orderBy('Biz_mela\Models\OrderMaster.id desc')
                  
                  ->getQuery()
                  ->execute();
        $this->view->setVar(paymentResult,$paymentResult);*/

		/*$a=UserBankInfo::findFirst("user_id="."'".$userid."'");
		$data['value']=$a;
		
		$this->view->setVar('data',$data);*/


		$phql = ("SELECT Biz_mela\Models\OrderMaster.order_no, Biz_mela\Models\OrderMaster.total, Biz_mela\Models\PaymentMethod.method_name
			FROM Biz_mela\Models\OrderMaster, Biz_mela\Models\PaymentMethod, Biz_mela\Models\PaymentTransaction
			WHERE Biz_mela\Models\PaymentTransaction.order_master_id = Biz_mela\Models\OrderMaster.id
			AND Biz_mela\Models\PaymentMethod.id = Biz_mela\Models\PaymentTransaction.payment_method_id
			AND Biz_mela\Models\OrderMaster.user_id = $userid
			LIMIT 0 , 30");

		$newresult = $this->modelsManager->executeQuery($phql);

		//$newresult = $query->execute();
		//$data['value']=$newresult;
                 //print_r($data['value']);exit();
         $this->view->setVar(newresult,$newresult);

    }



	public function updateAction() {
		
		
		$username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		$userid=$con->id;
		$User=UserBankInfo::findFirst("user_id="."'".$userid."'");
		
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
		$swiftcode = new Text("swiftcode", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'swiftcode',
            'placeholder' => $User->swift_code,
            'autocomplete' => 'off'
        ));
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
		$form->add($swiftcode);
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
			$swiftcode = $this->request->getPost('swiftcode');
			$accountholder = $this->request->getPost('accountholder');
			$accountno = $this->request->getPost('accountno');
		
			$User->bank_name = $bankname;
			$User->branch_name = $branchname;
			$User->swift_code = $swiftcode;
			$User->account_holder_name = $accountholder;
			$User->account_no = $accountno;
			
			if ($User->save()) {
						$this->flash->success("Admin  updated successfully!!");
						//$this->response->redirect('sell/index/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
			
		
		
		}
	
	
	
	
	
	
	
	
	
	}
	
























}