<?php
class Application_View_Helper_GoogleMaps extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/googleMaps.phtml';
    
    public function googleMaps($data)
    {
        if (count($data) == 1) {
            $data['center'] = "{$data[0]['lat']},{$data[0]['lang']}";
        } else {
            $data['center'] = 'Buenos Aires';
        }
        $data['markers'] = $this->_prepareMarkers($data);
        $this->_data = $data;
        return $this;
    }
    
    private function _prepareMarkers($data)
    {
        $markers = array();
        foreach($data as $d) {
            $markers[] = "markers=color:red|label:S|{$d['lat']},{$d['lang']}";
        }
        return implode('&', $markers);
    }
}
