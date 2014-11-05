<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\Newsletter as Newsletter;
use Biz_mela\Models\ShopMaster as ShopMaster;

use Phalcon\Paginator\Adapter\Model as Paginator;


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
    	 	   $user_id = $this->session->get('auth')['id'];
            $user=UserMaster::findFirst("id="."'".$user_id."'");
            $email=$user->email;

            
            
            $subscribeTo = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\Newsletter')
                  ->columns('Biz_mela\Models\Newsletter.shop_id,Biz_mela\Models\Newsletter.id,Biz_mela\Models\Newsletter.status,
                    s.shop_name , s.shop_image')
                  ->leftJoin('Biz_mela\Models\ShopMaster', 's.id = Biz_mela\Models\Newsletter.shop_id', 's')
                  
                  ->orderBy('Biz_mela\Models\Newsletter.shop_id ')
                  ->where('Biz_mela\Models\Newsletter.email = :name:', array('name' => $email))
                  ->getQuery()
                  ->execute();

       

            //$newresult = $this->modelsManager->executeQuery($phql);

            $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $subscribeTo,
                "limit" => 5,
                "page" => $numberPage
            ));
            
            $page['Subscription'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);



            $user_id = $this->session->get('auth')['id'];
            $subscribedBy= $this->modelsManager->createBuilder()

            ->from('Biz_mela\Models\Newsletter')
                  ->columns('Biz_mela\Models\Newsletter.shop_id,Biz_mela\Models\Newsletter.email,Biz_mela\Models\Newsletter.status,s.shop_name ,
                   s.shop_image')
                  ->leftJoin('Biz_mela\Models\ShopMaster', 's.id = Biz_mela\Models\Newsletter.shop_id', 's')
                  
                  ->orderBy('Biz_mela\Models\Newsletter.shop_id ')
                  ->where('s.user_id = :name:', array('name' => $user_id))
                  ->getQuery()
                  ->execute();

            $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $subscribedBy,
                "limit" => 5,
                "page" => $numberPage
            ));
            
            $page['News'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);




	 }



     public function unsubscribeAction($value='')
     {
        


      $dump = Newsletter::findFirst('id = "' . $value . '"');
      
      if ($dump != false) {
          if ($dump->delete() == false) {
                echo "Sorry, we can't delete the robot right now: \n";
                foreach ($dump->getMessages() as $message) {
                    echo $message, "\n";
                }
          } else {
              echo "Unsubscription Successful!";
              return $this->response->redirect('subscribe/subscription/');
          }
      }



     }














}