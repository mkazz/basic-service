<?php

namespace MKaczorowski\BasicService\DAOs;
use MKaczorowski\BasicService\Models as Models;

abstract class BaseDAO {

    protected
        $model,
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

    public function __construct($dbal, $model, $table_name) {
        $this->dbal   = $dbal;
        $this->model = $model;
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

    public function findById($id) {
        return $this->findBy("id", $id);
    }

    public function findAll() {
        $query = "SELECT * FROM {$this->table_name} ORDER BY name ASC";
        return $this->fetchAll($query);
    }

    public function findAllBy($field, $value) {
        if ($this->model->isFieldValid($field)) {
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
        if ($this->model->isFieldValid($field)) {
            $params = [$field => $value];
            $qb = $this->qb;
            $qb->select("*")
                ->from($this->table_name)
                ->where("{$field} = :{$field}");
            return $this->fetchAssoc($qb->getSQL(), $params);
        }

        throw new \Exception(get_called_class() . " - Field: {$field} is not a valid field");
    }

    public function findAllByLike($field, $value) {
      if ($this->model->isFieldValid($field)) {
          $params = [$field => "{$value}%"];
          $qb = $this->qb;
          $qb->select("*")
              ->from($this->table_name)
              ->where("$field LIKE :{$field}");

          return $this->fetchAll($qb->getSQL(), $params);
      }

      $this->error = "Field: {$field} is not a valid field";
      return false;
    }

    public function findAllByLikeWithParent($field, $value, $parent, $parent_id) {
      if ($this->model->isFieldValid($field)) {
          $parent_key = "{$parent}_id";
          $params = [
            $field => "{$value}%",
            $parent_key => $parent_id
          ];
          $qb = $this->qb;
          $qb->select("*")
              ->from($this->table_name)
              ->where(
                "$field LIKE :{$field}
                AND $parent_key = :{$parent_key}
                "
              );

          return $this->fetchAll($qb->getSQL(), $params);
      }

      $this->error = "Field: {$field} is not a valid field";
      return false;
    }

    public function findAllByParent($parent, $parent_id) {
        $parent_key = "{$parent}_id";
        $params = [
            $parent_key => $parent_id,
        ];

        $join_table = $this->model->getJoinTable($parent);
        if ($join_table !== null) {
            $qb = $this->qb;
            $qb->select("*")
                ->from($this->table_name, 'l')
                ->leftJoin(
                  'l',
                  $join_table,
                  'r',
                  "l.id = r.{$model->getLabel()}_id"
                )
                ->where("r.{$parent_key}  =  {$parent_id}");

            return $this->fetchAll($qb->getSQL(), $params);
        }
    }

    public function findAllByOperator($field, $value, $operator, $value2) {
        $params = [
            'value'     => $value,
            'value2'    => $value2,
        ];

        if ($this->model->isFieldValid($field)) {
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

    public function save(Models\BaseModel &$model) {
        $params = $this->buildModelQueryParams($model);
        $sets   = $this->buildModelSetParams($model);

        $query = "
            REPLACE INTO
                {$this->table_name}
            SET
                {$sets}";
        $result = $this->dbal->executeQuery($query, $params);
        if ($result->rowCount() > 0) {
            if (empty($params['id'])) {
                $model->id = $this->dbal->lastInsertId();
                $model->save_state = 'CREATED';
            }
            $model->save_state = 'UPDATED';
            return true;
        }
        return false;
    }

    protected function fetchAssoc($query, $params = []) {
        try {
            return $this->dbal->fetchAssoc($query, $params);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
        return false;
    }

    protected function fetchAll($query, $params = []) {
        try {
            return $this->dbal->fetchAll($query, $params);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }

    protected function write($query, $params = []) {
        $result = $this->dbal->executeQuery($query, $params);
        if ($result->rowCount() > 0) {
            return true;
        }

        return false;
    }

    protected function buildModelSetParams(Models\BaseModel $model) {
        $vars = get_object_vars($model);
        $sets = '';
        foreach ($vars as $field => $value) {
            if (
              $model->isFieldValid($field) &&
              !$model->isFieldReadOnly($field) &&
              !$model->isSynthetic($field)
            ) {
                $sets .= " {$field} = :{$field},\n";
            }
        }
        $sets = rtrim($sets, ",\n");
        return $sets;
    }

    protected function buildModelQueryParams(Models\BaseModel $model) {
        $vars = get_object_vars($model);
        $params = [];
        foreach ($vars as $field => $value) {
            if ($model->isFieldValid($field) && !$model->isSynthetic($field)) {
                $params[$field] = $value;
            }
        }
        return $params;
    }
}
