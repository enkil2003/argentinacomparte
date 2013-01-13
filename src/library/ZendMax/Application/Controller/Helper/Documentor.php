<?php

class Exts_Controller_Action_Helper_ServerDocumentator extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * list the server methods.
     */
    public function generateDoc ($serverClassName, $serverAlias, $view)
    {

        //$action->view->className = $serverClass;
        

        $serverClass = new $serverClassName();
        
        $zc = new Zend_Reflection_Class($serverClassName);
        //$zr = new Zend_Reflection_File( $zc->getDeclaringFile() );
        

        $res = $this->parseClass(null, $zc);
        $res['serverAlias'] = $serverAlias;
        
        // extraer nombre del module
        $res['module'] = "";
        if (preg_match('/^(.*)WS_(.*)$/', $serverClassName, $matches)) {
            $res['module'] = strtolower($matches[1]);
        }
        $res['class'] = strtolower($serverAlias);
        
        return $res;
    }

    // parses a Zend_Reflection_Class object
    public function parseClass ($zr, $class)
    {

        // read class docs
        $docBlock = $class->getDocblock();
        $classShortComment = $docBlock->getShortDescription();
        $classLongComment = $docBlock->getLongDescription();
        
        // load class info
        $classDoc = array('className' => $class->getName(), 
            'shortComment' => $classShortComment, 
            'longComment' => $classLongComment );
        
        // Read methods
        $methods = array();
        foreach ($class->getMethods() as $methodObj) {
            try {
                $methods[] = $this->parseMethod($methodObj);
            } catch (Exception $e) {}
        }
        
        $classDoc['methods'] = $methods;
        
        return $classDoc;
    }

    // parses a Zend_Reflection_Method object
    public function parseMethod ($methodObj)
    {

        $return = 'void';
        
        $docBlock = $methodObj->getDocblock();
        $shortDesc = $docBlock->getShortDescription();
        $longDesc = $docBlock->getLongDescription();
        
        // read method tags
        $tags = $docBlock->getTags();
        
        // parse them
        $paramDoc = array();
        foreach ($tags as $tag) {
            
            if ($tag->getName() == 'return') {
                $return = $tag->getType();
                $returnDesc = $tag->getDescription();
            }
            if ($tag->getName() == 'param') {
                $paramDoc[] = array('name' => $tag->getVariableName(), 
                    'type' => $tag->getType(), 
                    'desc' => trim($tag->getDescription()) );
            }
        
        }
        
        $method = array();
        $method['name'] = $methodObj->getName();
        $method['shortDesc'] = $shortDesc;
        $method['longDesc'] = $longDesc;
        $method['returnType'] = $return;
        $method['returnDesc'] = $returnDesc;
        $method['params'] = $paramDoc;
        
        return $method;
    }
}