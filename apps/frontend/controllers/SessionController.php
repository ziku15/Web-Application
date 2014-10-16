<?php

namespace Biz_mela\Frontend\Controllers;
//namespace Biz_mela\Controllers\ControllerBase;


use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\UserBankInfo as UserBankInfo;
use Biz_mela\Library\PHPMailer as PHPMailer;

use Phalcon\Tag as Tag,
	Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Date,
	Phalcon\Forms\Element\Password,
	Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength,
	Phalcon\Mvc\Model\Validator\Email,
	Phalcon\Forms\Element\Select,
	Phalcon\Security;

class SessionController extends ControllerBase
{
    public function initialize()
    {	
        //$this->view->setTemplateAfter('main');
        Tag::setTitle('Sign Up/Sign In');
        parent::initialize();
		
    }

    public function indexAction()
    {	
		
        //echo $this->security->hash('1234');
    }
	
	
	

    public function registerAction()
    {
	
	
		
        $request = $this->request;
        if ($request->isPost()) {

            $name = $request->getPost('name', array('string', 'striptags'));
            $username = $request->getPost('username', 'alphanum');
            $email = $request->getPost('email', 'email');
            $password = $request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');
			$type=$this->request->getPost('type');
			$contact_no=$this->request->getPost('contact_no');
			$address=$this->request->getPost('address');
			$dob=$this->request->getPost('dob');
			$previous_user = UserMaster::find('username=' . "'" . $username . "'" );
			$previous_email = UserMaster::find('email=' . "'" . $email . "'" );
			
            
			
			if ($previous_email->count() > 0) {
                    $this->flash->error(" Email already exists");
                    return false;
                }
			if ($previous_user->count() > 0) {
                    $this->flash->error(" Username already exists");
                    return false;
                }
			if ($password != $repeatPassword) {
                $this->flash->error('Passwords are diferent');
                return false;
            }
			if (strlen($password)<8 ) {
                $this->flash->error('Password too short');
                return false;
            }
			
			
				
			

            $user = new UserMaster();
            $user->username = $username;
			//$new_password = $this->security->hash($password);
					
            //$user->password=$new_password;
			//$user->password = $this->security->hash($new_password);
            $user->password = sha1($password);
            $user->name = $name;
            $user->email = $email;
			$user->type = $type;
			$user->contact_no =$contact_no ;
			$user->address =$address ;
			$user->dob=$dob ;
            //$user->created_at = new Phalcon\Db\RawValue('now()');
			$user->status = 0;
            //$user->active = 'Y';
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
				
			
            } else {
                Tag::setDefault('email', '');
                Tag::setDefault('password', '');
                $this->flash->success('Thanks for sign-up, please log-in to explore BizMela');
						$userid=$user->id;
						$to=$email;
						
						$subject="Email verification";
						$body='Hi, <br/> <br/> Please verify your email and get started using your Website account.
						<br/> <br/> <a href="http://localhost/biz_mela/session/status/'.$userid.'">Click Here To Confirm</a>' ;
						//mail($to,$subject,$body);
						//$this->Send_Mail($to,$subject,$body);	
						//return $this->forward('session/index');
						return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'session',
					 		'action' => 'index'
					 		
					 		)
					 	);
						
