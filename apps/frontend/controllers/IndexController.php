<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Frontend\Models\UserMaster as UserMaster;
use Biz_mela\Frontend\Models\ProductMaster as ProductMaster;
use Biz_mela\Frontend\Models\ProductImage as ProductImage;
use Biz_mela\Frontend\Models\OrderDetails as OrderDetails;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        UserMaster::find();
        

        $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Frontend\Models\ProductMaster')
                  ->columns('Biz_mela\Frontend\Models\ProductMaster.price, Biz_mela\Frontend\Models\ProductMaster.product_name, Biz_mela\Frontend\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Frontend\Models\ProductImage', 'p.product_id = Biz_mela\Frontend\Models\ProductMaster.id', 'p')
                  ->orderBy('Biz_mela\Frontend\Models\ProductMaster.created_at desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();

        $this->view->setVar(newResult,$newResult);


        $hotResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Frontend\Models\ProductMaster')
        ->columns('Biz_mela\Frontend\Models\ProductMaster.price, Biz_mela\Frontend\Models\ProductMaster.product_name, Biz_mela\Frontend\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Frontend\Models\ProductImage', 'p.product_id = Biz_mela\Frontend\Models\ProductMaster.id', 'p')
        ->where('is_hot = 1')
        ->getQuery()
        ->execute();
        $this->view->setVar(hotResult,$hotResult);

        $bestResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Frontend\Models\ProductMaster')
                  ->columns('Biz_mela\Frontend\Models\ProductMaster.price, Biz_mela\Frontend\Models\ProductMaster.product_name, Biz_mela\Frontend\Models\ProductMaster.product_description, p.picture, o.product_id')
                  ->leftJoin('Biz_mela\Frontend\Models\ProductImage', 'p.product_id = Biz_mela\Frontend\Models\ProductMaster.id', 'p')
                  ->join('Biz_mela\Frontend\Models\OrderDetails', 'o.product_id = Biz_mela\Frontend\Models\ProductMaster.id', 'o','right')
                  ->groupBy('o.product_id')
                  ->orderBy('count(o.product_id) desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();
        $this->view->setVar(bestResult,$bestResult);

        

    }

}

