<?php
   define("connected", true);
   define("disconnected", false);
   include_once("Log.php");
   /**
    * socket class
    * 
    *  
    * @author seven 
   */
  class SocketUdp
  {
      private static $instance;
  
      private $connection = null;
      
      private $connectionstate = disconnected;
      
      private $defaulthost = "192.168.1.212";
      
      private $defaultport = 20001;
     
      private $defaulttimeout = 10;
     
      public  $debug = false;
      
      function __construct()
      {
          
     }
      /**
      * singleton pattern. returns the same instance to all callers
       *
     * @return socket
       */
     public static function singleton()
     {
          if (self::$instance == null || ! self::$instance instanceof socket)
         {
              self::$instance = new socket();
			  //Log::write("123");
             
          }else{
				//Log::write("456"); 
		  }
          return self::$instance;
      }
      /**
       * connects to the socket with the given address and port
      * 
      * @return void
      */
      public function connect($serverhost=false, $serverport=false, $timeout=false)
      {        
          if($serverhost == false)
          {
              $serverhost = $this->defaulthost;
          }
         
          if($serverport == false)
          {
              $serverport = $this->defaultport;
          }
          $this->defaulthost = $serverhost;
          $this->defaultport = $serverport;
          
          if($timeout == false)
          {
              $timeout = $this->defaulttimeout;
          }
          $this->connection = socket_create(AF_INET,SOCK_DGRAM,SOL_TCP);  
          if (socket_bind($this->connection, '192.168.1.212', 20001)) {
				echo "Socket bound correctly";
				Log::write("Socket bound correctly");
		  }else{
				Log::write("Socket bound Wrong","error"); 
		  }
          
          
          $this->connectionstate = connected;
      }
      
      /**
       * disconnects from the server
       * 
       * @return true on succes, false if the connection was already closed
       */
      public function disconnect()
      {
          if($this->validateconnection())
          {
              socket_close($this->connection);
              $this->connectionstate = disconnected;
              $this->_throwmsg("socket disconnected!");
              return true;
          }
          return false;
      }
      /**
 98      * sends a command to the server
 99      * 
100      * @return string server response
101      */
     public function sendrequest($command)
     {
             if(socket_sendto($this->connection,$command,strlen($command),0x100,'192.168.1.212',20001)!==false){
			 	Log:write("");
			 }
			 echo $result;
             return $result;
        
     }
     
     
     
     public function isconn()
     {
         return $this->connectionstate;
     }
     
     
     public function getunreadbytes()
     {
         
         $info = socket_get_status($this->connection);
         return $info['unread_bytes'];
 
     }
 
     
     public function getconnname(&$addr, &$port)
     {
         if ($this->validateconnection())
         {
             socket_getsockname($this->connection,$addr,$port);
         }
     }
     
    
     
     /**
140      * gets the server response (not multilined)
141      * 
142      * @return string server response
143      */
     public function getresponse()
    {
         $read_set = array($this->connection);
     
         while (($events = socket_select($read_set, $write_set = null, $exception_set = null, 0)) !== false) 
         {
             if ($events > 0)
             {
                 foreach ($read_set as $so)
                {
                     if (!is_resource($so))
                     {
                        $this->_throwerror("receiving response from server failed.<br>reason: not connected");
                         return false;
                     }elseif ( ( $ret = @socket_read($so,4096,php_binary_read) ) == false){
                         $this->_throwerror("receiving response from server failed.<br>reason: not bytes to read");
                        return false;
                     }
                     return $ret;
                 }
             }
         }
         
         return false;
     }
     public function waitforresponse()
     {
         if($this->validateconnection())
         {
             return socket_read($this->connection, 2048);
         }
         
         $this->_throwerror("receiving response from server failed.<br>reason: not connected");
     return false;
     }
     /**
180      * validates the connection state
181      * 
182      * @return bool
183      */
     private function validateconnection()
     {
         return (is_resource($this->connection) && ($this->connectionstate != disconnected));
     }
     /**
189      * throws an error
190      * 
191      * @return void
192      */
     private function _throwerror($errormessage)
     {
         echo "socket error: " . $errormessage;
     }
     /**
198      * throws an message
199      * 
200      * @return void
201      */
     private function _throwmsg($msg)
     {
         if ($this->debug)
         {
             echo "socket message: " . $msg . "\n\n";
         }
     }
     /**
210      * if there still was a connection alive, disconnect it
211      */
     public function __destruct()
     {
         $this->disconnect();
     }
 }
 
 ?>