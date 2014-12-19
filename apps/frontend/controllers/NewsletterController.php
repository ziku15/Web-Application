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

      $this->view->disable();
      $url= $_SERVER['HTTP_REFERER'];
      $prev=explode("/", $url);
     
      

      if (count($prev)>4){
        if (count($prev)==7){
          $action=$prev[4];
          $method=$prev[5];
          $id=$prev[6];
        }

      elseif(count($prev)==6){
          $action=$prev[4];
          $method=$prev[5];
          $id='';
        }

      elseif(count($prev)==5){
        $action=$prev[4];
        $method='';
        $id='';
        }
      } 

      else {
        $action='';
        $method='';
        $id='';


      }
      
      


      
      $request = $this->request;
        if ($request->isPost()) {
          $email = $request->getPost('news_email');
          //$previous_email = Newsletter::find('email=' . "'" . $email . "'" );

          $previous = Newsletter::find("email="."'".$email."'and shop_id="."'".$id."'");
          $general=  Newsletter::find("email="."'".$email."'and shop_id="."0");
          
          $msg="Input an email address";

          // for subscribed for a particular shop

          if($action=='shop' && $method=='index'){
            if ($previous->count()>0 ) {
                    //$msg=" You are already subscribed ";
                  echo "1";
                   
            }
                else {
                  $user = new Newsletter();
                  $user->email=$email;
                  $user->shop_id=$id;
                  $user->status=1;
                  $user->created_at = date("Y-m-d h:i:s");
                  $user->updated_at = date('Y-m-d h:i:s');
                  $user->save();

                  if ($user->save() == True) {
                    //$this->flash->success('You have successfully subscribed to our newsletter');
                  //$msg="You have successfully subscribed to our newsletter";
                   echo "2"; 
                  }

                  else echo "10";
             
                }

          }

          // for home page,where it is not working

          elseif (($action=='') && ($method=='')) {

                if ($general->count()>0 ) {
                    //$msg=" You are already subscribed to general subscription";
               echo "3";
                   
                } else {
                  $user = new Newsletter();
                  $user->email=$email;
                  $user->shop_id=intval(0);
                  $user->status=1;
                  $user->created_at = date("Y-m-d h:i:s");
                  $user->updated_at = date('Y-m-d h:i:s');
                  $user->save();

                  if ($user->save() == True) {
                    //$this->flash->success('You have successfully subscribed to our newsletter');
                  //$msg="General Subscription Successful";
                   echo "14";  
                  }

                   else echo "11";
             
                }

            
          }

          // general subscription
          else {
            if ($general->count()>0 ) {
                    //$msg=" You are already subscribed to general subscription";
               echo "3";
                   
                } else {
                  $user = new Newsletter();
                  $user->email=$email;
                  $user->shop_id=intval(0);
                  $user->status=1;
                  $user->created_at = date("Y-m-d h:i:s");
                  $user->updated_at = date('Y-m-d h:i:s');
                  $user->save();

                  if ($user->save() == True) {
                    //$this->flash->success('You have successfully subscribed to our newsletter');
                  //$msg="General Subscription Successful";
                   echo "4";  
                  }

                   else echo "11";
             
                }
              }

          

                 //$data['value']=$msg;

                 //$this->view->setVar(data,$data);


       }
    }

  }


		    


  




