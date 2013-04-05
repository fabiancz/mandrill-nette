<?php

namespace Fabian\Mandrill;

/**
 * Provides similar API to Nette\Mail\Message 
 */
class Message {
    /**
     * Mandrill object
     * @var Mandrill
     */
    private $mandrill;
    
    /**
     * Message parameters
     * @var array
     */
    private $params = array();
    
    public function __construct(Mandrill $mandrill)
    {
        $this->mandrill = $mandrill;
    }

    /**
    * Sets the sender of the message.
    * @param  string  email or format "John Doe" <doe@example.com>
    * @param  string
    * @return Message  provides a fluent interface
    */
    public function setFrom($email, $name = NULL)
    {
        $this->params['from_email'] = $email;
        if (!is_null($name)) {
            $this->params['from_name'] = $name;
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
        $this->params['text'] = $body;
        
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
        $this->params['html'] = $html;
        
        return $this;
    }
    
    /**
    * Sets the subject of the message.
    * @param  string
    * @return Message  provides a fluent interface
    */
    public function setSubject($subject)
    {
        $this->params['subject'] = $subject;
        
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
        if (!isset($this->params['to'])) {
            $this->params['to'] = array();
        }
        $recipient = array('email' => $email);
        if (!is_null($name)) {
            $recipient['name'] = $name;
        }
        $this->params['to'][] = $recipient;
        
        return $this;
    }
    
    /**
     * Add tag form Mandrill Outbound info
     * @param string $tag
     * @return Message  provides a fluent interface
     */
    public function addTag($tag)
    {
        if (!isset($this->params['tags'])) {
            $this->params['tags'] = array();
        }
        $this->params['tags'][] = $tag;
        
        return $this;
    }
    
    /**
     * Enable a background sending mode that is optimized for bulk sending. In async mode, messages/send will immediately return a status of "queued" for every recipient. To handle rejections when sending in async mode, set up a webhook for the 'reject' event. Defaults to false for messages with no more than 10 recipients; messages with more than 10 recipients are always sent asynchronously, regardless of the value of async.
     * @param type $async 
     * @return Message  provides a fluent interface
     */
    public function setAsync($async = TRUE)
    {
        $this->params['async'] = $async;
        
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
        $this->params[$param] = $value;
        
        return $this;
    }

    /**
     * Send email 
     */
    public function send()
    {
        $this->mandrill->call(
            '/messages/send',
            array('message' => $this->params)
        );
    }
}