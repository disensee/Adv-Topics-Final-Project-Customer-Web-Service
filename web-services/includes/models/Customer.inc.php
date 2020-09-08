<?php
include_once("Model.inc.php");

class Customer extends Model{

    //instance variables
    public $customerId;
    public $firstName;
    public $lastName;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $email;

    //Contructor
    public function __construct($args = []){
        $this->customerId = $args['customerId'] ?? 0;
        $this ->firstName = $args['firstName'] ?? "";
        $this->lastName = $args['lastName'] ?? "";
        $this->address = $args['address'] ?? "";
        $this->city = $args['city'] ?? "";
        $this->state = $args['state'] ?? "";
        $this->zip = $args['zip'] ?? "";
        $this->email = $args['email'] ?? "";
    }

    /**
	* Validates the state of a Contact object. Returns true if it is valid, false otherwise
	* @return {boolean}
    */
    public function isValid(){
        //firstName must not be empty
		if(empty($this->firstName)){
			return false;
		}
		// lastName must not be empty
		if(empty($this->lastName)){
			return false;
        }
        //address must not be empty
        if(empty($this->address)){
			return false;
        }
        //city must not be empty
        if(empty($this->city)){
			return false;
        }
        //state must not be empty
        if(empty($this->state)){
			return false;
        }
        //zip must not be empty
        if(empty($this->zip)){
			return false;
        }
        //zip must be a 5 digit number
        if (!preg_match('/^[0-9]{5}$/', $this->zip)){
            return false;
        }
        //email must not be empty
        if(empty($this->email)){
            return false;
        }
        // email must be a valid email address
		if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
			return false;
        }
        return true;
    }

}
?>