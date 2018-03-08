<?php
 
 class Decode{

    private $jwtToken;
    private $jwtToken_break;
    private $status;
    private $service_type;
    //private $error_type;
    //private $error_code;
    private $result_detail = array();
    

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
     * @param integer $type:        Defines the type of result to be returned. Ranges from 0 to 2. 
     * 0 means encoded payload, 1 means payload in JSON, 2 means payload in array.
     * @param integer $msg_type:    Defines that data detailing.
     * 0 means data in detailed format, 1 means data in short format.
     */
    function getPayload($type,$msg_type){
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
        }else{
            throw new Exception("Type not defined.",1);
        }
        return $this->result;

    }

    function display($data){
        if($data == 0)
        {
            var_dump($this->result);
        }elseif($data == 1){
            echo "Status: ".$this->status." i.e. ".$this->service_type;
            var_dump($this->result);    
        }
        else{
            throw new Exception("Message type not found");
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
    
 }
    try{
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOiJiMDhmODZhZi0zNWRhLTQ4ZjItOGZhYi1jZWYzOTA0NjYwYmQifQ.-xN_h82PHVTCMA9vdoHrcZxH-x5mb11y1537t3rGzcM";
        $decode = new Decode($token);
        $result = $decode->getPayload(1,1);
        echo $decode->display(1);
        //var_dump($decode->result);
    }catch(Exception $e){
        echo $e->getMessage();
    }

?>