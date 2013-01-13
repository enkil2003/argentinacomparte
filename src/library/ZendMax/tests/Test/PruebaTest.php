<?php
require_once APPLICATION_PATH . '/Test/Prueba.php';

class PruebaTest extends PHPUnit_Framework_TestCase
{
    public function testSetName()
    {
        $value = 'Ricardo';
        $p = new Prueba();
        $p->setName($value);
        
        $this->assertEquals($value, $p->getName());
    }
    
    public function testGetName()
    {
        $value = 'Ricardo';
        $p = new Prueba();
        $p->setName($value);
        
        $this->assertEquals($value, $p->getName());
    }
    
    public function testGetNameUpper()
    {
        $value = 'Ricardo';
        
        $p = $this->getMock(
            'Prueba',
            array('setName', 'getName', '_getNameUpper', 'getNameUpper')
        );
        
        $p->setName($value);
        
        $xxx = $p->getName();
        
        echo $xxx."\n\n\n";
        die;
        
        $result = $p->getNameUpper();
        
        $this->assertEquals('RICARDO', $result);
        
    }
}