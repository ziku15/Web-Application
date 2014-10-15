<?php

namespace Biz_mela\Frontend\Models;

class OrderUserBillingInfo extends \Phalcon\Mvc\Model
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
    public $order_master_id;

    /**
     *
     * @var string
     */
    public $billing_address;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $zip_code;

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
     * @var string
     */
    public $contact_no;

}
