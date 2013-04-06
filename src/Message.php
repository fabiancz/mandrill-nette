<?php

namespace Fabian\Mandrill;

/**
 * Provides similar API to Nette\Mail\Message 
 */
class Message extends \Nette\Mail\Message {
    /**
     * Message parameters
     * @var array
     */
    private $mandrillParams = array();

    /**
    * Sets the sender of the message.
    * @param  string  email or format "John Doe" <doe@example.com>
    * @param  string
    * @return Message  provides a fluent interface
    */
    public function setFrom($email, $name = NULL)
    {
        $this->mandrillParams['from_email'] = $email;
        if (!is_null($name)) {
            $this->mandrillParams['from_name'] = $name;
        }
        
        return $this;
    }

    /**
    * Sets textual body.
    * @param  string
    * @return Message  provides a fluent interface
    */
    public function setBody($body)
    {
        $this->mandrillParams['text'] = $body;
        
        return $this;
    }
    
    /**
    * Sets HTML body.
    * @param  string
    * @param  NOT IMPLEMENTED
    * @return Message  provides a fluent interface
    */
    public function setHtmlBody($html, $basePath = NULL)
    {
        $this->mandrillParams['html'] = $html;
        
        return $this;
    }
    
    /**
    * Sets the subject of the message.
    * @param  string
    * @return Message  provides a fluent interface
    */
    public function setSubject($subject)
    {
        $this->mandrillParams['subject'] = $subject;
        
        return $this;
    }
    
    /**
    * Adds email recipient.
    * @param  string  email or format "John Doe" <doe@example.com>
    * @param  string
    * @return Message  provides a fluent interface
    */
    public function addTo($email, $name = NULL)
    {
        if (!isset($this->mandrillParams['to'])) {
            $this->mandrillParams['to'] = array();
        }
        $recipient = array('email' => $email);
        if (!is_null($name)) {
            $recipient['name'] = $name;
        }
        $this->mandrillParams['to'][] = $recipient;
        
        return $this;
    }
    
    /**
     * Add tag form Mandrill Outbound info
     * @param string $tag
     * @return Message  provides a fluent interface
     */
    public function addTag($tag)
    {
        if (!isset($this->mandrillParams['tags'])) {
            $this->mandrillParams['tags'] = array();
        }
        $this->mandrillParams['tags'][] = $tag;
        
        return $this;
    }
    
    /**
     * Enable a background sending mode that is optimized for bulk sending. In async mode, messages/send will immediately return a status of "queued" for every recipient. To handle rejections when sending in async mode, set up a webhook for the 'reject' event. Defaults to false for messages with no more than 10 recipients; messages with more than 10 recipients are always sent asynchronously, regardless of the value of async.
     * @param type $async 
     * @return Message  provides a fluent interface
     */
    public function setAsync($async = TRUE)
    {
        $this->mandrillParams['async'] = $async;
        
        return $this;
    }
    
    /**
     * Add another Mandrill param
     * @param string $param
     * @param string $value
     * @return Message  provides a fluent interface
     */
    public function setParam($param, $value)
    {
        $this->mandrillParams[$param] = $value;
        
        return $this;
    }
    
    /**
     * Returns Mandrill params
     * @return array
     */
    public function getMandrillParams()
    {
        return $this->mandrillParams;
    }
}