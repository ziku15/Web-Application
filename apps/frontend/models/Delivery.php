<?php

namespace Biz_mela\Frontend\Models;

class Delivery extends \Phalcon\Mvc\Model
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
    public $product_id;

    /**
     *
     * @var integer
     */
    public $delivery_area_id;

    /**
     *
     * @var integer
     */
    public $delivery_method_id;

    /**
     *
     * @var string
     */
    public $remark;

    /**
     *
     * @var string
     */
    public $condition;

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
