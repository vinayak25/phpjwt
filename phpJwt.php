<?php
	/**
	* @author Vinayak Sarawagi(vinayaksarawagi25@gmail.com)
	* @author Mayank Sareen(mayanksareen63@gmail.com)
	*/
	class phpJwt
	{
		private $algo_type;
		private $payload_data=array();
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
			if($algo_type > 5 or $algo_type<0){
				throw new Exception("Algorithm type not defined. Please refer to the manual", 1);
			}
			if($cty==null){
				$header_data = ["type"=>"JWT","alg"=>"'".$this->supported_algos[$algo_type]."'"];	
			}else{
				$header_data = ["type"=>"JWT","alg"=>"'".$this->supported_algos[$algo_type]."'","cty"=>"'".$cty."'"];
			}
			$header_data = phpJwt::base64_generator(phpJwt::json_generator($header_data));
			var_dump($header_data);
		}

		//This function creates generic standard payload
		function createPayload(){
			echo "Payload is created Here";
		}



		//This function creates extra data to be included in payload
		function createExtraPayload($extra_data){
			foreach ($extra_data as $key => $value) {
				$this->payload_data[$key] = $value;
			}
			$this->payload_data = phpJwt::json_generator($this->payload_data);
			var_dump($this->payload_data);
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
	}


	$obj = new phpJwt();
	$obj->createHeader(6,"JWT","text/html");
	$data =["email"=>"vinayaksarawagi25@gmail.com","id"=>4567890];
	$obj->createExtraPayload($data);
?>