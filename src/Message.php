<?php

namespace Fabian\Mandrill;

/**
 * Provides similar API to Nette\Mail\Message
 */
class Message extends \Nette\Mail\Message
{
    /**
     * Message parameters
     * @var array
     */
    private $mandrillParams = [];



    /**
     * Sets the sender of the message.
     * @param string $email or format "John Doe" <doe@example.com>
     * @return static
     */
    public function setFrom(string $email, string $name = null)
    {
        $this->mandrillParams['from_email'] = $email;
        if ($name !== null) {
            $this->mandrillParams['from_name'] = $name;
        }

        return $this;
    }



    /**
     * Sets textual body.
     * @return static
     */
    public function setBody(string $body)
    {
        $this->mandrillParams['text'] = $body;

        return $this;
    }



    /**
     * Sets HTML body.
     * @return static
     */
    public function setHtmlBody(string $html, string $basePath = null)
    {
        $this->mandrillParams['html'] = $html;

        return $this;
    }



    /**
     * Sets the subject of the message.
     * @return static
     */
    public function setSubject(string $subject)
    {
        $this->mandrillParams['subject'] = $subject;

        return $this;
    }



    /**
     * Adds email recipient.
     * @param string $email or format "John Doe" <doe@example.com>
     * @return static
     */
    public function addTo(string $email, string $name = null)
    {
        if (!isset($this->mandrillParams['to'])) {
            $this->mandrillParams['to'] = [];
        }
        $recipient = ['email' => $email];
        if ($name !== null) {
            $recipient['name'] = $name;
        }
        $this->mandrillParams['to'][] = $recipient;

        return $this;
    }



    /**
     * Adds the reply-to address.
     * @param string $email or format "John Doe" <doe@example.com>
     * @return static
     */
    public function addReplyTo(string $email, string $name = null)
    {
        if (!isset($this->mandrillParams['headers'])) {
            $this->mandrillParams['headers'] = [];
        }
        $this->mandrillParams['headers']['Reply-To'] = $email;

        return $this;
    }



    /**
     * Add tag form Mandrill Outbound info
     * @return static
     */
    public function addTag(string $tag)
    {
        if (!isset($this->mandrillParams['tags'])) {
            $this->mandrillParams['tags'] = [];
        }
        $this->mandrillParams['tags'][] = $tag;

        return $this;
    }



    /**
     * Enable a background sending mode that is optimized for bulk sending. In async mode, messages/send will immediately return a status of "queued" for every recipient. To handle rejections when sending in async mode, set up a webhook for the 'reject' event. Defaults to false for messages with no more than 10 recipients; messages with more than 10 recipients are always sent asynchronously, regardless of the value of async.
     * @return static
     */
    public function setAsync(bool $async = true)
    {
        $this->mandrillParams['async'] = $async;

        return $this;
    }



    /**
     * Add another Mandrill param
     * @return static
     */
    public function setParam(string $param, string $value)
    {
        $this->mandrillParams[$param] = $value;

        return $this;
    }



    /**
     * Returns Mandrill params
     */
    public function getMandrillParams(): array
    {
        return $this->mandrillParams;
    }



    public function addBcc(string $email, string $name = null)
    {
        $this->mandrillParams['bcc_address'] = $email;

        return $this;
    }
}
