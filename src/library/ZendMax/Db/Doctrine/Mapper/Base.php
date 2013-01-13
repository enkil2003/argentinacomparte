<?php 

namespace ZendMax\Db\Doctrine\Mapper;

use \ZendMax\Db\Doctrine as EterDoctrine;
use \ZendMax\Db\Doctrine\Mapper\Exception as Exception;

class Base
{
    /**
     * Used in pagination 
     * @var int
     */
    const QUERY_LIMIT_DEFAULT = 10;
    
    /**
     * Used to fetch data as arrays
     * @var string
     */
    const RESULT_TYPE_ARRAY = 'array';
    
    /**
     * Used to fetch data as entities
     * @var string
     */    
    const RESULT_TYPE_OBJECT = 'object';
    
    /**
     * Entity Manager
     * 
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em;
    
    /**
     * Repository of this Entity type
     * 
     * @var Doctrine\ORM\EntityRepository
     */
    protected $_repository;

    /**
     * Entity Class Name
     * 
     * @var string
     */    
    protected $_entityClassName;
    
    /**
     * Meta Data Information about the entity
     * 
     * @var \Doctrine\ORM\Mapping\ClassMetadata
     */    
    protected $_metaData;
    
    
    public function __construct()
    {
        $this->_em = \Zend_Registry::get('doctrine')
                                    ->getEntityManager();
        $this->_entityClassName = str_replace('Mapper', 'Entity', get_class($this));
        $this->_repository = $this->_em
                        ->getRepository($this->_entityClassName);
        $this->_metaData = $this->_em
                        ->getClassMetadata($this->_entityClassName);
    }
    
    /**
     * Verify the correct names of properties of an Entity
     * 
     * Given an associative array
     * ('field1', 'field2Assoc', array(
     *   'field3Assoc' => array('subField', 'subFieldAssoc'))
     * )
     * 
     * return an array where
     *  * fields columns are preserved
     *  * from the assoc properties only return the non subassoc properties
     *  * if a assocProp is passed as string it means all the field columns
     * ( 'field1', 
     *   array('field2Assoc' => array(fieldProps, ...), 
     *   array('field3Assoc' => array('subField'))
     * )
     * 
     * @param array $properties 
     * @param string $entityClassName
     * @return array
     * 
     * @throws Exception::$assocPropNotAllowed  
     *                      sub association properties are not allowed
     * @throws Exception::$propertyNotExist
     *                      When the property not exist
     * @throws Exception::dataNoValid
     *                      When the properties doesn't has any correct structure
     */
    protected function _verifyProps(
            array $properties, 
            $addId = false,
            $entityClassName = null,
            $assocStep = false
    )
    {
        if($entityClassName == null){
            $entityClassName = $this->_entityClassName;
            $metaData = $this->_metaData;
        } else {
            $metaData = $this->_em->getClassMetadata($entityClassName);
        }
        
        // Add defualt Ids props to the list
        if($addId){
            $ids = $metaData->getIdentifier();
            foreach($ids as $idField){
                if(array_search($idField, $properties) === false){
                    array_unshift($properties, $idField);
                }
            }
        }
        
        $filtered = array();
        
        foreach ($properties as $key => $value){

            if(is_int($key)){
                if(is_string($value)){
                    if($metaData->hasField($value)){
                        $filtered[] = $value;
                    } elseif($metaData->hasAssociation($value)) {
                        if($assocStep){
                            throw new Exception(array(
                                'prop'    => '@assocPropNotAllowed', 
                                'extend' => 'sub property: ' . $value));
                        }                
                        $entityClassNameAssoc = 
                               $metaData->getAssociationTargetClass($value);
                        $metaDataAssoc = $this->_em
                                ->getClassMetadata($entityClassNameAssoc);
                        $filtered[$value] = $metaDataAssoc->getColumnNames();                        
                    } else {
                        throw new Exception(array(
                            'prop'    => '@propertyNotExist', 
                            'extend' => 'Verify the name: ' . $value));                        
                    }
                } else {
                    throw new Exception(array(
                        'prop'    => '@dataNoValid', 
                        'extend' => 'Bad formed the properties'));                    
                }
            } else {
                if($metaData->hasAssociation($key)){
                    if($assocStep){
                        throw new Exception(array(
                            'prop'    => '@assocPropNotAllowed', 
                            'extend' => 'sub property: ' . $key));
                    }
                    if(is_array($value)){
                        $entityClassNameAssoc = 
                            $metaData->getAssociationTargetClass($key);
                        if(!empty($value)){
                            $assocProps = $this->_verifyProps($value, $addId, 
                                                        $entityClassNameAssoc, true);
                            if(!empty($assocProps)){
                                $filtered[$key] = $assocProps;
                            }
                        } else {
                            $metaDataAssoc = $this->_em
                                ->getClassMetadata($entityClassNameAssoc);
                            $filtered[$key] = $metaDataAssoc->getColumnNames();
                        }
                    } else {
                        throw new Exception(array(
                                'prop'    => '@dataNoValid', 
                                'extend' => 'Bad formed the properties'));            
                    }                    
                } else {
                    throw new Exception(array(
                        'prop'    => '@propertyNotExist', 
                        'extend' => 'Verify the name: ' . $key));                    
                }
            }
        }
        
        return $filtered;
    }
    
