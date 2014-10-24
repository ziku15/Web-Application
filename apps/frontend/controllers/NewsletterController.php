<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\Newsletter as Newsletter;
use Biz_mela\Models\ShopMaster as ShopMaster;


use Phalcon\Tag as Tag,
Phalcon\Forms\Element\Text,
Phalcon\Validation\Validator\PresenceOf,

Phalcon\Forms\Form;


class NewsletterController extends ControllerBase
{

	public function initialize()
    {
		$this->view->setTemplateAfter('index');
        
        
    }
	
	 public function indexAction()
    {
		
	}


	 public function subscriptionAction()
	 {



		$request = $this->request;
        if ($request->isPost()) {
        	$email = $request->getPost('email');
        	$previous_email = Newsletter::find('email=' . "'" . $email . "'" );
          $msg="not set";
        	if ($previous_email->count() > 0) {
                    $msg=" You are already subscribed to our newsletter";
                   
                } else {

              $subs=UserMaster::findFirst("email="."'".$email."'");
              $userid=$subs->id;
              $shop=ShopMaster::findFirst("user_id="."'".$userid."'");
              $data=$shop->id;
              $user = new Newsletter();
              $user->email=$email;
              $user->shop_id=$data;
              $user->status=1;
              $user->save();
             

              if ($user->save() == True) {
	                	//$this->flash->success('You have successfully subscribed to our newsletter');
                  $msg="You have successfully subscribed to our newsletter";   	
	                }
                }
                  $data['value']=$msg;

                 $this->view->setVar(data,$data);




       }



     }   




}