<?php
class My_Log_Writer_Backlog extends Zend_Log_Writer_Mail
{
    /**
     * Array of all events sent to writer, unfiltered.
     *
     * @var array
     */
    protected $_eventHistory = array();
    
    /**
     * Create a new instance of Zend_Log_Writer_Mail
     * 
     * @param  array|Zend_Config $config
     * @return Zend_Log_Writer_Mail
     * @throws Zend_Log_Exception
     */
    public static function factory($config)
    {
        $transport = new Zend_Mail_Transport_Smtp($config['transport']['url'],
                                                  $config['transport']['params']);
        
        $mail = new Zend_Mail($config['encoding']);
        $mail->setFrom($config['from'])
             ->addTo($config['to'])
             ->setDefaultTransport($transport);
        
        $writer = new self($mail);
        $writer->setSubjectPrependText($config['subject'])
               ->addFilter(Zend_Log::ERR);
        
        return $writer;
    }
    
    /**
     * Returns an array of all of the events sent to the writer thus far
     *
     * @return Array
     */
    public function getEventHistory()
    {
        return $this->_eventHistory;
    }
    
    /**
     * Log a message to this writer.
     *
     * @param  array     $event  log data event
     * @return void
     */
    public function write($event)
    {
        $this->_eventHistory[] = $event;
        
        return parent::write($event);
    }
    
    /**
     * Sends mail to recipient(s) if log entries are present.  Note that both
     * plaintext and HTML portions of email are handled here.
     *
     * @return void
     */
    public function shutdown()
    {
        // If there are events to mail, use them as message body.  Otherwise,
        // there is no mail to be sent.
        if (empty($this->_eventsToMail)) {
            return;
        }
        
        // Now add the backlog of messages for inclusion in the email
        foreach ($this->getEventHistory() as $event) {
            $this->_write($event);
        }
        
        return parent::shutdown();
    }
}