<?php

namespace Biz_mela\Models;

class ShopMaster extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var string
     */
    public $shop_name;

    /**
     *
     * @var string
     */
    public $banner;

    /**
     *
     * @var string
     */
    public $shop_image;

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

    /**
     *
     * @var integer
     */
    public $is_del;

}
