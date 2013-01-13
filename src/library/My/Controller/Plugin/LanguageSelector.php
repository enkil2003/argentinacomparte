<?php
/**
 * Vassilymas
 *
 * LICENSE http://www.gnu.org/licenses/gpl.txt
 *
 * This source file is subject to the GPL license that is bundled
 * with this package in the file LICENSE.txt located at the docs folder.
 *
 * @copyright  Copyright (c) 2011 and future, Ricardo Buquet
 * @license    http://www.gnu.org/licenses/gpl.txt     GPL  
 */

Class My_Controller_Plugin_LanguageSelector extends Zend_Controller_Plugin_Abstract
{
    /**
     * Default language to use if non is provided.
     * @var string
     */
    const DEFAULT_LANGUAGE = 'es';
    
    /**
     * Cache lifetime
     * @var int seconds
     */
    const CACHE_LIFETIME = 1892160000;
    
    /**
     * Language cookie lifetime
     * @var int seconds
     */
    const LANG_COOKIE_LIFETIME = 1892160000;
    
    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    protected $_supportedLanguages = array('en','es');
    
    /**
     * Locale to be used
     * @var string
     */
    protected $_locale = null;
    
    /**
     * Hook method to inject the translator
     * @see Zend_Controller_Plugin_Abstract::preDispatch()
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $lang = $this->_getDesiredLanguage();
        $this->_rememberLanguage($lang);
        $this->_setLocale($lang);
        $this->_configureCache();
        $this->_createTranslator($lang);
    }
    
    protected function _createTranslator($lang)
    {
        // translate source
        $translate = new Zend_Translate(
            array(
                'adapter' => 'gettext',
                'content' => APPLICATION_PATH . "/langs/{$this->_locale}/LC_MESSAGES/messages.mo",
                'locale'  => $lang
            )
        );
        Zend_Registry::set('Zend_Translate', $translate);
    }
    
    /**
     * Sets the locale
     * @param string $lang language
     */
    protected function _setLocale($lang)
    {
        if ($lang == 'en') {
            $locale = 'en_US';
        } else {
            $locale = 'es_AR';
        }
        $this->_locale = $locale;
        
        $zl = new Zend_Locale();
        $zl->setLocale($locale);
        Zend_Registry::set('Zend_Locale', $zl);
    }
    
    /**
     * Configures the cache to use with the translator
     * @return void
     */
    protected function _configureCache()
    {
        $cache = Zend_Cache::factory('Core',
            'File',
            array(
                'lifetime' => self::CACHE_LIFETIME,
                'automatic_serialization' => true
            ),
            array(
                'cache_dir' => APPLICATION_PATH . '/cache/'
            )
        );
        Zend_Translate::setCache($cache);
    }
    
    /**
     * Retrives the desired language
     * @return string language
     */
    protected function _getDesiredLanguage()
    {
        $lang = $this->getRequest()->getParam('lang', '');
        if ($lang == '') {
            $lang = $_COOKIE['lang'];
        }
        if ($lang == '') {
            $lang = self::DEFAULT_LANGUAGE;
        }
        return $lang;
    }
    
    /**
     * Saves the language cookie
     * @param string $lang language to be saved
     */
    protected function _rememberLanguage($lang)
    {
        setcookie('lang', $lang, time()+(self::LANG_COOKIE_LIFETIME));
        $this->getRequest()->setParam('lang', $lang);
    }
    
}