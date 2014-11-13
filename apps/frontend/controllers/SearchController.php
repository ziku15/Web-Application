<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;

class SearchController extends ControllerBase
{

	public function initialize()
	{

	}

	public function indexAction()
    {
    
    }

    public function searchAction()
    {
    	if ($this->request->isPost()) {
    		$key = $this->request->getPost('key');


            $phql = ("SELECT Biz_mela\Models\ProductMaster.product_name,Biz_mela\Models\ProductMaster.id,Biz_mela\Models\ProductMaster.product_description,Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.discount,Biz_mela\Models\ProductMaster.in_stock, Biz_mela\Models\ProductMaster.status,Biz_mela\Models\ProductImage.picture
            	
                FROM Biz_mela\Models\ProductMaster,Biz_mela\Models\ProductImage
                
                
                where Biz_mela\Models\ProductMaster.product_name like '%$key%'
                and Biz_mela\Models\ProductMaster.id=Biz_mela\Models\ProductImage.product_id
                ORDER BY Biz_mela\Models\ProductMaster.id desc
                limit 12
                
                

                ");

            $searchresult = $this->modelsManager->executeQuery($phql);

            
            $data['value']=$searchresult;
            $this->view->setVar(data,$data);
        
    	}

    }



}