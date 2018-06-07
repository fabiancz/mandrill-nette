<?php

namespace Fabian\Mandrill;

/**
 * Provides functionality to compose and send email via Mandrill service.
 *
 * @author Lukas Vana
 */
class MandrillMailer implements \Nette\Mail\IMailer {
    /**
     * Mandrill API key
     * @var string
     */
    private $apiKey;

    /**
     * Mandrill API endpoint
     * @var string
     */
    private $apiEndpoint = "https://mandrillapp.com/api/1.0";

    /**
     * Input and output format
     * Currently supported only json;)
     * @var string
     */
    private $apiFormat = 'json';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Sends email via Mandrill.
     * @param $message
     * @return void
     */
    public function send(\Nette\Mail\Message $message)
    {
        if ($message instanceof Message) {
            $params = $message->getMandrillParams();
        } else {
            $params = $this->parseNetteMessage($message);
        }
        $attachments = $this->parseAttachments($message);
        if (!empty($attachments)) {
             $params['attachments'] = $attachments;
        }
        $params = array('message' => $params);

        $this->callApi('/messages/send', $params);
    }

    /**
     * Sends email via Mandrill template.
     * @param $message
     * @param $templateName
     * @param $templateContent dynamic content
     * @return void
     */
    public function sendTemplate(\Nette\Mail\Message $message, $templateName, $templateContent)
    {
        if ($message instanceof Message) {
            $params = $message->getMandrillParams();
        } else {
            $params = $this->parseNetteMessage($message);
        }
        $attachments = $this->parseAttachments($message);
        if (!empty($attachments)) {
             $params['attachments'] = $attachments;
        }
        $params = array('message' => $params);

        $params['template_name'] = $templateName;
        $params['template_content'] = $templateContent;

        $this->callApi('/messages/send-template', $params);
    }

    /**
     * Parse Nette Message headers to Mandrill API params
     * @param \Nette\Mail\Message $message
     * @return array
     */
    private function parseNetteMessage(\Nette\Mail\Message $message)
    {
        $params = array();

        $params['subject'] = $message->getSubject();
        $params['text'] = $message->getBody();
        $params['html'] = $message->getHtmlBody();
        $from = $message->getFrom();
        if (empty($from)) {
            throw new MandrillException('Please specify From parameter!');
        }
        $params['from_email'] = key($from);
        $params['from_name'] = $from[$params['from_email']];
        $recipients = $message->getHeader('To');
        $params['to'] = array();
        foreach ($recipients as $email => $name) {
            $recipient = array('email' => $email);
            if (!empty($name)) {
                $recipient['name'] = $name;
            }
            $params['to'][] = $recipient;
        }
        
        $bcc = $message->getHeader('Bcc');
        if (!empty($bcc)) {
            $params['bcc_address'] = $bcc;
        }

        return $params;
    }

    /**
     * Call Mandrill API and send email
     * @param array $params
     * @return string
     * @throws MandrillException
     */
    private function callApi($method, array $params)
    {
        $params['key'] = $this->apiKey;
        $params = json_encode($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mandrill-Nette-PHP/0.2');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint.$method.'.'
            .$this->apiFormat);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/'.$this->apiFormat)
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        if (curl_error($ch)) {
            throw new MandrillException(
                'curl error while calling '.$method.': '.  curl_error($ch)
            );
        }

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $result = json_decode($response, true);
        if ($result === NULL) {
            throw new MandrillException('Unable to parse JSON response');
        }
        if ($info['http_code'] != 200) {
            throw new MandrillException('Error '.$info['http_code'].' Message: '.$result['message']);
        }

        curl_close($ch);

        return $result;
    }

    private function parseAttachments(\Nette\Mail\Message $message){
        $attachments = array();

        foreach ($message->getAttachments() as $attachment) {
          $attachments[] = array(
            'type' => $attachment->getHeader('Content-Type'),
            'name' => $this->extractFilename($attachment->getHeader('Content-Disposition')),
            'content' => $this->encodeMessage($attachment)
          );
        }

        return $attachments;
      }

    private function extractFilename($header){
        preg_match('/filename="([a-zA-Z0-9. -_]{1,})"/', $header, $matches);
        return $matches[1];
    }

    private function encodeMessage($attachment){
        $lines = explode("\n", $attachment->getEncodedMessage());

        $output = '';

        for($i=4; $i < count($lines); $i++){
          $output .= $lines[$i];
        }

        return $output;
    }
}
