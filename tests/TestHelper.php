<?php


namespace Tests;


use ReflectionClass;

trait TestHelper
{
    /**
     * @param string $model
     * @param int $num
     * @param array $attributes
     * @param bool $resource
     * @param string $resourceClassName
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function create(string $model, int $num = 1, array $attributes = [], bool $resource = false, $resourceClassName = '')
    {
        $resourceModel = ($num == 1)
            ? factory('App\\Models\\' . $model)->create($attributes)
            : factory('App\\Models\\' . $model, $num)->create($attributes);

        $resourceClass = 'Http\\Resources\\' . $resourceClassName;

        if (!$resource)
            return $resourceModel;

        return new $resourceClass($resourceModel);
    }

    /**
     * @param $className
     * @param $methodName
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    public function getPrivateMethod($className, $methodName)
    {
        $reflector = new ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @param $className
     * @param $propertyName
     * @return \ReflectionProperty
     * @throws \ReflectionException
     */
    public function getPrivateProperty($className, $propertyName)
    {
        $reflector = new ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        return $property;
    }

}