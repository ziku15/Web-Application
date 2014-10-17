<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\Admin as Admin;
use Phalcon\Mvc\View,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Password,
    Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength,
	Phalcon\Mvc\Model\Validator\Email,
    Phalcon\Paginator\Adapter\QueryBuilder;

class UserController extends \Phalcon\Mvc\Controller {

public function initialize() {
    $this->auth = $auth = $this->session->get('auth');
    if (!$auth) {
        $this->response->redirect('admin/auth/login/');
    }

    $this->view->setVars(array('title' => 'Bizmela Admin Homepage'));
}

    public function indexAction() 
    {
         $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\Admin')
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        $page['user'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'UserManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

    public function newAction() {
        $form = new Form();
        $email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'email',
            'placeholder' => 'Email',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
        $email->addValidator(new PresenceOf(array(
            'message' => 'The email field is required'
        )));
        
        $name = new Text("name", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'Name',
            'autocomplete' => 'off'
        ));
        $name->addValidator(new PresenceOf(array(
            'message' => 'The Name is required'
        )));
        $password = new Password("password", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'Password',
            'autocomplete' => 'off'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The Name is required'
        )));
        
        $form->add($email);
        $form->add($name);
        $form->add($password);
        $data['form'] = $form;
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            
            $name = $this->request->getPost('name');
            $password = $this->request->getPost('password');
            $email=$this->request->getPost('email');
            //$email_add = $email;
            //$email_add=$this->request->getPost('email');
            $previous_email = Admin::find('email=' . "'" . $email . "'" );
           
            if (!$form->isValid($_POST)) {
                        $this->flash->error("Please solve the following error !!");
                    }
            
                else if ($previous_email->count() > 0) {
                    $this->flash->error(" Email already exists");
                    //return false;
                }
                else
                {
                    
                    $User = new Admin();
                    $User->name = $name;
                    $password = sha1($password);
                    $User->password=$password;
                    $User->email=$email;
                    $User->created_by = $this->auth['id'];
                    $User->status = 1;
                    $User->created_at = date("Y-m-d h:i:s");
                    
                    if ($User->create()) {
                        $this->flash->success("Admin  added successfully!!");
                                
                        return $this->response->redirect('admin/user/index/');
                                
                        //return $this->response->redirect('user/passwordconfirm/');
                        //exit();
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                        /*$UserId=$User->id;
                        $to=$email;
                        $subject="Email verification";
                        $body='Hi, <br/> <br/> Please verify your email and get started using your Website account.Your password is: '.$password.'
                        <br/> <br/> <a href="http://localhost/sidra/user/passwordconfirm/'.$User->id.'">Click Here To Confirm</a>' ;
                        $this->Send_Mail($to,$subject,$body,$UserId);   */  
                }
            }
        }

        public function updateAction($value) {
        $User = Admin::findFirst('id=' . $value);
         $form = new Form();
        $email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'email',
            'placeholder' => 'Email',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
        $email->addValidator(new PresenceOf(array(
            'message' => 'The email field is required'
        )));
        
        $name = new Text("name", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'Name',
            'autocomplete' => 'off'
        ));
        $name->addValidator(new PresenceOf(array(
            'message' => 'The Name is required'
        )));
        $password = new Password("password", array(
            'class' => 'form-control input-lg form-element',
            'placeholder' => 'Password',
            'autocomplete' => 'off'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The Name is required'
        )));
        
        $form->add($email);
        $form->add($name);
        $form->add($password);
        $data['form'] = $form;
        $data['user']=$User;
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
                
                $name = $this->request->getPost('name');
                $password = $this->request->getPost('password');
                $email=$this->request->getPost('email');
                //$email_add = $email;
                //$email_add=$this->request->getPost('email');
                $previous_email = Admin::find('email=' . "'" . $email . "'" );
               
                $temp=0;
                $userid=$User->id;
                
                
                    
                    $previous_email = Admin::find('email=' . "'" . $email. "'" . ' and id not in ('.$userid.')');
                    
                    if ($previous_email->count() > 0) {
                        $this->flash->error(" Email already exists!!");
                        $temp=1;
                    }
                    
                
                if( $temp==0 )
                {
                    
                    //$User->username = $title;
                    //$password = $this->request->getPost('password');
                    $User->name = $name;
                    /*if ($password != null) {
                        $User->password = $this->security->hash($password);
                    }*/
                    
                    $password = sha1($password);
                    //echo $new_password;exit();
                    $User->password=$password;
                    $User->email=$email;
                    $User->updated_by = $this->auth['id'];
                    $User->status = 1;
                    $User->updated_at = date('Y-m-d h:i:s');
                    
                    if ($User->save()) {
                        $this->flash->success("Admin  updated successfully!!");
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                }
            }
        }
        //$data['action'] = "Create Press Release";
    }
public function deleteAction($value) {
    $User = Admin::findFirst('id=' . $value);
    $User->delete();
    $this->response->redirect('admin/user');
 }

}