    /**
     * Given an associative array where the keys are
     * array(
     *      'field' => ...,
     *      'fieldAssc.field2' => ...,
     *      ...
     * )
     * Verify the same rules as self::_veryProps()
     * 
     * @param array $params 
     * @return array
     */
    protected function _verifyKeyProps(array $params)
    {
        foreach($params as $prop => $value){
            $propParts = explode('.', $prop);
            if(count($propParts) == 1){
                //is a field prop
                $result = $this->_verifyProps(
                    array($propParts[0]), false);
            } else {
                //is a assoc prop
                $result = $this->_verifyProps(
                    array($propParts[0] => array($propParts[1])), false);
            }
        }
         return $params;
    }
    
    /**
     * Parse a Where clause
     * 
     * Given a string like  "states.id > 300 AND states.id < 400",
     * 
     * return an array like:
     * array(
     *      array('logic' => 'AND', 'prop' => 'states.id', 
     *               'comp' => '>', 'value' => 300),
     *      array('logic' => 'AND', 'prop' => 'states.id', 
     *              'comp' => '>', 'value' => 400),
     * )
     * 
     * @param string $findBy
     * @return array
     * 
     * @throws Exception::$whereWithErrors
     *                      is bad the structure of the where
     * @throws Exception::$dataNoValid
     *                      when the comparation sign not match
     */
    protected function _parseFindBy($findBy)
    {
        $findBy = trim($findBy);
        $wheres = preg_split('/\s?(OR|AND)\s?/', $findBy, -1, 
                                        PREG_SPLIT_DELIM_CAPTURE);
        for($i = 0; $i < count($wheres); $i += 2){
            $parts = preg_split('/[\s]+/', $wheres[$i], 3);
            if(count($parts) <  3){
                throw new Exception('@whereWithErrors');
            }
            $logic = ($i != 0) ? $wheres[$i - 1] : 'AND';
            $prop = $this->_verifyKeyProps(array($parts[0] => null));
            if(!in_array($parts[1], array('=', '!=', '<', '<=', '>', '>='))){
                throw new Exception(array(
                    'prop'    => '@dataNoValid', 
                    'extend' => 'Verify the comparation sign'));
            }
            $where[] = array(
                'logic' => $logic,
                'prop' => $parts[0],
                'comp' => $parts[1],
                'value' => $parts[2],
            );
        }
        
        return $where;
    }
    
