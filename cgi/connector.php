<?php
class Connector {
    private $_socket;

    /**
    * Constructor of class Connector.
    *
    * @return void
    */
    public function __construct() {
        $this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($_socket === false) {
            // echo "_socket_create() failed: reason: " . 
            // _socket_strerror(_socket_last_error()) . "<br/>";
            return true;
        }
        return false;
    }

    /**
    * open
    *
    * @return bool
    */
    public function open($addr, $port) {
        $addr = gethostbyname(addr);
        $result = socket_connect($this->_socket, '127.0.0.1', $port);
        if ($result === false) {
            // echo "_socket_connect() failed.<br/>Reason: ($result) " . 
            // _socket_strerror(_socket_last_error($_socket)) . "<br/>";
            return true;
        }
        return false;
    }

    /**
    * close
    *
    * @return void
    */
    public function close() {
        socket_close($this->_socket);
    }

    /**
    * write
    *
    * @return bool
    */
    public function write($tx) {
        $tx .= "\r\n";
        $result = socket_write($this->_socket, $tx, strlen($tx));
        if ($result === false) {
            // echo "socket_write() failed.<br/>Reason: ($result) " .
            // socket_strerror(socket_last_error($socket)) . "<br/>";
            return true;
        }
        return false;
    }

    /**
    * read
    *
    * @return string from device
    */
    public function read() {
        ob_implicit_flush(true);
        while (true) {
            socket_recv($this->_socket, $buffer, 1, 0);
            $rx .= $buffer;
            if ($buffer == "\n") {
                break;
            }
        }
        return $rx;
    }
}
?>
