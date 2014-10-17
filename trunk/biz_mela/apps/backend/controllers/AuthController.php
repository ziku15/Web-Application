<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\Admin as Admin;
use Phalcon\Mvc\View,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Password,
    Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength;

class AuthController extends \Phalcon\Mvc\Controller
{

    public function initialize()
    {
     
        $auth = $this->session->get('auth');
        if (!$auth) {
            $role = 'Guests';
        } else {
            $this->response->redirect('admin');
        }
        
    }

    public function indexAction()
    {
        echo $this->security->hash('1234');
    }


    private function _registerSession($user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'name' => $user->name
        ));
    }

    public function loginAction()
    {

        $form = new Form();

        $name = new Text("name", array(
                'class' => 'form-control input-lg',
                'placeholder' => 'name',
                'maxlength' => 30
            ));
        $name->addValidator(new PresenceOf(array(
            'message' => 'The name field is required'
        )));


        $password = new Password("password", array(
                'class' => 'form-control input-lg form-element',
                'placeholder' => 'password'
            ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password field is required'
        )));

        $form->add($name);
        $form->add($password);

        if($this->request->isPost()){
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            }else{
                $name = $this->request->getPost('name');
                $password = $this->request->getPost('password');
                // $password = sha1($password);

                $user = Admin::findFirst("name = '$name' AND status = 1");
                $pass=$user->password;
                if ($user != false) {
                    $password=sha1($password);
                    if ($password==$pass){
                        if($user->first_login==null)
                        {

                            $user->first_login=date("Y-m-d h:i:s");
                        }
                        $user->login_time=date("Y-m-d h:i:s");
                        $user->save();
                        $this->_registerSession($user);                    
                            $this->response->redirect('admin');
                    }else{
                        $this->flash->error("Wrong username or password !!");
                    }

                    
                }else{
                    $this->flash->error("No user exists !!");
                }
            }
        }
        
        $data['form'] = $form;
        $this->view->setVars($data);
    	$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

    }

    
}

