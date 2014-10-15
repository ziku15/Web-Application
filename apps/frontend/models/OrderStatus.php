<?php

namespace Biz_mela\Frontend\Models;

class OrderStatus extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $status_code;

    /**
     *
     * @var string
     */
    public $status_name;

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
