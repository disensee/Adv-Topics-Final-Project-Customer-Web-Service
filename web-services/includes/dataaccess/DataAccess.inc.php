<?php

abstract class DataAccess{

	// INSTANCE VARIABLES
	protected $link;


	// CONSTRUCTOR

	/**
	* Constructor function for this class
	* @param $link 		A preconfigured connection to the database
	*/
	function __construct($link){

		$this->link = $link;
	
	}


	// METHODS

	// we'll call this error handler rather than OR die(mysqli_error) when a query fails
	protected function handleError($msg){
		throw new Exception($msg);
	}
	
	

	/**
	* Removes 'dangerous' html tags and attributes from a string of html.
	* @param {string} $inputHTML 		A string that includes HTML mark up in it.
	* @return {string}					The sanitized HTML string
	*/
	function sanitizeHtml($inputHTML){
	       
	    // we'll allow these tags, but no others (we are white-listing)
	    $allowed_tags = array('<sub>','<sup>','<div>','<span>','<h1>','<h2>','<br>','<h3>','<h4>','<h5>','<h6>','<h7>','<i>','<b>','<a>','<ul>','<ol>','<em>','<li>','<pre>','<hr>','<blockquote>','<p>','<img>','<strong>','<code>');

	    //replace dangerous attributes...
	    $bad_attributes = array('onerror','onmousemove','onmouseout','onmouseover','onkeypress','onkeydown','onkeyup','onclick','onchange','onload','javascript:');
	    $inputHTML = str_replace($bad_attributes,"x",$inputHTML);
	   
	    $allowed_tags = implode('',$allowed_tags);
	    $inputHTML = strip_tags($inputHTML, $allowed_tags);

	    return $inputHTML;

	}

	/**
	* Converts a date string into the format required by MySQL
	* @param $dateStr {string}		A string that can be parsed into a Date object and then
	* 								formatted for inserting into a MySQL database (Y-m-d)
	*/
	function convertDateForMySQL($dateStr){
		$dt = new DateTime($dateStr);
	    return $dt->format('Y-m-d');
	}

	/**
	* Subclasses will have to implement this method to prevent SQL injection attacks
	* The method should 'clean' the data that is about to be inserted, updated, deleted
	*/
	abstract function cleanDataGoingIntoDB($data);

	/*
	* Subclasses will have to implement this method to prevent XSS attacks
	* It should remove any potentially malicious HTML code from data that is being selected from the db
	*/
	abstract function cleanDataComingFromDB($row);

	/**
	* Gets a row from the database by it's id
	* @param {number} 	The id of the item to get from a row in the database
	* @return {object}	Returns an instance of a model object (for example, a Contact object)
	*/
	abstract function getById($id);

	/**
	* Gets all rows from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of model objects
	*/
	abstract function getAll($args = []);


	/**
	* Inserts a row into a table in the database
	* @param {object}	The model object to be inserted
	* @return {object}	Returns the same model object, but with the id property set (the id is assigned by the database)
	*/
	abstract function insert($obj);

	/**
	* Updates a row in a table of the database
	* @param {object}	The model object to be updated
	* @return {object}	Returns the same model object that was passed in as the param
	*/
	abstract function update($obj);


	/**
	* Deletes a row from a table in the database
	* @param {number} 	The id of the row to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	abstract function delete($id);

}