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
		const ALGO_TYPE_HS256 = 0;
		const ALGO_TYPE_HS384 = 1;
		const ALGO_TYPE_HS512 = 2;
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
			if($cty==null){
				$this->header_data = ["type"=>"JWT","alg"=>"'".$this->supported_algos[$algo_type]."'"];	
			}else{
				$this->header_data = ["type"=>"JWT","alg"=>"'".$this->supported_algos[$algo_type]."'","cty"=>"'".$cty."'"];
			}
			$header_data = phpJwt::base64_generator(phpJwt::json_generator($header_data));
			var_dump($header_data);
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
				if($this->$iss!=null)
				{
					$this->payload_data["iss"] = $iss;
				}
				if($this->$sub!=null){
					$this->payload_data["sub"] = $sub;
				}
				if($this->$aud!=null){
					$this->payload_data["aud"] = $aud;
				}
				if($this->exp!=null){
					$this->payload_data["exp"] = $exp;
				}
				if($this->nbf!=null){
					$this->payload_data["nbf"] = $nbf;
				}
				if($this->iat!=null){
					$this->payload_data["iat"] = $iat;
				}
				if($thsi->jti!-null){
					$this->payload_data["jti"] = $jti;
				}
				
		}



		//This function creates extra data to be included in payload
		function createExtraPayload(){
			
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
	}


	$obj = new phpJwt();
	$obj->createHeader(0,"JWT","text/html");
	
?>