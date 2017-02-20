<?php

namespace MKaczorowski\BasicService\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MKaczorowski\BasicService\Models\BaseModel;
use MKaczorowski\BasicService\Exceptions as Exceptions;

abstract class BaseController {

    protected
        $model_factory_key;

    protected function _list(Application $app, Request $request) {
        $model  = $app[$this->model_factory_key];

        $models = $model->findAll();
        $response = new JsonResponse($models);

        if (empty($models)) {
            $response->setStatusCode(204);
        }

        return $response;
    }

    public function save(Application $app, Request $request) {
        $model  = $app[$this->model_factory_key];
        $model->load(json_decode($request->getContent()));
        $result = $model->save();

        $response = new JsonResponse($model);
        $response->setStatusCode(201);

        if ($result !== true) {
            $response->setStatusCode(400);
        }
        return $response;
    }

    public function findById(Application $app, Request $request) {
        $id     = $request->get('id');
        $model = $app[$this->model_factory_key]->findById($id);
        $response = new JsonResponse($model);

        if (empty($model)) {
            $response->setStatusCode(204);
        }

        return $response;
    }

    public function findBy(Application $app, Request $request) {
        $model  = $app[$this->model_factory_key];
        $field  = $request->get('field');
        $value  = $request->get('value');

        $result = $model->findBy($field, $value);
        $response = new JsonResponse($result);

        if (!$result instanceof BaseModel && $result !== false) {
            $response->setStatusCode(204);
        } elseif ($entity === false) {
            $response->setStatusCode(400);
        }

        return $response;
    }

    public function findAllByLike(Application $app, Request $request) {
      $field = $request->get('field');
      $value = $request->get('value');

      $model = $app[$this->model_factory_key];

      if (!$model->isFieldValid($field)){
          $response = new JsonResponse(['error' => "$field is not a valid field"]);
          $response->setStatusCode(400);
          return $response;
      }

      $entities = $model->findAllByLike($field, $value);
      $response = new JsonResponse($entities);

      if (empty($entities)) {
          $response->setStatusCode(204);
      }

      return $response;
    }

    public function findAllByLikeWithParent(Application $app, Request $request) {
      $field        = $request->get('field');
      $value        = $request->get('value');
      $parent       = $request->get('parent');
      $parent_name  = $request->get('parent_name');

      $model = $app[$this->model_factory_key];

      if (!$model->isFieldValid($field)){
          $response = new JsonResponse(['error' => "$field is not a valid field"]);
          $response->setStatusCode(400);
          return $response;
      }

      $entities = $model->findAllByLikeWithParent(
        $field,
        $value,
        $parent,
        $parent_name
      );
      $response = new JsonResponse($entities);
      if (empty($entities)) {
          $response->setStatusCode(204);
      }

      return $response;
    }

    public function findAllBy(Application $app, Request $request) {
        $field = $request->get('field');
        $value = $request->get('value');

        $model = $app[$this->model_factory_key];

        if (!$model->isFieldValid($field)){
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
        $operator = $request->get('operator');

        if (!in_array($operator, $valid_operators)) {
            $response = new JsonResponse(["error" => "Invalid operator"]);
            $response->setStatusCode(400);
            return $response;
        }

        if (!$model->isFieldValid($field)){
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