    /**
     * Create the DQL query
     * 
     * @todo escapar los valores del where con usando 
     *            parameters de DQL
     * 
     * @param array $props
     * @return string
     */
    protected function _createGetDQL(
            array $props, array $orderBy = null, array $where = null)
    {
        $currentJoinIndex = 2;
        $selectJoins = $joinEntities = $selectFields = '';
        $joinsData = array();
        foreach($props as $key => $value){
            if(is_array($value)){
                $fields = '';
                foreach($value as $assocField){
                    $fields .= $assocField . ', ';
                }
                $fields = substr($fields, 0 , -2);
                $selectJoins .= 'partial j' . $currentJoinIndex . '.{' . $fields . '}, ';
                $joinEntities .= 'JOIN e1.' . $key . ' j' . $currentJoinIndex . ' ';
                $joinsData[$key] =  ' j' . $currentJoinIndex;
                $currentJoinIndex++;
            } else {
                $selectFields .= $value . ', ';
            }
        }
        
        $orderByProps = '';
        if(!empty($orderBy)){
            foreach($orderBy as $prop => $orderDir){
                $orderDir = strtoupper($orderDir);
                if(!($orderDir === 'ASC' || $orderDir === 'DESC')){ 
                    continue;
                }
                $prop = explode('.', $prop);
                if(count($prop) == 1){
                    //is a field prop
                    $orderByProps .= 'e1.' . $prop[0] . ' '. $orderDir . ', ';
                } else {
                    //is a assoc prop
                    if(isset($joinsData[$prop[0]])){
                        //not add the join, was before
                        $orderByProps .= $joinsData[$prop[0]] . '.' 
                                              . $prop[1] . ' '. $orderDir . ', ';
                    } else {
                        //add the join
                        $joinEntities .= 'JOIN e1.' . $prop[0] 
                                          . ' j' . $currentJoinIndex . ' ';
                        $joinsData[$prop[0]] =  ' j' . $currentJoinIndex;
                        $currentJoinIndex++;
                        $orderByProps .= $joinsData[$prop[0]] . '.' 
                                              . $prop[1] . ' '. $orderDir . ', ';
                    }
                }
            }
        }
        
        $whereDql = '';
        if(!empty($where)){
            for($i = 0; $i < count($where); $i++){
                if($i == 0){
                    $where[$i]['logic'] = '';
                }
                $prop = explode('.', $where[$i]['prop']);
                if(count($prop) == 1){
                    //is a field prop
                    $whereDql .= $where[$i]['logic'] . ' e1.' . $prop[0] . ' '
                                    . $where[$i]['comp'] . ' ' . $where[$i]['value'] . ' ';
                } else {
                    //is a assoc prop
                    if(isset($joinsData[$prop[0]])){
                        //not add the join, was before
                        $whereDql .= $where[$i]['logic'] . $joinsData[$prop[0]] . '.' 
                                        . $prop[1] . ' '. $where[$i]['comp'] . ' ' 
                                        . $where[$i]['value'] . ' ';
                    } else {
                        //add the join
                        $joinEntities .= 'JOIN e1.' . $prop[0] 
                                           . ' j' . $currentJoinIndex . ' ';
                        $joinsData[$prop[0]] =  ' j' . $currentJoinIndex;
                        $currentJoinIndex++;
                        $whereDql .= $where[$i]['logic'] . $joinsData[$prop[0]] . '.' 
                                        . $prop[1] . ' '. $where[$i]['comp'] . ' ' 
                                        . $where[$i]['value'] . ' ';
                    }
                }
            }
        }
        
        if(!empty($selectFields)){
            $selectFields = substr($selectFields, 0 , -2);
            $selectFields = 'partial e1.{' . $selectFields . '} ';
        }
        $select = "SELECT DISTINCT {$selectFields}";
        if(!empty($selectJoins)){
            $selectJoins = substr($selectJoins, 0 , -2);
            $select .= ", {$selectJoins}";
        }
        $from = "FROM {$this->_entityClassName} e1";
        if(!empty($orderByProps)){
            $orderByProps = 'ORDER BY ' . substr($orderByProps, 0 , -2);
        }
        if(!empty($whereDql)){
            $whereDql = 'WHERE' . $whereDql;
        }                
        
        $dql = "{$select} {$from} {$joinEntities} 
                    {$whereDql} {$orderByProps} ";
        
        return $dql;
    }
    
