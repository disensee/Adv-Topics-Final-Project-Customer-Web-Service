<?php
include_once('DataAccess.inc.php');
include_once(__DIR__ . "/../models/Customer.inc.php");

class CustomerDataAccess extends DataAccess{


	// Constructor

	function __construct($link){

        parent::__construct($link);
        
	}

	/**
	* 'Cleans' the data in a Customer object to prevents SQL injection attacks
	* @param {customer}	A Customer model object
	* @return {customer} A new instance of Customer object with clean data in it
	*/
	function cleanDataGoingIntoDB($customer){

		if($customer instanceOf Customer){
			$cleanCustomer = new Customer();
			$cleanCustomer->customerId = mysqli_real_escape_string($this->link, $customer->customerId);
			$cleanCustomer->firstName = mysqli_real_escape_string($this->link, $customer->firstName);
            $cleanCustomer->lastName = mysqli_real_escape_string($this->link, $customer->lastName);
            $cleanCustomer->address = mysqli_real_escape_string($this->link, $customer->address);
            $cleanCustomer->city = mysqli_real_escape_string($this->link, $customer->city);
            $cleanCustomer->state = mysqli_real_escape_string($this->link, $customer->state);
            $cleanCustomer->zip = mysqli_real_escape_string($this->link, $customer->zip);
			$cleanCustomer->email = mysqli_real_escape_string($this->link, $customer->email);

			return $cleanCustomer;
		}else{
			$cleanParam = mysqli_real_escape_string($this->link, $customer);
			return $cleanParam;
		}
    }

    /**
	* 'Cleans' the data in a row from the database (a row should be an associative array)
	* @param {array}	An associative array with key value pairs for each column in the table
	* @return {array} 	A new associative array with clean data in it
	*/
	function cleanDataComingFromDB($row){
		$cleanRow = [];
		$cleanRow['customerId'] = htmlentities($row['customerId']);
        $cleanRow['firstName'] = htmlentities($row['firstName']);
        $cleanRow['lastName'] = htmlentities($row['lastName']);
        $cleanRow['address'] = htmlentities($row['address']);
        $cleanRow['city'] = htmlentities($row['city']);
        $cleanRow['state'] = htmlentities($row['state']);
        $cleanRow['zip'] = htmlentities($row['zip']);
		$cleanRow['email'] = htmlentities($row['email']);

		return $cleanRow;
    }
    
    /**
	* Gets all customers from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of Customer objects
	*/
	function getAll($args = []){
		$qStr = "SELECT customerId, firstName, lastName, address, city, state, zip, email FROM customers";
		//die($qStr);

		//Many people run queries like this. Shows error messages to users. 
		//$result = mysqli_query($this->link, $qStr) or die(mysqli_error($this->link));
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allCustomers = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$customer = new Customer($cleanRow);

				$allCustomers[] = $customer;
			}
		}
		return $allCustomers;
    }
    
    /**
	* Gets a Customer from the database by their id
	* @param {number} 	 The id of the customer to get from a row in the database
	* @return {Customer} Returns an instance of a Customer model object
	*/
	function getById($customerId){
		$cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT customerId, firstName, lastName, address, city, state, zip, email FROM customers WHERE customerId = $cleanCustomerId";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$customer = new Customer($cleanRow);
			return $customer;
		}

		return false;
    }
    
    /**
	* Inserts a customer into a table in the database
	* @param {Customer}	The Customer object to be inserted
	* @return {Customer}	Returns the same Customer object, but with the customerId property set (the customerId is assigned by the database)
	*/
	function insert($customer){
		$cleanCustomer = $this->cleanDataGoingIntoDB($customer);
		$qStr = "INSERT INTO customers (firstName, lastName, address, city, state, zip, email) VALUES (
			'{$cleanCustomer->firstName}',
			'{$cleanCustomer->lastName}',
            '{$cleanCustomer->address}',
            '{$cleanCustomer->city}',
            '{$cleanCustomer->state}',
            '{$cleanCustomer->zip}',
			'{$cleanCustomer->email}'
		)";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			$cleanCustomer->customerId = mysqli_insert_id($this->link);
		}else{
			$this->handleError("Unable to insert customer");
		}

		return $cleanCustomer;
    }
    
    /**
	* Updates a Customer in the database
	* @param {Customer}	 The Customer model object to be updated
	* @return {Customer} Returns the same Customer model object that was passed in as the param
	*/
	function update($customer){
		$cleanCustomer = $this->cleanDataGoingIntoDB($customer);
		$qStr = "UPDATE customers SET 
				firstName = '{$cleanCustomer->firstName}',
				lastName = '{$cleanCustomer->lastName}',
                address = '{$cleanCustomer->address}',
                city = '{$cleanCustomer->city}',
                state = '{$cleanCustomer->state}',
                zip = '{$cleanCustomer->zip}',
				email = '{$cleanCustomer->email}'
				WHERE customerId = '{$cleanCustomer->customerId}'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		return $cleanCustomer;
    }
    
    /**
	* Deletes a Customer from a table in the database
	* @param {number} 	The id of the customer to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	function delete($customerId){
		$cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "DELETE FROM customers WHERE customerId = $cleanCustomerId";
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_affected_rows($this->link) == 1){
			return true;
        }
        
        return false;
        
	}
}