<?php

namespace MKaczorowski\BasicService\DAOs;

use MKaczorowski\BasicService\Entities;

class BaseDAO {

    protected 
        $entity,
        $dbal,
        $error;

    public function __construct($dbal, $entity) {
        $this->dbal   = $dbal;
        $this->entity = $entity;
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

    protected function buildEntityQueryParams(Entities\BaseEntity $entity) {
        $vars = get_object_vars($entity);
        $params = [];

        foreach ($vars as $field => $value) {
            $params[$field] = ":{$field}";
        }

        return $params;
    }

}

