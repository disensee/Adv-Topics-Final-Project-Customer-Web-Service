<?php

abstract class Model {

	
	/**
	* Validates the state of a Model object. Returns true if it is valid, false otherwise
	* @return {boolean}
	*/
	abstract public function isValid();

	/**
	* Converts an instance of a model object into JSON
	* @return {string}		The state of the model object in JSON encoded formatting
	*/
	public function toJSON(){
		return json_encode($this);
	}


	/**
	* Converts an instance of a model object into CSV
	* @return {string}		The state of the model in CSV format
	*/
	public function toCSV(){

		// Not sure if this approach will work
		// How is the order of values determined?
		// What happens to methods, are they included in the csv? 
		$cells = [];
		foreach ($this as $value) {
			$cells[] = $value;
		}
		return implode($cells,",");
	}


	/**
	* Converts an instance of a model object into XML
	* @return {string}		The state of the model in XML format
	*/
	public function toXML(){

		$rootElement = strtolower(get_class($this));
		
		$xml = new SimpleXMLElement(strtolower('<' . $rootElement . '/>'));

		//$xml = simplexml_load_string("<$rootElement />");
		foreach ($this as $key => $value) {
		  $xml->addChild($key, $value);
		}
		$XMLstring = $xml->asXML();
		// to remove the xml doc type declaration
		//https://stackoverflow.com/questions/5947695/remove-xml-version-tag-when-a-xml-is-created-in-php
		$XMLstring = substr($XMLstring, strpos($XMLstring, '?'.'>') + 2);

		return trim($XMLstring);
	}

	



}