    /**
     * Get an Entity
     * 
     * $params['fields']:
     * associations properties came from arrays or strings
     * if array is empty means all the entity association.
     * - fields: indexed array 
     *      ("field1", "fieldAssoc" => array("subField1", ...), ...)
     *      ("field1", "fieldAssoc" => array(), ...)
     *      ("field1", "fieldAssoc" , ...)
     * 
     * @param int $id
     * @param array $params          
     * @param array|object $resultType
     * @return null|array|\Etermax\Db\Doctrine\Entity
     * 
     * @throws Exception::$dataTypeNoInteger
     *                      when entity Id is not integer
     * @throws Exception::$dataTypeNoArray
     *                      when "field" parameter is not array
     * @throws Exception::$dataArrayNoKeyField
     * @throws Exception::$dataNoValid
     *                      result type must be 'array' or 'object'
     */
    public function get($id, array $params = null, 
                                $resultType = self::RESULT_TYPE_OBJECT)
    {
        if(!is_int($id)){
               throw new Exception(array(
                    'prop'    => '@dataTypeNoInteger', 
                    'extend' => 'Verify the entity id'));
        }
        if($params == null){
            //return the Entity
            return $this->_repository->find($id);
        } else {
            //return an array/partial representation of the Entity
            if(!isset($params['fields'])){
                throw new Exception('@dataArrayNoKeyField');
            }
            if(!is_array($params['fields'])){
               throw new Exception(array(
                    'prop'    => '@dataTypeNoArray', 
                    'extend' => 'Verify "fields" parameter' ));
            }
            if($resultType != 'array' && $resultType != 'object'){
               throw new Exception(array(
                    'prop'    => '@dataNoValid',
                    'extend' => 'Verify $resultType' ));
            }
            
            if(empty($params['fields'])){
                $props = $this->_metaData->getColumnNames();
            } else {
                $props = $this->_verifyProps($params['fields'], true);
            }
            $dql = $this->_createGetDQL($props);
            $dql .= " WHERE e1.id = {$id}";
            $query = $this->_em->createQuery($dql);
            
            $result = $query->getOneOrNullResult(
                                ($resultType == self::RESULT_TYPE_ARRAY)?
                                    \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY:
                                    \Doctrine\ORM\AbstractQuery::HYDRATE_OBJECT
            );
            
            return $result;
        }
    }

    /**
     * Get a list of entities, there are restrictions 
     * that can reduce the result set
     * 
     * Entities:
     * entities: indexed array(id1, id2, ...., idn)
     * 
     * Paginated:
     * - paginated: associative array (offset => int, limit => int )
     * 
     * Order:
     * (no admite associations properties)
     * - orderBy: associative array ('filed1' => 'ASC', ...)
     * 
     * Search
     * - findBy: "states.id > 100 AND states.id < 400 OR name = 'Argentina'"
     * 
     * Short Result
     * - fields: indexed array (fieldNames, ...)
     * 
     * @param null|array $params 
     * @return null|array
     * 
     * @throws Exception::$dataTypeNoArray
     *                      "fields" must be an array
     *                      "orderBy" must be an array
     * @throws Exception::$assocPropNotAllowed
     *                      "orderBy" can't have a assocProp
     * @throws Exception::$dataTypeNoString
     *                      "findBy" must be a string
     */
    public function getMany(array $params = null, 
                                        $resultType = self::RESULT_TYPE_OBJECT) 
    {  
        $hasJoins = false;
        
        /*
         * Fields  
         */
        if(!isset($params['fields'])){
            //$params['fields'] = array('id');
            $params['fields'] = $this->_metaData->getColumnNames();
        }
        if(!is_array($params['fields'])){
            throw new Exception(array(
                    'prop'    => '@dataTypeNoArray', 
                    'extend' => 'Verify "fields" parameter' ));
        }       
        $props = $this->_verifyProps($params['fields'], true);
        $hasJoins = isset($props[count($props) - 1])? false : true;

        /*
         * OrderBy
         */
        $orderProps = null;
        if(isset($params['orderBy'])){
            if(!is_array($params['orderBy'])){
                throw new Exception(array(
                        'prop'    => '@dataTypeNoArray', 
                        'extend' => 'Verify "orderBy" parameter' ));
            }
            $orderProps = $this->_verifyKeyProps($params['orderBy']);
            foreach($orderProps as $key => $value){
                //no permito que existan orderBY con associations, da errores con el paginado
                if(strpos($key, '.') !== false){
                    throw new Exception(array(
                        'prop'    => '@assocPropNotAllowed', 
                        'extend' => 'Verify property: ' . $key));
                }
                $hasJoins |= strpos($key, '.') !== false;
            }
        }
        
        /*
         * FindBy
         */        
        $whereParsed = null;
        if(isset($params['findBy'])){
            if(!is_string($params['findBy'])){
                throw new Exception(array(
                    'prop'    => '@dataTypeNoString', 
                    'extend' => 'Verify "findBy" parameter' ));
            }
            $whereParsed = $this->_parseFindBy($params['findBy']);
            for($i = 0; $i < count($whereParsed); $i++){
                $hasJoins |= strpos($whereParsed[$i]['prop'], '.') !== false;
            }            
        }
        
        $dql = $this->_createGetDQL($props, $orderProps, $whereParsed);
        $query = $this->_em->createQuery($dql);

        /*
         * Paginated
         */        
        $query->setMaxResults(self::QUERY_LIMIT_DEFAULT);
        if(isset($params['paginated'])){
            if(isset($params['paginated']['offset'])){
                $query->setFirstResult($params['paginated']['offset']);
            }
            if(isset($params['paginated']['limit'])){
                $query->setMaxResults($params['paginated']['limit']);
            }
        }
        $query->setHydrationMode(
                ($resultType == self::RESULT_TYPE_ARRAY)?
                    \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY:
                    \Doctrine\ORM\AbstractQuery::HYDRATE_OBJECT);
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query, (bool)$hasJoins);
        $result = (array) $paginator->getIterator();
        if(empty($result)){
            $result = null;
        }
        
