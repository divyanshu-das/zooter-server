<?php
/**
 * Mandrill Transport
 *
 */

App::uses('AbstractTransport', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');

/**
 * MandrillTransport
 *
 * This class is used for sending email messages
 * using the Mandrill API http://mandrillapp.com/
 *
 */
class MandrillTransport extends AbstractTransport {

/**
 * CakeEmail
 *
 * @var CakeEmail
 */
	protected $_cakeEmail;

/**
 * Variable that holds Mandrill connection
 *
 * @var HttpSocket
 */
	private $__mandrillConnection;

/**
 * CakeEmail headers
 *
 * @var array
 */
	protected $_headers;

/**
 * Configuration to transport
 *
 * @var mixed
 */
	protected $_config = array();

/**
 * Sends out email via Mandrill
 *
 * @return array Return the Mandrill
 */
	public function send(CakeEmail $email) {
		// CakeEmail
		$this->_cakeEmail = $email;

		$this->_config = $this->_cakeEmail->config();
		$this->_headers = $this->_cakeEmail->getHeaders(array('from', 'to', 'cc', 'bcc', 'replyTo', 'subject'));

		// Setup connection
		$this->__mandrillConnection = &new HttpSocket();

		// Build message
		$message = $this->__buildMessage();
		
		// Build request
		$request = $this->__buildRequest();

		if(isset($this->_config['mandrillTemplate']) && !empty($this->_config['mandrillTemplate'])){
			$message_send_uri = $this->_config['uri'] . "messages/send-template.json";
		}else{
			$message_send_uri = $this->_config['uri'] . "messages/send.json";	
		}
		
		// Send message
		try{
			$returnMandrill = $this->__mandrillConnection->post($message_send_uri, json_encode($message), $request);
			// Return data
			$result = json_decode($returnMandrill, true);
					
			$headers = $this->_headersToString($this->_headers);

			return array_merge(array('Mandrill' => $result), array('headers' => $headers, 'message' => $message));
		}catch(Exception $e){
			return false;
		}
	}

/**
 * Build message
 *
 * @return array
 */
	private function __buildMessage() {
		// Message
		
		$json = array();
		$json["key"] = $this->_config['key'];

		$message = array();
				
		// From
		$fromEmail = $this->_cakeEmail->from();
		reset($fromEmail);
		$message['from_email'] = key($fromEmail);
		if($message['from_email'] != current($fromEmail)){
			$message['from_name'] = current($fromEmail);	
		}

		// To
		$message["to"] = array(
			array("email" => $this->_headers['To'])
		);

		// Subject
		$message['subject'] = mb_decode_mimeheader($this->_headers['Subject']);

		//Template Name
		if(isset($this->_config['mandrillTemplate']) && !empty($this->_config['mandrillTemplate'])){
			$json['template_name'] = $this->_config['mandrillTemplate'];
			$json['template_content'] = '';

			//Template Variables -> Merge Variables in Mandrill
			$vars = $this->_cakeEmail->viewVars();
			if(!empty($vars)){
				$message['global_merge_vars'] = array();
				foreach($vars as $key => $var){
					$message['global_merge_vars'][] = array('name' => $key, 'content' => $var);
				}
			}
		}

		// HtmlBody
		if ($this->_cakeEmail->emailFormat() === 'html' || $this->_cakeEmail->emailFormat() === 'both') {
			$message['html'] = $this->_cakeEmail->message('html');
		}

		// TextBody
		if ($this->_cakeEmail->emailFormat() === 'text' || $this->_cakeEmail->emailFormat() === 'both') {
			$message['text'] = $this->_cakeEmail->message('text');
		}

		$attachments = $this->_cakeEmail->attachments();
		$messageAttachments = array();
		if(!empty($attachments)){
			foreach($attachments as $key => $attachment){
				$content = file_get_contents($attachment['file']);
				$content = base64_encode($content);
				$messageAttachments[] = array('type' => $attachment['mimetype'], 'name' => $key, 'content' => $content);
			}
			if(!empty($messageAttachments)){
				$message['attachments'] = $messageAttachments;
			}
		}
		
		$json["message"] = $message;


		return $json;
	}


/**
 * Build request
 *
 * @return array
 */
	private function __buildRequest () {
		$request = array(
			'header' => array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
			)
		);

		return $request;
	}



}
