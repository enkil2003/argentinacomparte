<?php
require_once 'My/Application/Resource/ViewAbstract.php';
abstract class My_Application_Resource_HeadFileAbstract extends My_Application_Resource_ViewAbstract
{
    protected function _isMobile() {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(stripos($ua,'android') !== false && stripos($ua,'mobile') !== false) {
            return true;
        }
    }
}