         return $result; 
    }    
    
    /**
     * Create an Entity from an array of data
     * 
     * @param array $entity
     * @return bool
     */
    public function create(array $entity)
    {
        
    }

    /**
     * Create many entities from an indexed array that 
     * have many associative arrays with data
     * not accept association properties by default
     * 
     * @param array $entities
     * @return void
     */
    public function createMany(array $entities)
    {
        $this->_em->getConnection()->beginTransaction();
        try {
            for($i = 0; $i < count($entities); $i++){
                $this->create($entities[$i]);
            }
            $this->_em->flush();
            $this->_em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            if($e instanceof \Etermax\Db\Doctrine\Exception){
                $e->extendMsg('Verify the entity in the array position: ' . $i);
            } else {
                $e = new Exception(array(
                    'prop'    => '@internalError', 
                    'extend' => 'Verify the entity in the array position: ' . $i));
            }
            throw $e;
        }
    }
    
    /**
     * Update an Entity from an array of data or 
     * from the same Entity
     * 
     * @param array|Etermax\Db\Doctrine\Entity\Base $entity
     */    
    public function update($entity)
    {
        
    }

    /**
     * Update many Entities
     * entities come from arrays of entities or array of arrays (of data)
     * 
     * @param array|\Model\Entity\Base $entities
     */    
    public function updateMany(array $entities)
    {
        $this->_em->getConnection()->beginTransaction();
        try {
            for($i = 0; $i < count($entities); $i++){
                $this->update($entities[$i]);
            }
            $this->_em->flush();
            $this->_em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            if($e instanceof \Etermax\Db\Doctrine\Exception){
                $e->extendMsg('Verify the entity in the array position: ' . $i);
            } else {
                $e = new Exception(array(
                    'prop'    => '@internalError', 
                    'extend' => 'Verify the entity in the array position: ' . $i));
            }
            throw $e;
        }                
    }
    
    /**
     * Delete an Entity
     * entities come from arrays of entities or array of arrays (of data)
     * 
     * @param int|Etermax\Db\Doctrine\Entity\Base $entity 
     */
    public function delete($entity)
    {
        
    }
    
    /**
     * Delete many Entities
     * 
     * 
     * @param array $entities
     */
    public function deleteMany(array $entities)
    {
        $this->_em->getConnection()->beginTransaction();
        try {
            for($i = 0; $i < count($entities); $i++){
                $this->delete($entities[$i]);
            }
            $this->_em->flush();
            $this->_em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            if($e instanceof \Etermax\Db\Doctrine\Exception){
                $e->extendMsg('Verify the entity in the array position: ' . $i);
            } else {
                $e = new Exception(array(
                    'prop'    => '@internalError', 
                    'extend' => 'Verify the entity in the array position: ' . $i));
            }
            throw $e;
        }        
    }
}
