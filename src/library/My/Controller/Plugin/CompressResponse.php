<?php
require_once 'Zend/Controller/Plugin/Abstract.php';
class My_Controller_Plugin_CompressResponse extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopShutdown()
    {
        $content = $this->getResponse()->getBody();
        $content = preg_replace(
            array(
                '/(\x20{2,})/',   // extra-white spaces
                '/\t/',           // tab
                '/\n\r/'          // blank lines
            ),
            array(' ', '', ''),
            $content
        );
        $this->getResponse()->setBody($content);;
        /*
        // if the browser does not support gzip, serve the stripped content
        if (@strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === FALSE) {
            $this->getResponse()->setBody($content);
        } else {
            header('Content-Encoding: gzip');
            $this->getResponse()->setBody($content);
        }
        */
    }
}
