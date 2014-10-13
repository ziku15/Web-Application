<?php

class UserBankInfo extends \Phalcon\Mvc\Model
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
    public $bank_name;

    /**
     *
     * @var string
     */
    public $branch_name;

    /**
     *
     * @var string
     */
    public $account_holder_name;

    /**
     *
     * @var string
     */
    public $swift_code;

    /**
     *
     * @var string
     */
    public $account_no;

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
