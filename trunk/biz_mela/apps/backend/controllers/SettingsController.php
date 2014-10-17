<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\Admin as Admin;
use Phalcon\Mvc\View,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Password,
    Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Identical,
	Phalcon\Validation\Validator\StringLength;

class SettingsController extends \Phalcon\Mvc\Controller
{

	public function initialize()
    {
        $this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            $this->response->redirect('admin/auth/login/');
        }


        $this->view->setVars(array('title'=>'Settings'));
    }

    public function indexAction()
    {

    }

    public function changepasswordAction()
    {
    	$form = new Form();

        $current_password = new Password("current_pass", array(
                'class' => 'form-control input-lg',
                'placeholder' => 'current password'
            ));
        $current_password->addValidator(new PresenceOf(array(
            'message' => 'The current password field is required'
        )));


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
		
		/* $new_password->addValidator(new Identical(array(
            'value'   => $current_password,
			'message' => 'Your current password and new password same'
        ))); */
		
		
        $form->add($current_password);
        $form->add($new_password);
        $form->add($confirm_password);

        if($this->request->isPost()){
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            }else{
                $id = $this->auth['id'];
                $current_password = $this->request->getPost('current_pass');
				$new_password = $this->request->getPost('new_pass');
                $confirm_password = $this->request->getPost('confirm_pass');
                 //echo $current_password;

                $user = Admin::findFirst("id = '$id' AND status = 1");
                $pass=$user->password;

                if ($user != false) {
					if($current_password==$new_password)
					{
						$this->flash->error("Current password and new password same !!");
					}else{
                      $current_password = sha1($current_password);
                      
						if ($current_password==$pass){
							/* $new_password = $this->request->getPost('new_pass');
							$confirm_password  = $this->request->getPost('confirm_pass'); */
							if($new_password != $confirm_password){
								$this->flash->error("Confirm password doesn't match !!");
							}else{
								// $new_password = sha1($new_password);
								$new_password = sha1($new_password);
								$user->password = $new_password;
								$user->save();
								$this->flash->success("Password successfully changed !!");
                               
							}
						}else{
								
								$this->flash->error("Wrong current password !!");
						}
					}

                }else{
                    $this->flash->error("Error occured,Please contact with administrator !!");
                }
            }
        }
        

        $data['form'] = $form;
        $this->view->setVars($data);
    	
    }

}

