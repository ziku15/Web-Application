<?php

namespace Biz_mela\Models;

class UserPoint extends \Phalcon\Mvc\Model
{
	public $id;
	
	public $user_id;
	
	public $point;
	
	public $point_type;
	
	public $status;
	
	public $created_at;
	
	public $updated_at;


}