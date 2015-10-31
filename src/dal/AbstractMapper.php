<?php

/**
 * Created by PhpStorm.
 * User: Tal
 * Date: 31/10/2015
 * Time: 23:51
 */
abstract class AbstractMapper implements MapperInterface
{
    protected $adapter;
    protected $entityTable;
    protected $entityClass;

    public function __construct(DataBaseAdapterInterface $adapter, array $entityOptions = array()){
        $this->adapter = $adapter;
        if(isset($entityOptions['entityTable'])){
            $this->setEntityTable($entityOptions['entityTable']);
        }

        if(isset($entityOptions['entityClass'])){
            $this->setEntityClass($entityOptions['entityClass']);
        }

        $this->checkEntityOptions();
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setEntityTable($entityTable){
        if(!is_string($entityTable) || empty($entityTable)){
            throw new InvalidArgumentException('The entity table is invalid');
        }
        $this->entityTable = $entityTable;
        return $this;
    }

    public function getEntityTable(){
        return $this->entityTable;
    }

    public function setEntityClass($entityClass){
        if(!is_subclass_of($entityClass, 'ModelAbstractEntity')){
            throw new InvalidArgumentException('The entity class is invalid');
        }
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }

    protected function checkEntityOptions(){
        if(!isset($this->entityTable)){
            throw new RuntimeException('The entity table has not been set yet');
        }

        if(!isset($this->entityClass)){
            throw new RuntimeException('The entity class has not been set yet');
        }
    }

    public function findById($id){
        $this->adapter->select($this->entityTable, "id = $id");
        if($data = $this->adapter->fetch()){
            return $this->createEntity($data);
        }
        return null;
    }

    public function find($conditions=''){
        $collection = new EntityCollection;
        $this->adapter->select($this->entityTable, $conditions);
        while ($data = $this->adapter->fetch()){
            $collection[] = $this->createEntity($data);
        }
        return $collection;
    }

    public function insert($entity){
        if(!$entity instanceof $this->entityClass){
            throw new InvalidArgumentException('The entity to be inserted must be an instance of' .$this->entityClass);
        }
        return $this->adapter->insert($this->entityTable, $entity->toArray());
    }

    public function update($entity){
        if($entity instanceof $this->entityClass){
            throw new InvalidArgumentException('The entity to be updated must be an instance of' .$this->entityClass);
        }

        $id = $entity->id;
        $data = $entity->toArray();
        unset($data['id']);
        return $this->adapter->update($this->entityTable, $data, "id = $id");
    }

    public function delete($id, $col='id'){
        if($id instanceof $this->entityClass) {
            $id = $id->id;
        }
        return $this->adapter->delete($this->entityTable, "$col = $id");
    }

    abstract protected function createEntity(array $data);

}