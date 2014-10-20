<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;
use Biz_mela\Models\ProductWishlist as ProductWishlist;
use Biz_mela\Models\OrderDetails as OrderDetails;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Phalcon\Mvc\View;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        // $a=UserMaster::find();
        // var_dump($a);
        // $this->view->disable();

        

        $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->orderBy('Biz_mela\Models\ProductMaster.created_at desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();

        $this->view->setVar(newResult,$newResult);


        $hotResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->where('is_hot = 1')
        ->getQuery()
        ->execute();
        $this->view->setVar(hotResult,$hotResult);

        $bestResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture, o.product_id')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->join('Biz_mela\Models\OrderDetails', 'o.product_id = Biz_mela\Models\ProductMaster.id', 'o','right')
                  ->groupBy('o.product_id')
                  ->orderBy('count(o.product_id) desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();
        $this->view->setVar(bestResult,$bestResult);

        $bottomResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->limit(16)
        ->getQuery()
        ->execute();
        $this->view->setVar(bottomResult,$bottomResult);

        

    }

    public function wishlistAction()
    {
      $this->view->disable();

      $wish = $this->request->getPost('data1');

      //echo $wish;
      
      $username = $this->session->get('auth');
      $con=UserMaster::findFirst("username="."'".$username['name']."'");
      $user_id=$con->id;

      //echo $user_id;
      /*
      // Inserting using placeholders
     $phql = "INSERT INTO 'Biz_mela\Models\ProductWishlist' (product_id, user_id, status) "
      . "VALUES (:product_id:, :user_id:, :status:)";
     $manager->executeQuery($phql,
     array(
        'product_id' => $wish,
        'user_id' => $user_id,
        'status'     => 1,
         )
     );
     */
      $product_data = ProductMaster::findFirst("id="."'".$wish."'");
      $product_name= $product_data->product_name;

     

     //$timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))); 
     //$timestamp = date('Y-m-d H:i:s'); 

     $Result = ProductWishlist::find("user_id="."'".$user_id."'and product_id="."'".$wish."'");

     $count = count($Result);

     if($count>0){

      //echo $product_name." exists in your wish list";

      echo '<font color="red">'.$product_name." exists in your wish list".'</font>';



     }

     else {
      // Specifying columns to insert
     $phql = "INSERT INTO Biz_mela\Models\ProductWishlist (product_id, user_id, status, updated_at, created_at) "
      . "VALUES ($wish, $user_id, 1, now(), now())";
     $this->modelsManager->executeQuery($phql);

     //echo $product_name." is added to your wish list";

     echo '<font color="green">'.$product_name." is added to your wish list".'</font>';

     }
    
    /*
     $Result = $this->modelsManager->createBuilder()
        ->from('ProductWishlist')
        ->where('user_id = $user_id')
        //->andWhere('product_id = $wish')
        ->getQuery()
        ->execute();
    */

     //$Result = ProductWishlist::find("user_id="."'".$user_id."'and product_id="."'".$wish."'");

     //$count = count($Result);

     //echo $count;

     //$previous_email = Admin::find('email=' . "'" . $email_add . "'" . ' and is_del=0');




    }

    public function addtocartAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      
   
      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ; 


      // if(isset($_COOKIE[$product_id])){

      //   echo "This product already exists in your cart";
      // }

      // else{
       
        //print_r(count($_COOKIE['product']));
        //$array = array("person1" => array("name" => "Ted"));

        //$date_of_expiry = time() - 60 ;
        //setcookie('product', $product_id, $date_of_expiry, "/" );
      //setcookie( 'product', '', $date_of_expiry, "/" );


      
        $cookie_count = unserialize($_COOKIE['product']);
        if(!in_array($product_id, $cookie_count)){
            $cookie_count[] = $product_id;
            $value = serialize($cookie_count);

            setcookie( 'product', $value, $date_of_expiry, "/" );
        }
        //print_r($cookie_count);
        echo count($cookie_count);

       


        
        
        //print_r($cookie_count);

        
        



      // }

      //setcookie( $product_id, $product_id, $date_of_expiry, "/" );

      

      
      // $date_of_expiry = time() - 60 ;
      // setcookie('product', $product_id, $date_of_expiry, "/" );

      

      //echo $product_id;

      //echo '<br>';

      //echo count($_COOKIE);


    }

    public function displayAction(){
      $this->view->disable();

      print_r($_COOKIE);

      $cookie_count = unserialize($_COOKIE['product']);

      echo '<br>';

      foreach ($cookie_count as $entry) {
        # code...
        echo $entry;
        echo '<br>';
      }

      print_r($cookie_count);
      /*

      $product_data = ProductMaster::findFirst("id="."'".$wish."'");

      foreach ($cookie_count as $entry) {
        # code...
        $product_data = ProductMaster::findFirst("id="."'".$entry."'");
        echo $product_data->product_name;
      }*/

      $cResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->inWhere('Biz_mela\Models\ProductMaster.id', $cookie_count)
        ->getQuery()
        ->execute();

      //print_r($cResult);

      echo count($cResult);

      foreach ($cResult as $item) {
        # code...
        echo $item->product_name;
        echo $item->price;
        echo $item->picture;
        echo '<br>';
      }




    }

    public function shoppingcartAction(){
      //$this->view->disable();

      $this->view->disableLevel(View::LEVEL_LAYOUT);

      $cookie_count = unserialize($_COOKIE['product']);

      $cResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->inWhere('Biz_mela\Models\ProductMaster.id', $cookie_count)
        ->getQuery()
        ->execute();

      $this->view->setVar(cResult,$cResult);


    }

    public function productdetailsAction($value){
      $this->view->disableLevel(View::LEVEL_LAYOUT);
      //$this->view->disable();
      //echo $value;
      
      $detailResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture, s.shop_name')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->leftJoin('Biz_mela\Models\ShopMaster', 's.id = Biz_mela\Models\ProductMaster.shop_id', 's')
        ->where('Biz_mela\Models\ProductMaster.id = :name:', array('name' => $value))
        ->getQuery()
        ->execute();
      

        //$a=$detailResult::findFirst();

        //echo $a->product_name;

        //foreach ($detailResult as $key) {
          # code...
          //echo $key->shop_name;
        //}
        $data['shop_name'] = $detailResult[0]->shop_name;
        $data['product_name'] = $detailResult[0]->product_name;
        $data['price'] = $detailResult[0]->price;
        $data['description'] = $detailResult[0]->product_description;
        $data['picture'] = $detailResult[0]->picture;
        
        $this->view->setVar(data,$data);

        //echo $detailResult[0]->shop_name;
        //echo $detailResult[0]->product_name;
         //echo $detailResult[0]->product_description;
          //echo $detailResult[0]->price;
          // echo $detailResult[0]->picture;

      

      


    }



}

