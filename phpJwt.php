<?php
	/**
	* @author Vinayak Sarawagi(vinayaksarawagi25@gmail.com)
	* @author Mayank Sareen(mayanksareen63@gmail.com)
	*/
	class phpJwt
	{
		private $algo_type;
		private $payload_data=array();
		private $header_data;
		private $headerb64;
		private $payloadb64;
    	const ALGO_TYPE_HS256 = 0;
		const ALGO_TYPE_HS384 = 1;
		const ALGO_TYPE_HS5345678 = 2;
		const ALGO_TYPE_RS256 = 3;
		const ALGO_TYPE_RS384 = 4;
		const ALGO_TYPE_RS512 = 5;
		private $supported_algos = array("HS256 ","HS384","HS512","RS256","RS384","RS512"); 
		/**
		*@param int    $algo_type : Defines the type of supported algo
		*@param String $type      : Defines the type of the header restricted to JWT
		*@param String $cty       : Defines the content-type of the token
		*/
		function createHeader($algo_type,$type="JWT",$cty=null){
			if($algo_type > 5 or $algo_type<0){
				throw new Exception("Algorithm type not defined. Please refer to the manual", 1);
			}
			if(!phpJwt::checkIfInteger($algo_type)){
				throw new Exception("Undefined Data Type passed. Please check, if you are passing an integer value in defined ranged. For more please refer to the manual", 1);
			}
			if(!phpJwt::checkIfString($type)){
				throw new Exception("Undefined Data Type passed. Please check, if you are passing a string value. For more, please refer to the manual", 1);
			}
			/*
			if($type==null || $type!="JWT" || $type!="jwt"){
				throw new Exception("Undefined Token Type passed. Please refer to the manual", 1);
			}*/
			if($cty==null){
				$this->header_data = ["type"=>"JWT","alg"=>"'".$this->supported_algos[$algo_type]."'"];	
			}else{
				$this->header_data = ["type"=>"JWT","alg"=>"'".$this->supported_algos[$algo_type]."'","cty"=>"'".$cty."'"];
			}
			$this->header_data = phpJwt::json_generator($this->header_data);
			$this->headerb64 = phpJwt::base64_generator($this->header_data);
		}
		//This function creates generic standard payload
		/**
		*@param String $iss: Defines issuer string.
		*@param String $sub: Defines the princi[pal of the subject JWT.
		*@param String $aud: Defines the recepient that the JWT is intended for.
		*@param int $exp:	Defines the expiration time for the JWT
		*@param int $nbf: 	Defines the time before the JWT must not be accepted.
		*@param int $iat:	Defines the time at which the JWT is issued at.
		*@param int $jti:	Defines the unique identifier for the JWT.
		*/
		function createPayload($iss=null,$sub=null,$aud=null,$exp=null,$nbf=null,$iat=null,$jti=null){
				if(!phpJwt::checkIfString($iss)){
					throw new Exception("Undefined \"Issuer\" type passed, please check if you are passing String type only. For more, please refer to the manual", 1);
				}
				if(!phpJwt::checkIfString($sub)){
					throw new Exception("Undefined \"Subject\" type passed, please check if you are passing String type only. For more, please refer to the manual", 1);
				}
				if(!phpJwt::checkIfString($aud)){
					throw new Exception("Undefined \"Audience\" type passed, please check if you are passing String type only. For more, please refer to the manual", 1);
				}
				if(!phpJwt::checkIfInteger($exp)){
					throw new Exception("Undefined \"Expiration\" type passed, please check if you are passing Integer type only. For more, plase refer to the manual.", 1);
				}
				if(!phpJwt::checkIfInteger($nbf)){
					throw new Exception("Undefined \"Not Before\" type passed, please check if you are passing Integer type only. For more, plase refer to the manual.", 1);
				}
				if(!phpJwt::checkIfInteger($iat)){
					throw new Exception("Undefined \"Issued At\" type passed, please check if you are passing Integer type only. For more, plase refer to the manual.", 1);
				}
				if(!phpJwt::checkIfInteger($jti)){
					throw new Exception("Undefined \"JWT Token Id\" type passed, please check if you are passing Integer type only. For more, plase refer to the manual.", 1);
				}
				if($iss!=null)
				{
					$this->payload_data["iss"] = $iss;
				}
				if($sub!=null){
					$this->payload_data["sub"] = $sub;
				}
				if($aud!=null){
					$this->payload_data["aud"] = $aud;
				}
				if($exp!=null){
					$this->payload_data["exp"] = $exp;
				}
				if($nbf!=null){
					$this->payload_data["nbf"] = $nbf;
				}
				if($iat!=null){
					$this->payload_data["iat"] = $iat;
				}
				if($jti!=null){
					$this->payload_data["jti"] = $jti;
				}
		}
		/**
		*@param Associative Array $extra_data Includes extra data to include in $payload_data
		*
		*/	
		//This function creates extra data to be included in payload
		function createExtraPayload($extra_data){
			if(!phpJwt::checkIfArray($extra_data)){
				throw new Exception("Undefined \"Extra Data\" type passed, please check if you are passing Array type only. For more, plase refer to the manual.", 1);
			}
			foreach ($extra_data as $key => $value) {
				$this->payload_data[$key] = $value;
			}
			//$this->payload_data = phpJwt::json_generator($this->payload_data);
			//var_dump($this->payload_data);
		}	
		function payload_encode(){
			$this->payload_data = phpJwt::json_generator($this->payload_data);
			$this->payloadb64 = phpJwt::base64_generator($this->payload_data);
		}
		//Converts array data into json data
		//Currently uses json_encode() generic function
		function json_generator($data){
			$data =  json_encode($data);
			return $data;
		}
		function base64_generator($jsonData){
			$jsonData = base64_encode($jsonData);
			return $jsonData;
		}
		function checkIfInteger($data){
			return is_int($data);
		}
		function checkIfString($data){
			return is_string($data);
		}
		function checkIfArray($data){
			return is_array($data);
		}
	}
	try{
		$obj = new phpJwt();
		$obj->createHeader(0,"JWT","text/html");
		$obj->createPayload("assguard","login","generic",1234567,12345678,12234567,23456);
		//$obj->createHeader(6,"JWT","text/html");
		$data =["Email"=>"vinayaksarawagi25@gmail.com"];
		$obj->createExtraPayload($data);
	}catch(Exception $e){
		echo $e->getMessage();
	}
?>