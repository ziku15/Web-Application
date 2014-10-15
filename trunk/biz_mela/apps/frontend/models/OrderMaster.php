<?php

namespace Biz_mela\Frontend\Models;

class OrderMaster extends \Phalcon\Mvc\Model
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
    public $order_no;

    /**
     *
     * @var string
     */
    public $total;

    /**
     *
     * @var string
     */
    public $subtotal;

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
    public $delivery_id;

    /**
     *
     * @var string
     */
    public $customer_note;

    /**
     *
     * @var string
     */
    public $customer_ip;

}
