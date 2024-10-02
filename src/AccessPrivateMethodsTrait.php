<?php

namespace PHPUnit\Helper;

trait AccessPrivateMethodsTrait
{
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws \ReflectionException
     */
    public function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param mixed $object
     * @param string $property
     * @param mixed $value
     */
    public function setPrivateProperty(&$object, $property, $value = null)
    {
        $privatePropertySetter = function ($object, $property, $value) {
            $object->$property = $value;
        };
        $privatePropertySetter = \Closure::bind($privatePropertySetter, null, $object);
        $privatePropertySetter($object, $property, $value);
    }

    /**
     * @param mixed $object
     * @param string $property
     * @param mixed $value
     */
    public function setPrivateStaticProperty(&$object, $property, $value = null)
    {
        $privatePropertySetter = function ($object, $property, $value) {
            $object::$$property = $value;
        };
        $privatePropertySetter = \Closure::bind($privatePropertySetter, null, $object);
        $privatePropertySetter($object, $property, $value);
    }

    /**
     * @param $object
     * @param $property
     * @return mixed
     */
    public function getPrivateStaticProperty(&$object, $property)
    {
        $privatePropertyGetter = function ($object, $property) {
            return $object::$$property;
        };
        $privatePropertyGetter = \Closure::bind($privatePropertyGetter, null, $object);
        return $privatePropertyGetter($object, $property);
    }

    /**
     * @param $object
     * @param $property
     * @return mixed
     */
    public function getPrivateProperty(&$object, $property)
    {
        $privatePropertyGetter = function ($object, $property) {
            return $object->$property;
        };
        $privatePropertyGetter = \Closure::bind($privatePropertyGetter, null, $object);
        return $privatePropertyGetter($object, $property);
    }
}
