<?php

namespace MKaczorowski\BasicService\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MKaczorowski\BasicService\Entities\BaseEntity;
use MKaczorowski\BasicService\Models\BaseModel;

abstract class BaseController {

    protected
        $model_factory_key,
        $entity_factory_key;

    public function save(Application $app, Request $request) {
        $model  = $app[$this->model_factory_key];
        $entity = $app[$this->entity_factory_key];

        $entity->load($request->request->all());
        $result = $model->save($entity);

        $response = new JsonResponse($entity);
        $response->setStatusCode(201);

        if ($result !== true) {
            $response->setStatusCode(400);
        }

        return $response;
    }

    public function findById(Application $app, Request $request) {
        $model  = $app[$this->model_factory_key];
        $id     = $request->get('id');
        $entity = $model->findById($id);

        $response = new JsonResponse($entity);

        if (empty($entity)) {
            $response->setStatusCode(204);
        }

        return $response;
    }

    public function findBy(Application $app, Request $request) {
        $model  = $app[$this->model_factory_key];
        $field  = $request->get('field');
        $value  = $request->get('value');

        $entity = $model->findBy($field, $value);
        $response = new JsonResponse($entity);

        if (!$entity instanceof BaseEntity && $entity !== false) {
            $response->setStatusCode(204);
        } elseif ($entity === false) {
            $response->setStatusCode(400);
        }

        return $response;
    }

    public function findAllBy(Application $app, Request $request) {
        $field = $request->get('field');
        $value = $request->get('value');

        $model = $app[$this->model_factory_key];
        $entity = $app[$this->entity_factory_key];

        if (!$entity->isFieldValid($field)){
            $response = new JsonResponse(['error' => "$field is not a valid field"]);
            $response->setStatusCode(400);
            return $response;
        }

        $entities = $model->findAllBy($field, $value);
        $response = new JsonResponse($entities);

        if (empty($entities)) {
            $response->setStatusCode(204);
        }

        return $response;
    }

    public function findAllByOperator(Application $app, Request $request) {
        $valid_operators = [
            'lessThan',
            'greaterThan',
            'lessThanOrEqualTo',
            'greaterThanOrEqualTo',
            'between',
        ];

        $field   = $request->get('field');
        $value   = $request->get('value_1');
        $value2  = $request->get('value_2');
        $model = $app[$this->model_factory_key];
        $entity = $app[$this->entity_factory_key];
        $operator = $request->get('operator');

        if (!in_array($operator, $valid_operators)) {
            $response = new JsonResponse(["error" => "Invalid operator"]);
            $response->setStatusCode(400);
            return $response;
        }

        if (!$entity->isFieldValid($field)){
            $response = new JsonResponse(['error' => "$field is not a valid field"]);
            $response->setStatusCode(400);
            return $response;
        }

        $entities = $model->findAllByOperator($field, $value, $operator, $value2);
        $response = new JsonResponse($entities);

        if (empty($entities)) {
            $response->setStatusCode(204);
        }

        return $response;
    }

}

