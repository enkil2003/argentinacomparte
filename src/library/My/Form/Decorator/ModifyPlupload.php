<?php

class My_Form_Decorator_ModifyPlupload extends Zend_Form_Decorator_Abstract
{
    private $_images = null;
    
    private $_folder = null;
    
    public function setFolder($folder)
    {
        $this->_folder = $folder;
    }
    
    public function getFolder()
    {
        return $this->_folder;
    }
    
    public function setImages(array $images = array()) {
        $this->_images = $images;
    }
    
    public function getImages()
    {
        return $this->_images;
    }
    
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }
        
        $imagesHTML = '';
        
        if (is_array($this->_images)) {
            $lis = '';
            foreach($this->_images as $image) {
                $lis .= "    <li><img style=\"width: 100px; height: auto;\" src=\"/uploads/tmp/{$this->_folder}/{$image}\" /></li>\n";
            }
            
            $imagesHTML = <<<HTML
<div class="galleryImages">
  <ul>
{$lis}  </ul>
</div>
<style>
.galleryImages img {
    width: 100px;
    height: auto;
}
.galleryImages li {
    display: inline-block;
}
</style>
<script language="javascript">
var ImagesToDelete = [];
//$('.galleryImages img').on({
//    click: function() {
//        $(this).toggleClass('opacity');
//    }
//})
</script>
HTML;
        }
        
        return $content . $imagesHTML;
    }
}
