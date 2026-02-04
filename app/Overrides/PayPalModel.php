<?php
/**
 * PHP 8 compatibility fix for PayPal SDK
 * This patches the sizeof() issue in the deprecated paypal/rest-api-sdk-php
 */

namespace PayPal\Common;

use PayPal\Validation\JsonValidator;

class PayPalModel
{
    private $_propMap = array();

    public function __construct($data = null)
    {
        switch (gettype($data)) {
            case "NULL":
                break;
            case "string":
                JsonValidator::validate($data);
                $this->fromJson($data);
                break;
            case "array":
                $this->fromArray($data);
                break;
            default:
        }
    }

    public function __get($key)
    {
        return $this->_propMap[$key];
    }

    public function __isset($key)
    {
        return isset($this->_propMap[$key]);
    }

    public function __set($key, $value)
    {
        if (!is_array($value) && $value === null) {
            $this->__unset($key);
        } else {
            $this->_propMap[$key] = $value;
        }
    }

    public function __unset($key)
    {
        unset($this->_propMap[$key]);
    }

    private function _convertToArray($param)
    {
        $ret = array();
        foreach ($param as $k => $v) {
            if ($v instanceof PayPalModel) {
                $ret[$k] = $v->toArray();
            } elseif (is_array($v) && count($v) > 0) {
                // PHP 8 fix: check if $v is actually an array before using sizeof/count
                $ret[$k] = $this->_convertToArray($v);
            } else {
                $ret[$k] = $v;
            }
        }
        if (count($ret) <= 0) {
            $ret = new \stdClass();
        }
        return $ret;
    }

    public function fromArray($arr)
    {
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    $clazz = \PayPal\Common\ReflectionUtil::getPropertyClass(get_class($this), $k);
                    if (\PayPal\Validation\ArrayValidator::isAssocArray($v)) {
                        if (isset($clazz)) {
                            $o = new $clazz();
                            $o->fromArray($v);
                            $this->assignValue($k, $o);
                        }
                    } else {
                        $arr = array();
                        foreach ($v as $nk => $nv) {
                            if (is_array($nv)) {
                                $o = new $clazz();
                                $o->fromArray($nv);
                                $arr[$nk] = $o;
                            } else {
                                $arr[$nk] = $nv;
                            }
                        }
                        $this->assignValue($k, $arr);
                    }
                } else {
                    $this->$k = $v;
                }
            }
        }
        return $this;
    }

    private function assignValue($key, $value)
    {
        $setter = 'set'. $this->convertToCamelCase($key);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $this->__set($key, $value);
        }
    }

    private function convertToCamelCase($key)
    {
        return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $key)));
    }

    public function fromJson($json)
    {
        return $this->fromArray(json_decode($json, true));
    }

    public function toArray()
    {
        return $this->_convertToArray($this->_propMap);
    }

    public function toJSON($options = 0)
    {
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($this->toArray(), $options | 64);
        }
        return str_replace('\\/', '/', json_encode($this->toArray(), $options));
    }

    public function __toString()
    {
        return $this->toJSON(128);
    }
}
