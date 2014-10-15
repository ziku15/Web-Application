<?php

namespace Biz_mela\Frontend\Models;

class ProductImage extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $picture;

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
