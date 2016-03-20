<?php
/**
 * Msg - client for tiny real-time messaging server
 *
 * @author     Steve Ottoz
 * @copyright  2016 Steve Ottoz
 * @license    MIT License http://opensource.org/licenses/MIT
 * @version    0.1.0
 * @link       https://github.com/iamso/tinymsg-php
 */

/**
 * tinymsg namespace
 */
namespace tinymsg;

/**
 * Msg class
 */
class Msg {

	/**
	 * The protocol for the connection. It's either empty or "ssl://".
	 *
	 * @access private
	 * @var    string
	 */
	private $protocol = '';

	/**
	 * The host to connect to
	 *
	 * @access private
	 * @var    string
	 */
	private $host = '';

	/**
	 * The port to connect through
	 *
	 * @access private
	 * @var    number
	 */
	private $port = 0;

	/**
	 * The channel to join
	 *
	 * @access private
	 * @var    string
	 */
	private $channel = '';

	/**
	 * The origin of the connection
	 *
	 * @access private
	 * @var    string
	 */
	private $origin = '';

	/**
	 * The socket object
	 *
	 * @access private
	 * @var    objecyt
	 */
	private $sock = NULL;

	/**
	 * The Msg consctructor takes some information data and opens the websocket connection
	 *
	 * @access public
	 * @param  string  $channel - channel to join
	 * @param  string  $host    - host to connect to
	 * @param  boolean $ssl     - true for secure connection
	 * @param  number  $port    - port to connect through
	 * @return void
	 */
	public function __construct($channel = '', $host = '', $ssl =  false, $port = NULL) {
		$this->channel = $channel;
		$this->host = $host;
		$this->port = $port ? $port : ($ssl ? 443 : 80);
		$this->protocol = $ssl ? 'ssl://' : '';
		$this->getOrigin();
		$this->open();
	}

	/**
	 * Open the websocket connection
	 *
	 * @access public
	 * @return void
	 */
	public function open() {
		$this->sock = fsockopen($this->protocol.$this->host, $this->port, $errno, $errstr, 2);
		fwrite($this->sock, $this->getHeaders()) or $this->error($errno, $errstr);
	}

	/**
	 * Close the websocket connection
	 *
	 * @access public
	 * @return void
	 */
	public function close() {
		fclose($this->sock);
	}

	/**
	 * Send the data to the websocket server
	 *
	 * @access public
	 * @param  string|object|array $data - data to be sent
	 * @return void
	 */
	public function send($data) {
		fwrite($this->sock, $this->encode(json_encode($data))) or $this->error($errno, $errstr);
	}

	/**
	 * Error handler
	 *
	 * @access private
	 * @param  number $errno  - Error number
	 * @param  string $errstr - Error string
	 * @return void
	 */
	private function error($errno, $errstr) {
		error_log("Msg error: $errno $errstr");
	}

	/**
	 * Get the origin of the connection
	 *
	 * @access private
	 * @return string - origin
	 */
	private function getOrigin() {
		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		return $this->origin = "$protocol://$_SERVER[HTTP_HOST]";
	}

	/**
	 * Get the headers for the connection
	 *
	 * @access private
	 * @return string - headers
	 */
	private function getHeaders() {
		return
			"GET / HTTP/1.1"."\r\n".
			"Host: $this->host"."\r\n".
			"Connection: Upgrade"."\r\n".
			"Upgrade: websocket"."\r\n".
			"Origin: $this->origin"."\r\n".
			"Sec-WebSocket-Version: 13"."\r\n".
			"Sec-WebSocket-Key: ".$this->newHandshakeKey()."\r\n".
			"Sec-WebSocket-Protocol: $this->channel"."\r\n"."\r\n";
	}

	/**
	 * Get a new key for the websocket handshake
	 *
	 * @access private
	 * @return string - base64 encode key
	 */
	private function newHandshakeKey() {
		$key = '';
		for ($i = 0; $i < 16; $i++) {
			$key .= chr(mt_rand(0, 255));
		}
		return base64_encode($key);
	}

	/**
	 * Encode data to send
	 *
	 * @access private
	 * @param  string $data - data to encode
	 * @return string       - encoded data
	 */
	private function encode($data) {
		$frame = Array();
		$encoded = "";
		$frame[0] = 0x81;
		$data_length = strlen($data);

		if ($data_length <= 125) {
			$frame[1] = $data_length;
		}
		else {
			$frame[1] = 126;
			$frame[2] = $data_length >> 8;
			$frame[3] = $data_length & 0xFF;
		}

		for ($i=0; $i < sizeof($frame); $i++) {
			$encoded .= chr($frame[$i]);
		}

		$encoded .= $data;
		return $encoded;
	}

}
