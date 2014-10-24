<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\Newsletter as Newsletter;
use Biz_mela\Models\ShopMaster as ShopMaster;


use Phalcon\Tag as Tag,
Phalcon\Forms\Element\Text,
Phalcon\Validation\Validator\PresenceOf,

Phalcon\Forms\Form;

class SubscribeController extends ControllerBase
{

	public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'Newsletter Subscription'));
        
        
        
    }

     public function indexAction()
    {
		
	}

	 public function subscriptionAction()
	 {
	 	$username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		$user_email=$con->email;

		$prev_email=Newsletter::find("email="."'".$user_email."'");

        $msg="";

		if ($prev_email->count() > 0) {
                   $msg="You are already subscribed to newsletter";

                   
                    
                } else  {
                	$msg="You are not Subscribed to newsletter.";
                }

        $data['value']=$msg;
        $this->view->setVar(data,$data);




	 }



     public function unsubscribeAction()
     {
        $username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
        $user_email=$con->email;
        $record=Newsletter::find("email="."'".$user_email."'");
        if ($record != false) {
            if ($record->delete() == false) {
                echo "Sorry, we can't delete the robot right now: \n";
                foreach ($record->getMessages() as $message) {
                    echo $message, "\n";
                }
            } else {
                $record->save();
                echo "You are successfully unsubscribed Now!";
            }
        }


     }














}