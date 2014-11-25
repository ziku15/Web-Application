<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;
use Biz_mela\Models\ProductWishlist as ProductWishlist;
use Biz_mela\Models\OrderDetails as OrderDetails;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\ProductReview as ProductReview;
use Biz_mela\Models\ProductRating as ProductRating;


use Biz_mela\Models\ProductQuery as ProductQuery;
use Phalcon\Paginator\Adapter\Model as Paginator;
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
                  ->limit(8)
                  ->getQuery()
                  ->execute();

        $this->view->setVar(newResult,$newResult);


        $hotResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->where('is_hot = 1')
                  ->limit(8)
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
                  ->limit(8)
                  ->getQuery()
                  ->execute();
        $this->view->setVar(bestResult,$bestResult);

        $bottomResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->join('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        
        ->limit(16)
        ->getQuery()

        ->execute();
        $this->view->setVar(bottomResult,$bottomResult);

        

    }



    public function testAction()
    {
      # code...
      $this->view->disable();
      echo 'ok';

    }

    public function wishlistAction()
    {
      $this->view->disable();

      $wish = $this->request->getPost('data1');

      //echo $wish;
      
      $username = $this->session->get('auth');
      $con=UserMaster::findFirst("username="."'".$username['name']."'");
      $user_id=$con->id;

      
      $product_data = ProductMaster::findFirst("id="."'".$wish."'");
      $product_name= $product_data->product_name;

     

     //$timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))); 
     //$timestamp = date('Y-m-d H:i:s'); 

     $Result = ProductWishlist::find("user_id="."'".$user_id."'and product_id="."'".$wish."'");

     $count = count($Result);

     $auth = $this->session->get('auth');
     if(!$auth){

      echo "1";
     }

     else if($count>0){

      //echo $product_name." exists in your wish list";

      //echo '<font color="red">'.$product_name." exists in your wish list".'</font>';
      echo "2";

      //return "123";



     }

     else {
      // Specifying columns to insert
     $phql = "INSERT INTO Biz_mela\Models\ProductWishlist (product_id, user_id, status, updated_at, created_at) "
      . "VALUES ($wish, $user_id, 1, now(), now())";
     $this->modelsManager->executeQuery($phql);

     //echo $product_name." is added to your wish list";

     //echo '<font color="green">'.$product_name." is added to your wish list".'</font>';
     echo "3";

     //return "456";

     }
    
    
    }

    public function addtocartAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      
   
      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ; 

      if (!isset($_COOKIE['product'])) {
        # code...
      $cookie_array[$product_id] = intval(1);
      setcookie('product', serialize($cookie_array), $date_of_expiry, "/" );
      echo count($cookie_array);
      }

      else{

      $cookie_array = unserialize($_COOKIE['product']);

      if (array_key_exists($product_id, $cookie_array))
      {
       $value = $cookie_array[$product_id];
       $cookie_array[$product_id] = intval($value) + 1;
       setcookie('product', serialize($cookie_array), $date_of_expiry, "/" );

      }

      else{
      $cookie_array[$product_id] = intval(1);
      setcookie('product', serialize($cookie_array), $date_of_expiry, "/" );
      echo count($cookie_array);
      }
    }
      

      /*
        $cookie_count = unserialize($_COOKIE['product']);
        if(!in_array($product_id, $cookie_count)){
            $cookie_count[] = $product_id;
            $value = serialize($cookie_count);

            setcookie( 'product', $value, $date_of_expiry, "/" );
            echo count($cookie_count);
        }
      */

    }

    public function addtocart2Action(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      $quantity = $this->request->getPost('data2');

      
   
      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ; 

      if (!isset($_COOKIE['product'])) {
        # code...
      $cookie_array[$product_id] = intval(1);
      setcookie('product', serialize($cookie_array), $date_of_expiry, "/" );
      echo count($cookie_array);
      }

      else{

      $cookie_array = unserialize($_COOKIE['product']);

      if (array_key_exists($product_id, $cookie_array))
      {
       $value = $cookie_array[$product_id];
       $cookie_array[$product_id] = intval($value) + intval($quantity);
       setcookie('product', serialize($cookie_array), $date_of_expiry, "/" );

      }

      else{
      $cookie_array[$product_id] = intval($quantity);
      setcookie('product', serialize($cookie_array), $date_of_expiry, "/" );
      echo count($cookie_array);
      }
    }
      

      /*
        $cookie_count = unserialize($_COOKIE['product']);
        if(!in_array($product_id, $cookie_count)){
            $cookie_count[] = $product_id;
            $value = serialize($cookie_count);

            setcookie( 'product', $value, $date_of_expiry, "/" );
            echo count($cookie_count);
        }
      */

    }

    public function updateCookieAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ; 

      $cookie_array = unserialize($_COOKIE['product']);
        //$cookie_count = array_diff($cookie_count, array($product_id));


      
      unset($cookie_array[$product_id]);
       


        //$value = serialize($cookie_count);

      setcookie( 'product', serialize($cookie_array), $date_of_expiry, "/" );
      echo count($cookie_array);

    }

    public function updateQuantCookieAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('productID');
      $quantity = $this->request->getPost('Quant');

      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ;

      $cookie_array = unserialize($_COOKIE['product']);

      $cookie_array[$product_id] = $quantity;
      setcookie('product', serialize($cookie_array), $date_of_expiry, "/" );

      //new code

      $cookie_array = unserialize($_COOKIE['product']);
      $mult = intval(0);
      foreach ($cookie_array as $key => $value) {
       $unitPrice = ProductMaster::findFirst("id="."'".$key."'")->price;
       if($key!=$product_id)
        $mult += intval($unitPrice) * intval($value);  
       else
        $mult += intval($unitPrice) * intval($quantity);


      } 

      echo $mult;

    }

    public function debugTotalAction(){
      $this->view->disable();

      $cookie_array = unserialize($_COOKIE['product']);
      $mult = intval(0);
      foreach ($cookie_array as $key => $value) {
       $unitPrice = ProductMaster::findFirst("id="."'".$key."'")->price;
       $mult += intval($unitPrice) * intval($value);  
      } 

      echo $mult;

    }

    public function calculateTotalAction(){
      $this->view->disable();
      //$unitPrice = ProductMaster::findFirst("id="."'".$wish."'");
      $cookie_array = unserialize($_COOKIE['product']);
      $mult = intval(0);
      foreach ($cookie_array as $key => $value) {
       $unitPrice = ProductMaster::findFirst("id="."'".$key."'")->price;
       $mult += intval($unitPrice) * intval($value);  
      } 

      echo $mult;
    }

    public function displayAction(){
      $this->view->disable();

      //print_r($_COOKIE);

      //$cookie_array = $_COOKIE['product'];
      $cookie_array = unserialize($_COOKIE['product']);

      echo count($cookie_array);

      $keysHolder = array_keys($cookie_array);

      echo '<br>';

      foreach ($keysHolder as $entry) {
        # code...
        echo $entry;
        echo '<br>';
        echo $cookie_array[$entry];
        echo '<br>';
      }

      //print_r($cookie_array);
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
        ->inWhere('Biz_mela\Models\ProductMaster.id', $keysHolder)
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

    public function clearAction(){
      $this->view->disable();
      $date_of_expiry = time() - 60 ;
        
      setcookie( 'product', '', $date_of_expiry, "/" );


    }

    public function shoppingcartAction(){
      //$this->view->disable();

      $this->view->disableLevel(View::LEVEL_LAYOUT);

      //$cookie_count = unserialize($_COOKIE['product']);
      if (isset($_COOKIE['product']))
      $cookie_array = unserialize($_COOKIE['product']);

      //echo count($cookie_array);

      if(count($cookie_array) == 0){
        return $this->dispatcher->forward(
            array(
             
              'action' => 'index'
              
              )
            );
      }

      $keysHolder = array_keys($cookie_array);

      //$numberPage = $this->request->getQuery("page", "int", 1);

      $cResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->inWhere('Biz_mela\Models\ProductMaster.id', $keysHolder)
        ->getQuery()
        ->execute();
      $shop['cResult'] = $cResult;

        /*$paginator = new Paginator(array(
            "data" => $cResult,
            "limit" => 3,
            "page" => $numberPage
        ));

        $page['Product'] = $paginator->getPaginate();
        $page['value'] = $value;*/
        $this->view->setVar(shop,$shop);

      $cookie_array = unserialize($_COOKIE['product']);
      $mult = intval(0);
      foreach ($cookie_array as $key => $value) {
       $unitPrice = ProductMaster::findFirst("id="."'".$key."'")->price;
       $mult += intval($unitPrice) * intval($value);  
      } 

      $data['mult'] = $mult;
      $this->view->setVar(data,$data);


    }



    public function productdetailsAction($value){
      
      $user_id = $this->session->get('auth')['id'];
      $this->view->disableLevel(View::LEVEL_LAYOUT);
      //$this->view->disable();
      //echo $value;
      $my_wish = ProductWishlist::find("user_id='$user_id' AND product_id='$value'");
      $check=count($my_wish);
      $data['color']=$check;
      
      $detailResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, Biz_mela\Models\ProductMaster.discount, Biz_mela\Models\ProductMaster.cat_id, p.picture, s.shop_name, s.id as shopID')
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
        $data['discount'] = $detailResult[0]->discount;
        $data['category'] = $detailResult[0]->cat_id;
        $data['product_id'] = $detailResult[0]->id;
        $data['shop_id'] = $detailResult[0]->shopID;

        $cat = $detailResult[0]->cat_id;

        $relatedProducts = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->where('Biz_mela\Models\ProductMaster.cat_id = :name:', array('name' => $cat))
                  ->andWhere('Biz_mela\Models\ProductMaster.id != :name1:', array('name1' => $value))
                  ->limit(6)
                  ->getQuery()
                  ->execute();

        $data['related'] = $relatedProducts;

        //Quantity to display in the textbox

        if (isset($_COOKIE['product'])){
          $cookie_array = unserialize($_COOKIE['product']);

          if (array_key_exists($value, $cookie_array)){
            $quantity = $cookie_array[$value];
            $data['quantity'] = $quantity;

          }

          else
            $data['quantity'] = 1;

        }

        $cookie_array = unserialize($_COOKIE['product']);
        if (array_key_exists($value, $cookie_array)) {
          $data['cart']=1;
          } else if (!array_key_exists($value, $cookie_array)) {
            $data['cart']=2;
          }


        $allcomments = $this->modelsManager->createBuilder()
              ->from('Biz_mela\Models\ProductReview')
              ->columns('Biz_mela\Models\ProductReview.text, Biz_mela\Models\ProductReview.user_id, Biz_mela\Models\ProductReview.created_at')
              ->where('Biz_mela\Models\ProductReview.product_id = :name:', array('name' => $value))
              ->orderBy('Biz_mela\Models\ProductReview.created_at desc')
              ->getQuery()
              ->execute();
        $temp = array();

        foreach ($allcomments as $info) {
            $user=UserMaster::findFirst("id="."'".$info->user_id."'");
            $temp[$info->user_id]=$user->username;

            
        }

        

        $data['comments'] = $allcomments;


         $allratings = $this->modelsManager->createBuilder()
             ->from('Biz_mela\Models\ProductRating')
             ->columns('Biz_mela\Models\ProductRating.rating, Biz_mela\Models\ProductRating.user_id as uid, Biz_mela\Models\ProductRating.created_at')
             ->where('Biz_mela\Models\ProductRating.product_id = :name1:', array('name1' => $value))
             ->orderBy('Biz_mela\Models\ProductRating.created_at desc')
             ->getQuery()
             ->execute();

        //$total=intval(count($allratings));
        $total=intval($allratings->count());

       
        $data['average']=$avg;
        $data['total']=$total;

        


        $this->view->setVar(temp,$temp);
        $this->view->setVar(data,$data);


        //echo $detailResult[0]->shop_name;
        //echo $detailResult[0]->product_name;
         //echo $detailResult[0]->product_description;
          //echo $detailResult[0]->price;
          // echo $detailResult[0]->picture;

    }


    public function askAction($value){
        
          $request = $this->request;
          if ($request->isPost()) {

          $email = $request->getPost('email');
          $msg = $request->getPost('message');


        


           $query = new ProductQuery();
           $query->product_id=$value;
           $query->email=$email;
           $query->text=$msg;
           $query->created_at = date("Y-m-d h:i:sa");
           $query->updated_at = date("Y-m-d h:i:sa");
           $query->status =1;

            if ($query->save() == True) {
                    //$this->flash->success('You have successfully subscribed to our newsletter');
                  //$msg="You have successfully subscribed to our newsletter";
              return $this->dispatcher->forward(
                  array(
                    'controller' => 'index',
                    'action' => 'productdetails',
                    'param' => $value
                    )
                  );
                   
                  }


       }

    }

    public function commentAction($value){
       $this->view->disable();
      $this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
           echo "1";
        }

        else {
          $request = $this->request;
          if ($request->isPost()) {

            $text = $request->getPost('comment');

            
            $userid = $this->session->get('auth')['id'];
            $review=new ProductReview();
            $review->product_id=$value;
            $review->user_id=$userid;
            $review->text=$text;
            $review->status=1;

            $review->created_at = date("Y-m-d h:i:s");
            $review->updated_at = date('Y-m-d h:i:s');
            $review->save();

            if ($review->save() == True) {
                        //$this->flash->success('You have successfully subscribed to our newsletter');
                      //$msg="You have successfully subscribed to our newsletter";
              echo "2"; 
            } else {
              echo "3";
            }


          } else {
            echo "4";
          }


        }

    }

       public function ratingAction($value){

        $this->view->disable();
        $this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
           echo "1";
        }

         else{

           $request = $this->request;

           if ($request->isPost()) {

             $rating = $request->getPost('id');

             $userid = $this->session->get('auth')['id'];

             $prev=ProductRating::findFirst("user_id='$userid' AND product_id='$value'");

             if ($prev){
              
              $prev->rating=$rating;
              $prev->save();

              echo "2";



             } else if (!$prev) {
              $rate=new ProductRating();

              $rate->product_id=$value;
              $rate->user_id=$userid;
              $rate->rating=$rating;
              $rate->status=1;

              $rate->created_at = date("Y-m-d h:i:s");
              $rate->updated_at = date('Y-m-d h:i:s');
              $rate->save();

              if ($rate->save() == True) {
                        
              echo "3"; 
              } else {
              echo "4";
              }
             }

           }

           else {
            echo "5";
          }

         }

       }




    public function allcommentsAction(){

      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      $result = $this->modelsManager->createBuilder()

        ->from('Biz_mela\Models\ProductReview')
         ->columns('Biz_mela\Models\ProductReview.text,Biz_mela\Models\ProductReview.status')
        ->where('Biz_mela\Models\ProductReview.product_id = :name:', array('name' => $product_id))
        ->getQuery()
        ->execute();

        echo "1";

    }

    public function checkStockAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      $Result = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->where('Biz_mela\Models\ProductMaster.id = :name:', array('name' => $product_id))
        ->getQuery()
        ->execute();

      echo $Result[0]->in_stock;

    }



}

