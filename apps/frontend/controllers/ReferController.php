<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\UserPoint as UserPoint;



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


class ReferController extends ControllerBase
{

	 public function initialize()
     {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'Refer Friends'));
        
        //Tag::setTitle('Account Information');
        //parent::initialize();
    }

    public function indexAction()
    {
		
	}
	

	public function referAction()
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


        if ($this->request->isPost()) {

        	if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('accdashboard/dashinfo/');
            }
             if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else{
            	$email_add=$this->request->getPost('email');
				$previous_email = UserMaster::find('email=' . "'" . $email_add . "'" );
				if ($previous_email->count() > 0) {
								$this->flash->error("Email Already Exists!!");
					}
            	else{
	            	$username = $this->session->get('auth');
					$con=UserMaster::findFirst("username="."'".$username['name']."'");
					$ref_code=$con->invite_code;
					$user_id=$con->id;

					$to=$email_add;
					$subject="Register To bizmela";
					$body='Hi, <br/> <br/> Bizmela is a nice Ecommerce site.You can explore it by registration.My invitation code is  '.$ref_code.' . 
					<br/> <br/> <a href="http://localhost/biz_mela/refreg/registration/'.$ref_code.'">Click Here To Confirm</a>' ;

					$this->Send_Mail($to,$subject,$body);
					$this->flash->success("Invitation Sent!!");
					return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'refer',
					 		'action' => 'refer',
					 		
					 		
					 		)
					 	);
				//return $this->response->redirect('session/activationcodeconfirm/'.$User->id); 
            	}
        	}



        }

	}


	private function Send_Mail($to,$subject,$body)
	{
	//echo __DIR__; exit();
	// require_once __DIR__ . '/../../../PHPMailer/class.phpmailer.php';
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
		return $this->response->redirect('refer/refer/');
		}
	}



	public function invitationconfirmAction($value) {
        $User = UserMaster::findFirst('id=' . $value);
        $form = new Form();

        $invitation_code = new Text("invitation_code", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'Invitation Code',
            'autocomplete' => 'off'
        ));
        $invitation_code->addValidator(new PresenceOf(array(
            'message' => 'The  Invitation code field is required'
        )));
      

        $form->add($invitation_code);
        $data['form'] = $form;
        $this->view->setVars($data);
		//$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        if ($this->request->isPost()) {

			
            
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
				
                $invitationcode = $this->request->getPost('invitation_code');
                if ($invitationcode==$User->invite_code) {
					return $this->response->redirect('session/register/');
                }
				else
				{
					$this->flash->error("Invitation Code Not Same!!");
				}
            }
        }
        //$data['action'] = "Create Press Release";
    }


    public function registrationAction($ref_code)
    {
    	$suggest = UserMaster::findFirst('invite_code=' . $ref_code);

    	$form = new Form();

    	$firstname = new Text("firstname", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'firstname',
            'placeholder' => 'Firstname',
            'autocomplete' => 'off'
        ));
		$firstname->addValidator(new PresenceOf(array(
            'message' => 'The First Name field is required'
        )));

        $lastname = new Text("lastname", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'lastname',
            'placeholder' => 'Lastname',
            'autocomplete' => 'off'
        ));
		$lastname->addValidator(new PresenceOf(array(
            'message' => 'The Last Name field is required'
        )));

        $username = new Text("username", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'username',
            'placeholder' => 'Username',
            'autocomplete' => 'off'
        ));
		$username->addValidator(new PresenceOf(array(
            'message' => 'The UserName field is required'
        )));

        $cellphone = new Text("cellphone", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'cellphone',
            'placeholder' => 'Cell Phone',
            'autocomplete' => 'off'
        ));
		$cellphone->addValidator(new PresenceOf(array(
            'message' => 'The Cell Phone field is required'
        )));

        $email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'email',
            'placeholder' => 'Email Address',
            'autocomplete' => 'off'
        ));
		$email->addValidator(new PresenceOf(array(
            'message' => 'The email field is required'
        )));

        $password = new Password("password", array(
                'class' => 'form-control input-lg form-element',
				'id' => 'password',
                'placeholder' => 'password'
            ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password field is required'
        )));

        $repeatPassword = new Password("repeatPassword", array(
                'class' => 'form-control input-lg form-element',
				'id' => 'repeatPassword',
                'placeholder' => 'Repeat Password'
            ));
        $repeatPassword->addValidator(new PresenceOf(array(
            'message' => 'The Repeat Password field is required'
        )));

        $address = new Text("address", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'address',
            'placeholder' => 'Address',
            'autocomplete' => 'off'
        ));

        $dob = new Text("dob", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'dob',
            'placeholder' => 'Date of Birth(YYYY-MM-DD Format)',
            'autocomplete' => 'off'
        ));
        $dob->addValidator(new PresenceOf(array(
            'message' => 'The Date of Birth field is required'
        )));

        $form->add($firstname);
        $form->add($lastname);
        $form->add($username);
        $form->add($cellphone);
        $form->add($email);
        $form->add($password);
        $form->add($repeatPassword);
        $form->add($address);
        $form->add($dob);
        
        $data['form'] = $form;
        $this->view->setVars($data);


        if ($this->request->isPost()) {


        	if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            }else{
            	//echo $this->request->getPost('email');exit();
	            $firstname = $this->request->getPost('firstname', array('string', 'striptags'));
	            $lastname = $this->request->getPost('lastname', array('string', 'striptags'));
	            $username = $this->request->getPost('username', 'alphanum');
	            $email = $this->request->getPost('email');
	            $password = $this->request->getPost('password');
	            $repeatPassword = $this->request->getPost('repeatPassword');
				//$type=$this->request->getPost('type');
				$contact_no=$this->request->getPost('cellphone');
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
	            $user->first_name = $firstname;
	            $user->last_name = $lastname;

				$user->email = $email;
	            $user->password = sha1($password);
	            
	            
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
                $suggest->point=$suggest->point + 100;
                $userid=$user->id;
                $point= new UserPoint();
                $point->user_id=$userid;
                $point->point=50;
                $point->point_type="Registration Point";
                $point->status=1;


                $this->flash->success('Thanks for sign-up, please log-in to explore BizMela');
						/*$userid=$user->id;
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
					 	);*/
						
						//return $this->forward('session/index');
            		}
			
            	}







        }

	}
	


}