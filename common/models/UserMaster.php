<?php

namespace Biz_mela\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

class UserMaster extends \Phalcon\Mvc\Model
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
    public $profile_id;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;
	
	
	public $point;

    /**
     *
     * @var string
     */
    public $about;

    /**
     *
     * @var string
     */
    public $verification_id;

    /**
     *
     * @var string
     */
    public $contact_no;

    /**
     *
     * @var string
     */
    public $invite_code;

    /**
     *
     * @var string
     */
    public $verification_type;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $activation_code;

    /**
     *
     * @var string
     */
    public $reset_code;

    /**
     *
     * @var string
     */
    public $last_login_dt;

    /**
     *
     * @var string
     */
    public $login_ip;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $dob;

    /**
     *
     * @var integer
     */
    public $is_del;

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
	
	public $type;

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

}
