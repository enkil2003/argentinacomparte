<?php
/**
 * 
 * Fancybox 2 wrapper
 * @link http://fancyapps.com/fancybox/#examples
 * @author ricardo
 * @example
 * echo $this->fancyBox(
 *     array(
 *         'selector' => '#pirulo',
 *         'target' => '#miCaja',
 *         'selector' => '#miModal',
 *         'text' => 'login',
 *         'options' => array(
 *             'mouseWheel'     => false, //    - default false 
 *             'defaultCss' => true, //         - default true,
 *             'thumbnail' => false, //         - default false
 *             'thumbnailCss' => true, //       - default true, but will only be loaded if thumbnail option is set to true
 *             'buttons' => false, //           - default false
 *             'buttonsCss' => true, //         - default true, but will only be loaded if buttons option is set to true
 *         ),
 *         'fancyboxOptions' => array(
 *             'helpers' => array(
 *                 'overlay' => array(
 *                     'opacity' => .01, // 0 to 1 but if you want the overlay to block interaction with background in IE you must use .01
 *                     'css' => array(
 *                         'background-color' => '#000'
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * );
 *
 */
class My_View_Helper_Fancybox extends Zend_View_Helper_Abstract
{
    const VERSION = '2.0.4';
    
    private $_jsSrcScripts = array();
    
    private $_cssHrefLinks = array();
    
    private $_options = array(
        'mouseWheel' => false,
        'defaultCss' => true,
        'thumbnail' => false,
        'thumbnailCss' => true,
        'buttons' => false,
        'buttonsCss' => true,
        'title' => '',
        'template' => '<a href="%s" %s="%s" title="%s">%s</a>'
    );
    
    private static $_alreadyLoaded = array(
        'fancyBox' => array(
            'loaded' => false,
            'src' => '/js/lib/jquery/fancybox/jquery.fancybox.pack.js?v=2.0.4',
            'type' => 'js'
        ),
        'mouseWheel' => array(
            'loaded' => false,
            'src' => '/js/lib/jquery/fancybox/jquery.mousewheel-3.0.6.pack.js',
            'type' => 'js'
        ),
        'defaultCss' => array(
            'loaded' => false,
            'src' => '/js/lib/jquery/fancybox/jquery.fancybox.css?v=2.0.4',
            'type' => 'css'
        ),
        'buttons' => array(
            'loaded' => false,
            'src' => '/js/lib/jquery/fancybox/helpers/jquery.fancybox-buttons.js?v=2.0.4',
            'type' => 'js'
        ),
        'buttonsCss' => array(
            'loaded' => false,
            'src' => '/js/lib/jquery/fancybox/helpers/jquery.fancybox-buttons.css?v=2.0.4',
            'type' => 'css'
        ),
        'thumbnail' => array(
            'loaded' => false,
            'src' => '/js/lib/jquery/fancybox/helpers/jquery.fancybox-thumbs.js?v=2.0.4',
            'type' => 'js'
        ),
        'thumbnailCss' => array(
            'loaded' => false,
            'src' => '/js/lib/jquery/fancybox/helpers/jquery.fancybox-thumbs.css?v=2.0.4',
            'type' => 'css'
        )
    );
    
    /**
     * Returns a fully configured js fancybox 2.0 instance
     * @param array $options
     */
    public function fancybox(array $options = null)
    {
        // override default options if any
        if (isset($options['options']) && null !== $options['options']) {
            $this->_options = array_merge(
                $this->_options,
                $options['options']
            );
        }
        
        unset($options['options']);
        $this->_fancyboxOptions = $options['fancyboxOptions'];
        unset($options['fancyboxOptions']);
        if (is_array($options)) {
            $this->_options = array_merge($this->_options, $options);
        }
        $this->_init();
        
        return $this->_fancyCode();
    }
    
    /**
     * Renders the fancybox code
     * @return string
     */
    private function _fancyCode()
    {
        // obtain id or class from selector to use in <a> tag
        $selectorType = substr($this->_options['selector'], 0, 1) === '#'? 'id':'class';
        $selector = substr($this->_options['selector'], 1);
        
        // prepare <a> tag
        $tpl = sprintf(
            $this->_options['template'],
            $this->_options['target'],
            $selectorType,
            $selector,
            $this->_options['title'],
            $this->_options['text']
        );
        
        // render a template if we have to
        $renderedTemplate = '';
        if (isset($this->_options['modalTemplate'])) {
            $renderedTemplate = $this->view->render($this->_options['modalTemplate']);
        }
        
        // attach js if we have to
        $jsScripts = '';
        foreach ($this->_jsSrcScripts as $jsScript) {
            $jsScripts .= "<script type='text/javascript' src='{$jsScript}'></script>" . PHP_EOL;
        }
        
        // attach css if we have to
        $cssLinks = '';
        foreach ($this->_cssHrefLinks as $cssLink) {
            $cssLinks .= "<link rel='stylesheet' href='{$cssLink}' type='text/css' media='screen, projection' />" . PHP_EOL;
        }
        
        // prepare html
        $fancyboxOptions = Zend_Json::encode($this->_fancyboxOptions, false, array('enableJsonExprFinder' => true));
        $fancyCode = <<<SCRIPT
{$cssLinks}
{$jsScripts}
{$tpl}
{$renderedTemplate}
<script type="text/javascript">
$(document).ready(function() {
    $("{$this->_options['selector']}").fancybox({$fancyboxOptions});
});
</script>
SCRIPT;
        // prevent already loaded js and css, to be loaded on next calls to the helper, as it acts like a singleton
        $this->_jsSrcScripts = array();
        $this->_cssHrefLinks = array();
        return $fancyCode;
    }
    
    /**
     * Loads the a css or js file
     * @param string $option the js or css file to load
     * @return void
     */
    public function loadFile($option)
    {
        if (!self::$_alreadyLoaded[$option]['loaded']) {
            self::$_alreadyLoaded[$option]['loaded'] = true;
            switch(self::$_alreadyLoaded[$option]['type']) {
                case 'js':
                    $this->_jsSrcScripts[] = self::$_alreadyLoaded[$option]['src'];
                    break;
                case 'css':
                    $this->_cssHrefLinks[] = self::$_alreadyLoaded[$option]['src'];
                    break;
            }
        }
    }
    
    /**
     * Initializes the helper
     * @return void
     */
    private function _init()
    {
        // load default css
        if ($this->_options['defaultCss']) {
            $this->loadFile('defaultCss');
        }
        
        // load fancybox
        $this->loadFile('fancyBox');
        
        // load mouse wheel support if needed
        if ($this->_options['mouseWheel']) {
            $this->loadFile('mouseWheel');
        }
        
        // load thumbnails helper if needed
        if ($this->_options['thumbnail']) {
            $this->loadFile('thumbnail');
            if ($this->_options['thumbnailCss']) {
                $this->loadFile('thumbnailCss');
            }
        }
        
        // load buttons helper if needed
        if ($this->_options['buttons']) {
            $this->loadFile('buttons');
            if ($this->_options['buttonsCss']) {
                $this->loadFile('buttonsCss');
            }
        }
    }
}