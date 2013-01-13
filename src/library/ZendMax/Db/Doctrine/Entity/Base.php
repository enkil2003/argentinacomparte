<?php 

namespace ZendMax\Db\Doctrine\Entity;

class Base
{
    /**
     * Ways of set a protected property of an entity
     * with only $entity->name = $value
     *  Priorities:
     *  1) $this->setName($value)
     *  2) $this->name = $value
     * 
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws \Exception 
     */
    public function __set($name, $value )
    {
        $methodSet = 'set' . ucfirst($name);
        if(method_exists($this, $methodSet)){
            return $this->$methodSet($value);
        }
        if(property_exists($this, $name)) {
            return $this->$name  = $value;
        }
        throw new \Exception("The property {$name}, dosen't exists in " .
                                        get_class($this));
    }
    
    /**
     * Ways of get a protected property of an entity
     * with only $entity->name
     *  Priorities:
     *  1) $this->getName()
     *  2) $this->name
     * 
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws \Exception 
     */    
    public function __get($name)
    {
        $methodGet = 'get' . ucfirst($name);
        if(method_exists($this, $methodGet)){
            return $this->$methodGet();
        }        
        if( property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \Exception("The property {$name}, dosen't exists in " . 
                                        get_class($this));
    }

    /**
     * Convention: only to use inside Entities, is a way to put 
     * an instance in the other side of a bidirectional relation,
     * solving a recursive problem.
     * 
     * @param \Model\Entity $obj
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws \Exception 
     */
    public function _inyect($name, $value)
    {
        if(property_exists($this, $name)) {
            return $this->$name  = $value;
        }
        throw new \Exception("The property {$name}, dosen't exists in " .
                                        get_class($this));
    }
    
   /**
    * Allow to fill many fields in a single operation.
    * not allow associative fields.
    *
    * @param array $data
    * @return \Etermax\Db\Doctrine\Entity
    */
    public function setFromArray(array $data)
    {
        $metaData = \Zend_Registry::get('doctrine')
                                ->getEntityManager()
                                ->getClassMetadata(get_class($this));
        foreach ($data as $columnName => $value) {
            if($metaData->hasField($columnName)){
                $this->$columnName = $value;
            }
        }
        return $this;
    }

    /**
     * Returns the entity in array form
     * only return the data that exist, not do any query more
     * if $assoc = false, only return the no-assoc fields
     * max deep = sub arrays,.
     * 
     * @param bool $assoc
     * @param bool $hideNull hide null field values
     * @return array
     */
    public function toArray($assoc = true, $hideNull = false)
    {
        $metaData = \Zend_Registry::get('doctrine')
                                ->getEntityManager()
                                ->getClassMetadata(get_class($this));
        $result = array();
        
        $fieldsNames = $metaData->getFieldNames();
        foreach($fieldsNames as $fieldName){
            if($hideNull){
                if($this->$fieldName === null){
                    continue;
                }
            }
            $result[$fieldName] = $this->$fieldName;    
        }
        if(!$assoc){
            return $result;
        }
        
        $assocNames = $metaData->getAssociationNames();
        foreach($assocNames as $assocName){
            if($metaData->isCollectionValuedAssociation($assocName)){
                $snapshot = $this->$assocName->getSnapshot();
                if(empty($snapshot)){
                    if(!$hideNull){
                        $result[$assocName] = array();
                    }
                } else {
                    $result[$assocName] = array();
                    for($i = 0; $i < count($snapshot); $i++){
                        $result[$assocName][] = $snapshot[$i]->toArray(false, $hideNull);
                    }
                }
            } else {
                if($this->$assocName instanceof \Doctrine\ORM\Proxy\Proxy 
                || $this->$assocName === null){
                    if(!$hideNull){
                        $result[$assocName] = null;
                    }
                } else {
                    $result[$assocName] = $this->$assocName->toArray(false, $hideNull);
                }
                
            }
        }
        
        return $result;        
    }

}
