<?php
 
 class Decode{
    private $jwtToken;
    private $jwtToken_break;
    private $status;
    private $service_type;
    //private $error_type;
    //private $error_code;
    private $result_detail = array();
    private $secretkey = 'secret';
    
    function __construct($jwtToken){
        $this->jwtToken = $jwtToken;
        $this->jwtToken_break = explode(".",$this->jwtToken);
        $this->status = null;
        $this->service_type = null;
        //$this->error_type = null;
        //$this->error_code = null;
        $this->result_detail = null;
    }
    /**
     * @param int $type:        Defines the type of result to be returned. Ranges from 0 to 2. 
     * 0 means encoded payload, 1 means payload in JSON, 2 means payload in array.
     * @param int $msg_type:    Defines that data detailing.
     * 0 means data in detailed format, 1 means data in short format.
     */
    function getPayload($type,$msg_type){
        if(!Decode::checkIfInteger($type)){
            throw new Exception("Undefined \"Type Variable\" passed, please check if you are passing Array type only. For more, plase refer to the manual.", 1);
        }
        if($type > 2 || $type < 0){
            throw new Exception("Range of \"Type variable\" not defined. Please see if you are providing value between 0-2. For more, please refer to the manual.",1);
        }
        if($type == 0){
            $this->result = $this->jwtToken_break[1];
            $this->status = 1;
            $this->service_type = "Get payload as it is.";
        }elseif($type == 1){
            $this->result = Decode::json_degenerator($this->jwtToken_break[1]);
            $this->status = 1;
            $this->service_type = "Get the payload in JSON.";
        }
        elseif($type == 2){
            $this->result = Decode::array_degenerator($this->jwtToken_break[1]);
            $this->status = 1;
            $this->service_type = "Get the payload in array.";
        }
        if(!Decode::checkIfInteger($msg_type)){
            throw new Exception("Undefined \"Message Type Variable\" type passed, please check if you are passing Array type only. For more, plase refer to the manual.", 1);
        }
        if($msg_type>2 || $msg_type<0){
            throw new Exception("Range of \" Message Type variable\" not defined. Please see if you are providing value between 0-1. For more, please refer to the manual.",1);
        }
        //If msg_type variable is passed as 0, then only the payload data will be returned.
        //Else if msg_type variable is passed as 1, then detailed array of data will be passed.
        if($msg_type==0){
            return $this->result;
        }elseif($msg_type==1){
            $this->result_detail = ["Status"=>$this->status,"Service Type"=>$this->service_type,"Result"=>$this->result];
            return $this->result_detail;
        }
    }
    /**
     * This function will verify the JWT token thatis being passed to the code.
     * @param $algo     : Provides the algo for the hash_hmac() function to compute the signature.
     * @param $token    : Provides the token that in the encoded format from the user.
     * @param $key      : This is the key that helps to generate signature to match with the encoded signature.
     */
    function verifyJWT($algo,$token,$key){
        list($headerEncoded,$payloadEncoded,$signatureEncoded) = explode('.',$token);
        if($algo == null){
            $header = Decode::json_degenerator($headerEncoded);
            //var_dump($headerEncoded);
            $algo = json_decode($header);
            if($algo->alg == "HS256"){
                $algo = 'sha256';
            }elseif($algo->alg == "HS384"){
                $algo = 'sha384';
            }elseif($algo->alg == "HS512"){
                $algo = 'sha512';
            }else{
                throw new Exception("Undefined \"Algorithm used in decoding\" type passed, please check if you are passing Array type only. For more, plase refer to the manual.", 1);
            }
        }
        if($key == null){
            $key = $this->secretkey;
        }
        $receive = $headerEncoded.'.'.$payloadEncoded;
        $rawsignature = hash_hmac($algo,$receive,$key,true);
        $rawsignature = str_replace('=', '', strtr(base64_encode($rawsignature), '+/', '-_'));
        if($rawsignature == $signatureEncoded){
            return true;
        }
        else{
            return false;
        }
    }
    function json_degenerator($data){
        $data  = base64_decode($data);
        return $data;
    }
    function array_degenerator($data){
        $data = base64_decode($data);
        $data = json_decode($data, TRUE);
        return $data;
    }
    function checkIfInteger($data){
        return is_int($data);
    }
    
 }
    try{
        $token = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.YI0rUGDq5XdRw8vW2sDLRNFMN8Waol03iSFH8I4iLzuYK7FKHaQYWzPt0BJFGrAmKJ6SjY0mJIMZqNQJFVpkuw';
        $decode = new Decode($token);
        $result = $decode->getPayload(1,1);
        //var_dump($result);
        $secret_key = 'secret';
        $verify = $decode->verifyJWT('sha512',$token,$secret_key,TRUE);
        var_dump($verify);
        //echo $decode->display(1);
        //var_dump($decode->result);
    }catch(Exception $e){
        echo $e->getMessage();
    }
?>