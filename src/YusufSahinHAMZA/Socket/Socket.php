<?php

namespace YusufSahinHAMZA\Socket;

use App\Models\DeviceMessage;
use RuntimeException;

class Socket
{

    protected $ip = null;
    protected $port = null;
    protected $protocol = null;
    protected $socket = null;
    protected $isConnected = false;
    protected $myIp;
    protected $myPort;

    public function __construct($ip, $port, $protocol)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->protocol = $protocol;
    }

    public function connect($ip = null, $port = null, $protocol = null, $timeout = null)
    {
        if (!is_null($ip))
            $this->ip = $ip;

        if (!is_null($port))
            $this->port = $port;

        if (!is_null($protocol))
            $this->protocol = $protocol;

        if (!($this->socket = socket_create(AF_INET, SOCK_STREAM, $this->protocol))) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            $this->isConnected = false;
            throw new RuntimeException($errormsg, $errorcode);
        }

        if (!socket_connect($this->socket, $this->ip, intval($this->port))) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            $this->isConnected = false;
            throw new RuntimeException($errormsg, $errorcode);
        } else {
            $this->isConnected = true;
            socket_getsockname($this->socket, $IP, $PORT);

            if ($timeout != null) {
                $timeout = intval($timeout);
                $option = ['sec' => $timeout, 'usec' => $timeout * 1000];
                socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, $option);
                socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, $option);
            }

            $this->myIp = $IP;
            $this->myPort = $PORT;

            $result = new \stdClass();
            $result->ip = $IP;
            $result->port = $PORT;
            $result->socket = $this->socket;

            return $result;
        }
    }

    public function disconnect()
    {
        //socket_shutdown($socket, 2);
        socket_close($this->socket);
        $this->ip = null;
        $this->port = null;
        $this->protocol = null;
    }

    public function sendMessage($message)
    {
        $length = strlen($message);
        $sent = socket_write($this->socket, $message, $length);
        if (!$sent) {
            $errorCode = socket_last_error();
            $errorMessage = socket_strerror($errorCode);
            throw new RuntimeException($errorMessage, $errorCode);
        }
        $out = '';
        while($resp = @socket_read($this->socket, 1024)) {
            $out .= $resp;
            if (strpos($out, "\n") !== false) break;
        }
        return $out != false? $out : "No Data!";
    }

}
