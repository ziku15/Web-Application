<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;
use Biz_mela\Models\OrderDetails as OrderDetails;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        // $a=UserMaster::find();
        // var_dump($a);
        // $this->view->disable();

        

        $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->orderBy('Biz_mela\Models\ProductMaster.created_at desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();

        $this->view->setVar(newResult,$newResult);


        $hotResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->where('is_hot = 1')
        ->getQuery()
        ->execute();
        $this->view->setVar(hotResult,$hotResult);

        $bestResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture, o.product_id')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->join('Biz_mela\Models\OrderDetails', 'o.product_id = Biz_mela\Models\ProductMaster.id', 'o','right')
                  ->groupBy('o.product_id')
                  ->orderBy('count(o.product_id) desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();
        $this->view->setVar(bestResult,$bestResult);

        

    }

}