						//return $this->forward('session/index');
            }
        }
    }
	
	
	
	
	private function Send_Mail($to,$subject,$body)
	{
	//echo __DIR__; exit();
	//require_once __DIR__ . '/../../../PHPMailer/class.phpmailer.php';
	$from       = "info@optimaxbd.net";
	$mail       = new PHPMailer();
	$mail->IsSMTP(true);            // use SMTP
	$mail->IsHTML(true);
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "smtp.optimaxbd.net"; // SMTP host
	$mail->Port       =  25;                    // set the SMTP port
	$mail->Username   = "";  // SMTP  username
	$mail->Password   = "";  // SMTP password
	$mail->SetFrom($from, 'Sidra Term');
	$mail->AddReplyTo($from,'Sidra Term');
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address, $to);
	$mail->Send();
	if(!$mail->Send()) {
		$this->flash->error("Mail not Sent!! Mailer error:". $mail->ErrorInfo);
		
		} else {
		$this->flash->success("Mail Sent!!");
		return $this->response->redirect('session/register/');
		}
	}
	
	
	public function statusAction($userid){
	
		$user = UserMaster::findFirst('id=' . $userid);
		$user->status="1";
		$user->save();
		$data['value']=$user;
		$this->view->setVar('data',$data);
		$form = new Form();
		//return $this->response->redirect('user/newlogin/'.$User->id);
		if ($this->request->getPost('submit') == 'login') {
                return $this->response->redirect('session/firstlogin/'.$user->id);
				//return $this->response->redirect('auth/login/');
            }
		
		$data['form'] = $form;
        $this->view->setVars($data);
    	//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	
	}

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'name' => $user->username,
			
        ));
    }

    /**
     * This actions receive the input from the login form
     *
     */
	 
	public function firstloginAction()
	{
		
		$form = new Form();

        $username = new Text("username", array(
                'class' => 'form-control input-lg',
				'id' => 'username',
                'placeholder' => 'username',
                'maxlength' => 30
            ));
        $username->addValidator(new PresenceOf(array(
            'message' => 'The username field is required'
        )));


        $password = new Password("password", array(
                'class' => 'form-control input-lg form-element',
				'id' => 'password',
                'placeholder' => 'password'
            ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password field is required'
        )));

        $form->add($username);
        $form->add($password);
		

        if($this->request->isPost()){
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            }else{
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');
                 $password = sha1($password);

                $user = UserMaster::findFirst("username = '$username' AND status = 1");
				$userid=$user->id;
                if ($user != false) {

                    if ($password=$user->password){
                        $this->_registerSession($user); 
							$userid=$user->id;
                            $this->response->redirect('session/shop/'.$userid);
                    }else{
                        $this->flash->error("Wrong username or password !!");
                    }

                    
                }else{
                    $this->flash->error("No user exists !!");
                
				
            }
        }
		}
        

        $data['form'] = $form;
		//$data['value'] = $userid;
        $this->view->setVars($data);
    	//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);		
	}
	 
	 
    public function startAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $password = sha1($password);
			//$type=$this->request->getPost('type');

            $user = UserMaster::findFirst("email='$email' AND password='$password'");
            if ($user != false) {
                $this->_registerSession($user);
                //$this->flash->success('Welcome ' . $user->name);
				$a=UserMaster::findFirst("email="."'".$email."'");
                //return $this->forward('buy/index');
				$userid=$a->id;
				if($a->type == 'B') {
				if($a->status == 0)
				//return $this->forward('buy/index');
					return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'buy',
					 		'action' => 'index',
					 		
					 		)
					 	);
				else if ($a->status == 1)
				//return $this->forward('buy/index');
					return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'buy',
					 		'action' => 'index'
					 		
					 		)
					 	);

				}
                else if($a->type == 'S') {
				if($a->status == 0){
				//$userid['value']=$value;
				return $this->response->redirect('session/shop/'.$userid);
				//return $this->forward('session/shop/'.$userid );
				}
				else if ($a->status == 1)
				return $this->response->redirect('sell/index/'.$userid);
				}
				
				
				/*if($a->type == 'B') return $this->forward('buy/index');
                else if($a->type =='S') return $this->forward('sell/index');*/
            }

            $username = $this->request->getPost('email', 'alphanum');
            $user = UserMaster::findFirst("username='$username' AND password='$password'");
            if ($user != false) {
                $this->_registerSession($user);				
                //$this->flash->success('Welcome ' . $user->username);
				$a=UserMaster::findFirst("username="."'".$username."'");
                //return $this->forward('buy/index');
				$userid=$a->id;
				if($a->type == 'B') {
				if($a->status == 0)
				//return $this->forward('buy/index');
					return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'buy',
					 		'action' => 'index'
					 		
					 		)
					 	);
				else if ($a->status == 1)
				//return $this->forward('buy/index');
					return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'buy',
					 		'action' => 'index'
					 		
					 		)
					 	);
				}
                else if($a->type == 'S') {
				if($a->status == 0){
				
				//return $this->response->redirect('session/shop/'.$userid);
				//return $this->forward('session/shop/'.$userid );
				return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'session',
					 		'action' => 'shop',
					 		'param' => $userid
					 		)
					 	);
				}
				else if ($a->status == 1)
				// return $this->dispatcher->forward('sell/index/'.$userid);
					 return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'sell',
					 		'action' => 'index',
					 		'param' => $userid
					 		)
					 	);
				}
            }

            $this->flash->error('Wrong username/password');
        }

        //return $this->forward('session/index');
        return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'session',
					 		'action' => 'index'
					 		
					 		)
					 	);

    }
	
	public function shopAction($userid)
	{
		$user = UserMaster::findFirst('id=' . $userid);
		
		
		
		//$data['value']=$User;
		//$this->view->setVar('data',$data);
		$form = new Form();
		$shopname = new Text("shopname", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'shopname',
            'placeholder' => 'shopname',
			'autocomplete' => 'off'
        ));
		
		$form->add($shopname);
		$data['form'] = $form;
        $this->view->setVars($data);
		
		if ($this->request->isPost()){
			$shopname = $this->request->getPost('shopname');
			$Shop = new ShopMaster();
			$Shop->shop_name = $shopname;
			$Shop->user_id= $userid;
			$Shop->created_at = date("Y-m-d h:i:s");
			$Shop->updated_at = date('Y-m-d h:i:s');
			if ($Shop->create()) {
						$shop->user_id=$userid;
						$this->flash->success("Shop added successfully!!");
						//echo 'ytytyt';exit();		
						return $this->response->redirect('session/bank/'.$userid);
								
						//return $this->response->redirect('user/passwordconfirm/');
						//exit();
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
		
		
		
		}
		//return $this->response->redirect('user/newlogin/'.$User->id);
		/*if ($this->request->getPost('submit') == 'login') {
                return $this->response->redirect('user/newlogin/'.$User->id);
				//return $this->response->redirect('auth/login/');
            }*/
		
		
    	//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	
	
	}
	
	
	
	public function bankAction($userid)
	{
		$User = UserMaster::findFirst('id=' . $userid);
		//$User->status="1";
		//$User->user_id=$userid;
		//$User->save();
		
		
		//$data['value']=$User;
		//$this->view->setVar('data',$data);
		$form = new Form();
		$bankname = new Text("bankname", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'bankname',
            'placeholder' => 'bankname',
			'autocomplete' => 'off'
        ));
		$branchname = new Text("branchname", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'branchname',
            'placeholder' => 'branchname',
			'autocomplete' => 'off'
        ));
		$account_holder = new Text("account_holder", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'account_holder',
            'placeholder' => 'account holder name',
			'autocomplete' => 'off'
        ));
		$swiftcode = new Text("swiftcode", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'swiftcode',
            'placeholder' => 'swiftcode',
			'autocomplete' => 'off'
        ));
		$account_no = new Text("account_no", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'account_no',
            'placeholder' => 'Account Number',
			'autocomplete' => 'off'
        ));
		
		
		$form->add($bankname);
		$form->add($branchname);
		$form->add($account_holder);
		$form->add($swiftcode);
		$form->add($account_no);
		$data['form'] = $form;
        $this->view->setVars($data);
		
		if ($this->request->isPost()){
			$bankname = $this->request->getPost('bankname');
			$branchname = $this->request->getPost('branchname');
			$account_holder = $this->request->getPost('account_holder');
			$swiftcode = $this->request->getPost('swiftcode');
			$account_no = $this->request->getPost('account_no');
			$Bank = new UserBankInfo();
			$Bank->bank_name = $bankname;
			$Bank->branch_name = $branchname;
			$Bank->account_holder_name = $account_holder;
			$Bank->swift_code = $swiftcode;
			$Bank->account_no = $account_no;
			$Bank->user_id= $userid;
			$Bank->created_at = date("Y-m-d h:i:s");
			$Bank->updated_at = date('Y-m-d h:i:s');
			$Bank->user_id= $userid;
			if ($Bank->create()) {
						$Bank->user_id=$userid;
						$type=$User->type;
						
						$this->flash->success("Account added successfully!!");
						//echo 'ytytyt';exit();	
							if($type=='S'){
						return $this->response->redirect('sell/index/'.$userid);
						}
						else if ($type=='B'){
						
						return $this->response->redirect('buy/index/'.$userid);
						
						}
								
						//return $this->response->redirect('user/passwordconfirm/');
						//exit();
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
		
		
		
		}
		//return $this->response->redirect('user/newlogin/'.$User->id);
		/*if ($this->request->getPost('submit') == 'login') {
                return $this->response->redirect('user/newlogin/'.$User->id);
				//return $this->response->redirect('auth/login/');
            }*/
		
		
    	//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	
	
	}
	
    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
	 
	 public function forgetpasswordAction()
	{
		$form = new Form();
		$email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'Email',
            'autocomplete' => 'off'
        ));
		$email->addValidator(new PresenceOf(array(
            'message' => 'The email field is required'
        )));
		
		
		$form->add($email);
		$data['form'] = $form;
        $this->view->setVars($data);
		//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		$length = 5;
		$randomString = substr(number_format(time() * mt_rand(),0,'',''),0,$length); 
		
		if ($this->request->isPost()) {
            if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('session/index/');
            }
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
				$email_add=$this->request->getPost('email');
				$previous_email = UserMaster::find('email=' . "'" . $email_add . "'" );
				if ($previous_email->count() <= 0) {
								$this->flash->error("Email Not Found!!");
							}
				
				else{
						$previous_activationcode = UserMaster::find('activation_code=' . "'" . $randomString . "'" );
						if ($previous_activationcode->count() > 0) {
								$this->flash->error("Activation Code Already exists!!");
							}
						else{
							$User = UserMaster::findFirst('email=' ."'" . $email_add . "'");
							$User->created_at = date("Y-m-d h:i:s");
							$User->updated_at = date('Y-m-d h:i:s');
							$User->activation_code=$randomString;
							if ($User->save()) {
							    $UserId=$User->id;
								$to=$email_add;
								$subject="Email verification";
								$body='Hi, <br/> <br/> Please to change Your Passwordyour Activation Code is '.$randomString.' . <br/> <br/> <a href="http://localhost/bizmela/session/activationcodeconfirm/'.$User->id.'">Click Here To Confirm</a>' ;
								$this->Send_Mail_two($to,$subject,$body,$UserId);
									 $this->flash->success("Activation Code Sent!!");
									return $this->response->redirect('session/activationcodeconfirm/'.$User->id); 
							} else {
								$this->flash->error("error occured,please try again later!!");
							}
						}
				
            }
			}
        }
	}
	
	private function Send_Mail_two($to,$subject,$body,$UserId)
	{
	require_once __DIR__ . '/../../PHPMailer/class.phpmailer.php';
	$from       = "info@optimaxbd.net";
	$mail       = new PHPMailer();
	$mail->IsSMTP(true);            // use SMTP
	$mail->IsHTML(true);
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "smtp.optimaxbd.net"; // SMTP host
	$mail->Port       =  25;                    // set the SMTP port
	$mail->Username   = "";  // SMTP  username
	$mail->Password   = "";  // SMTP password
	$mail->SetFrom($from, 'Sidra Term');
	$mail->AddReplyTo($from,'Sidra Term');
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address, $to);
	$mail->Send();
	if(!$mail->Send()) {
		$this->flash->error("Activation Code not Sent!! Mailer error:". $mail->ErrorInfo);
		
		} else {
		$this->flash->success("Activation Code Sent!!");
		return $this->response->redirect('session/forgetpassword/');
		}
	}
	
	
	public function activationcodeconfirmAction($value) {
        $User = UserMaster::findFirst('id=' . $value);
        $form = new Form();

        $activation_code = new Text("activation_code", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'Activation Code',
            'autocomplete' => 'off'
        ));
        $activation_code->addValidator(new PresenceOf(array(
            'message' => 'The activation code field is required'
        )));
      

        $form->add($activation_code);
        $data['form'] = $form;
        $this->view->setVars($data);
		//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        if ($this->request->isPost()) {

			
            if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('user/index/');
            }
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
				
                $activationcode = $this->request->getPost('activation_code');
                if ($activationcode==$User->activation_code) {
					return $this->response->redirect('session/updatepassword/'.$User->id);
                }
				else
				{
					$this->flash->error("Activation Code Not Same!!");
				}
            }
        }
        //$data['action'] = "Create Press Release";
    }
	
	
	public function updatepasswordAction($value) {
        $User = UserMaster::findFirst('id=' . $value);
        $form = new Form();
        $new_password = new Password("new_pass", array(
                'class' => 'form-control input-lg form-element',
                'placeholder' => 'new password'
            ));
        $new_password->addValidator(new PresenceOf(array(
            'message' => 'The new password field is required'
        )));

        $confirm_password = new Password("confirm_pass", array(
                'class' => 'form-control input-lg form-element',
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
        $form->add($new_password);
        $form->add($confirm_password);

        $data['form'] = $form;
        $this->view->setVars($data);
		//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        if ($this->request->isPost()) {

			
            if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('session/index/');
            }
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
				
                $new_password = $this->request->getPost('new_pass');
                $confirm_password = $this->request->getPost('confirm_pass');
                  if ($User != false) {
							if($new_password != $confirm_password){
								$this->flash->error("Confirm password doesn't match !!");
							}else{
								 $new_password = sha1($new_password);
								//$new_password = $this->security->hash($new_password);
								$User->password = $new_password;
								$User->save();
								$this->flash->success("Password successfully changed !!");
								return $this->response->redirect('session/index/');
							}
                }else{
                    $this->flash->error("Error occured,Please contact with administrator !!");
                }
            }
        }
		$data['form'] = $form;
        $this->view->setVars($data);
        //$data['action'] = "Create Press Release";
    }
	
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->forward('index/index');
    }
}
