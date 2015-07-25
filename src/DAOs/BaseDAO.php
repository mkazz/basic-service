<?php

namespace MKaczorowski\BasicService\DAOs;

use MKaczorowski\BasicService\Entities;

class BaseDAO {

    protected 
        $entity,
        $dbal,
        $error,
        $table_name,
        $operators = [
            'lessThan'              => '<',
            'greaterThan'           => '>',
            'lessThanOrEqualTo'     => '<=',
            'greaterThanOrEqualTo'  => '>=',
            'between'               => 'BETWEEN',
        ];

    public function __construct($dbal, $entity, $table_name) {
        $this->dbal   = $dbal;
        $this->entity = $entity;
        $this->table_name = $table_name;
    }

    public function getError() {
        return $this->error;
    }

    public function __get($name) {
        switch ($name) {
            case "qb":
                return $this->dbal->createQueryBuilder();
            default:
                return null;
        }
    }

    public function findById(Entities\BaseEntity $entity) {
        return $this->findBy("id", $entity->id);
    }

    public function findAllBy($field, $value) {
        $entity = $this->entity;
        if ($entity->isFieldValid($field)) {
            $params = [$field => $value];
            $qb = $this->qb;
            $qb->select("*")
                ->from($this->table_name)
                ->where("$field = :{$field}");

            return $this->fetchAll($qb->getSQL(), $params);
        }

        $this->error = "Field: {$field} is not a valid field";
        return false;
    }

    public function findBy($field, $value) {
        $entity = $this->entity;
        if ($entity->isFieldValid($field)) {
            $params = [$field => $value];
            $qb = $this->qb;
            $qb->select("*")
                ->from($this->table_name)
                ->where("{$field} = :{$field}");

            return $this->fetchAssoc($entity, $qb->getSQL(), $params);
        }

        throw new \Exception(get_called_class() . " - Field: {$field} is not a valid field");
    }

    public function findAllByOperator($field, $value, $operator, $value2) {
        $entity = $this->entity;
        $params = [
            'value'     => $value,
            'value2'    => $value2,
        ];

        if ($entity->isFieldValid($field)) {
            $qb = $this->qb;
            $qb->select("*")
                ->from($this->table_name);

            if ($operator == 'between') {
                $qb->where("{$field} BETWEEN :value AND :value2");
            } else {
                $qb->where("{$field} {$this->operators[$operator]} :value");
            }

            return $this->fetchAll($qb->getSQL(), $params);
        }

        throw new \Exception(get_called_class() . " - Field: {$field} is not a valid field");
    }

    public function save(Entities\BaseEntity &$entity) {
        $params = $this->buildEntityQueryParams($entity);
        $sets   = $this->buildEntitySetParams($entity);

        $query = "
            REPLACE INTO
                {$this->table_name}
            SET
                {$sets}";

        $result = $this->dbal->executeQuery($query, $params);
        if ($result->rowCount() > 0) {
            if (empty($params['id'])) {
                $entity->id = $this->dbal->lastInsertId();
            }
            return true;
        }
        return false;
    }

    protected function fetchAssoc(Entities\BaseEntity $entity, $query, $params = []) {
        try {
            $result = $this->dbal->fetchAssoc($query, $params);
            if (!empty($result)) {
                $entity->load($result);
                return $entity;
            }

            return null;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }

    protected function fetchAll($query, $params = [], $entity = null) {
        if ($entity === null) {
            $entity = $this->entity;
        }

        try {
            $entities = [];
            $result = $this->dbal->fetchAll($query, $params);
            if (!empty($result)) {
                $class_name = get_class($entity);
                foreach ($result as $row) {
                    $entity = new $class_name();
                    $entity->load($row);
                    $entities[] = $entity;
                }
            }

            return $entities;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }

    protected function buildEntitySetParams(Entities\BaseEntity $entity) {
        $vars = get_object_vars($entity);
        $entity_name = get_class($entity);
        $fresh_entity = new $entity_name($entity);
        $sets = '';

        foreach ($vars as $field => $value) {
            if ($fresh_entity->isFieldValid($field)) {
                $sets .= " {$field} = :{$field},\n";
            }
        }
        $sets = rtrim($sets, ",\n");

        return $sets;
    }

    protected function buildEntityQueryParams(Entities\BaseEntity $entity) {
        $vars = get_object_vars($entity);
        $entity_name = get_class($entity);
        $fresh_entity = new $entity_name($entity);
        $params = [];

        foreach ($vars as $field => $value) {
            if ($fresh_entity->isFieldValid($field)) {
                $params[$field] = $value;
            }
        }

        return $params;
    }

}

