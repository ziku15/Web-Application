<?php

namespace Biz_mela\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

class Newsletter extends \Phalcon\Mvc\Model

{

	 public $id;
	 
	 
	 public $email;
	 
	 
	 public $shop_id;
	 
	 
	 public $status;
	 
	 
	 public $updated_at;
	 
	 
	 public $created_at;
	 
	 
	 
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