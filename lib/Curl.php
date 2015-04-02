<?php
/**
 * CURL
 * Base Class for Sending CURL Request 
 * 
 * @package  Twitter-API
 * @link https://github.com/vikram0207/twitter-api
 * @author Vikram Singh <vikram0207@gmail.com>
 */
abstract class Curl {

    private $url;
    private $error;
    private $response;
    private $postfields;
    private $getfield;
    private $header;
    

    const TIMEOUT = 10;
    const CONNECTION_TIMEOUT = 20;

    //Constructor
    public function __construct() {
        if (!in_array('curl', get_loaded_extensions())) 
        {
            throw new Exception('This functionality required CURL');
        }
    }
    
    /**
     * This will initiate CURL Request and set response
     * 
     * @return Instance of self for method chaining
     * 
     * @throws Exception
     */
    protected function initiate() {
        $buffer = '';
        if (!isset($this->url) || $this->url == '') {
            throw new Exception('URL not defined');
        }

        try {
            
            $getfield = $this->getGetfield();
            $postfields = $this->getPostfields();

            $options = array( 
                CURLOPT_HEADER => false,
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => self::TIMEOUT,
                CURLOPT_CONNECTTIMEOUT => self::CONNECTION_TIMEOUT,
                //CURLOPT_SSL_VERIFYPEER=> true,
                //CURLOPT_SSL_VERIFYHOST => 1,
            );
            
            if(!empty($this->header)) {
                $options[CURLOPT_HTTPHEADER] = $this->header;
            }
            
            if (!empty($postfields))
            {
                $options[CURLOPT_POSTFIELDS] = $postfields;
            }
            elseif(!empty ($getfield))
            {
                $options[CURLOPT_URL] .= $getfield;
            }

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $buffer = curl_exec($ch);
            curl_close($ch);
            
            if (empty($buffer)) {
                return FALSE;
            } 
            
            $this->setResponse(json_decode($buffer));
            
//            echo 'URL: '.$this->url.PHP_EOL;
//            echo 'Header: '.$this->header.PHP_EOL;
//            echo 'buffer: <pre>';
//            print_r($buffer);
            
            return true;
        } catch (Exception $e) {
            $this->_error = $e->getMessage();
            return FALSE;            
        }
    }

    /**
     * setResponse
     * Method to Set Response
     * @param <CURL RESPONSE> $resp
     */
    private function setResponse($resp) {
        $this->response = $resp;
    }

    /**
     * getResponse
     * Method to get Response
     * @return <CURL RESPONSE>
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * getErrorMsg
     * Method to get Error Message
     * @return type
     */
    public function getErrorMsg() {
        return $this->error;
    }
    
    /**
     * Set postfields array, example: array('screen_name' => 'J7mbo')
     * 
     * @param array $array Array of parameters to send to API
     * 
     * @return TwitterAPIExchange Instance of self for method chaining
     */
    public function setPostfields(array $array)
    {
        if (!is_null($this->getGetfield())) 
        { 
            throw new Exception('You can only choose get OR post fields.'); 
        }
        
        if (isset($array['status']) && substr($array['status'], 0, 1) === '@')
        {
            $array['status'] = sprintf("\0%s", $array['status']);
        }
        
        $this->postfields = $array;
        
        return $this;
    }
    
    /**
     * Set getfield string, example: '?screen_name=J7mbo'
     * 
     * @param string $string Get key and value pairs as string
     * 
     * @return Instance of self for method chaining
     */
    public function setGetfield($string)
    {
        if (!is_null($this->getPostfields())) 
        { 
            throw new Exception('You can only choose get OR post fields.'); 
        }
        
        $search = array('#', ',', '+', ':');
        $replace = array('%23', '%2C', '%2B', '%3A');
        $string = str_replace($search, $replace, $string);  
        
        $this->getfield = $string;
        
        return $this;
    }
    
    /**
     * Get getfield string (simple getter)
     * 
     * @return string $this->getfields
     */
    public function getGetfield()
    {
        return $this->getfield;
    }
    
    /**
     * Get postfields array (simple getter)
     * 
     * @return array $this->postfields
     */
    public function getPostfields()
    {
        return $this->postfields;
    }
    
    public function setHeader($header)
    {
        $this->header = $header;
    }
    
    public function setURL($url)
    {
        $this->url = $url;
    }
    
    
    public abstract function request();
    public abstract function response();
}
