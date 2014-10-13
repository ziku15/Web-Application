<?php

class ProductMaster extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $shop_id;

    /**
     *
     * @var integer
     */
    public $is_hot;

    /**
     *
     * @var integer
     */
    public $cat_id;

    /**
     *
     * @var string
     */
    public $sku;

    /**
     *
     * @var string
     */
    public $product_name;

    /**
     *
     * @var string
     */
    public $product_description;

    /**
     *
     * @var string
     */
    public $price;

    /**
     *
     * @var string
     */
    public $discount;

    /**
     *
     * @var string
     */
    public $in_stock;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $uom;

    /**
     *
     * @var integer
     */
    public $minimum_order_level;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $created_at;

}
