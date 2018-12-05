<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 06.11.2018
 * Time: 16:56
 */

namespace Service;

class Utils extends Basic
{
    /**
     * @return string
     */
    public function createRandomChar() {
        return chr(rand(33, 126));
    }

    /**
     * @param int $length
     * @return string
     * @throws \InvalidArgumentException
     */
    public function createRandomString($length) {
        if (!is_int($length))
            throw new \InvalidArgumentException('Bad string length');

        $randomString = '';
        for ($i = 0; $i < $length; $i++)
            $randomString .= $this->createRandomChar();
        return $randomString;
    }

    /**
     * @param string $data
     * @return string
     */
    public function createHash128($data) {
        return hash('sha512', $data, false);
    }

    /**
     * @return string
     */
    public function createRandomHash128() {
        return $this->createHash128($this->createRandomString(100));
    }

    /**
     * @param int $length
     * @return string
     * @throws \InvalidArgumentException
     */
    public function createSalt($length = 10)
    {
        if (!is_int($length))
            throw new \InvalidArgumentException('Bad salt length');

        $length = ($length > 10 || $length < 0) ? 10 : $length;
        return $this->createRandomString($length);
    }

    /**
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function createPassword($password = '', $salt = '') {
        return empty($salt) ? md5($password) : md5(md5($password) . md5($salt));
    }

    /**
     * @param string $password
     * @param string $passwordHash
     * @param string $salt
     * @return bool
     * @throws \Exception
     */
    public function checkPassword($password = '', $passwordHash = '', $salt = '') {

        if ($this->createPassword($password, $salt) !== $passwordHash) {
            throw new \Exception('Bad password');
        }
    }

    /**
     * @param array $arr
     * @return bool
     */
    public function isAssoc(array $arr) {
        if (!is_array($arr))
            throw new \InvalidArgumentException('Argument must be an array');
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * @param string $filePath
     * @param array $arrayPossibleTypes
     * @return bool
     * @throws \Exception
     */
    public function checkMime($filePath, array $arrayPossibleTypes)
    {
        /** @var \Fileinfo|bool */
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type

        // Извлекаем тип файла из $filePath и проверяем на соответствие
        // элементам массива $arrayPossibleTypes
        if (in_array(finfo_file($finfo, $filePath), $arrayPossibleTypes)) {

            // Закрываем объект FileInfo
            finfo_close($finfo);

            return true;
        }

        // Закрываем объект FileInfo
        finfo_close($finfo);

        throw new \Exception('Bad file type');
    }

    /**
     * @param string $filePath
     * @return string
     */
    public function getExtention($filePath) {
        $fileName = substr($filePath, strrpos($filePath, DIRECTORY_SEPARATOR));
        $fileExt = substr($fileName, strrpos($fileName, '.'));
        return $fileExt;
    }

    /**
     * @param string $intent
     * @return string
     * @throws \Exception
     */
    public function spacedStringToMethodName($intent)
    {
        if (empty($intent)) {
            throw new \Exception('Empty string');
        }

        $result = '';
        $words = explode(' ', $intent);
        foreach($words as $word) {
            $result .= ucfirst(strtolower($word));
        }
        return lcfirst($result);
    }

    /**
     * @param string $camelCase
     * @param bool $isUpper
     * @return string
     * @throws \Exception
     */
    public function camelCaseToUnderline($camelCase, $isUpper = true)
    {
        if (empty($camelCase)) {
            throw new \Exception('Empty string');
        }

        $spaced = preg_replace('/([A-Z])/', ' $1', $camelCase);
        $result = '';
        $words = explode(' ', $spaced);
        $isFirst = true;
        foreach($words as $word) {
            if ($isUpper) {
                $result .= $isFirst ? strtoupper($word) : '_' . strtoupper($word);
            } else {
                $result .= $isFirst ? strtolower($word) : '_' . strtolower($word);
            }

            $isFirst = false;
        }
        return $result;
    }

    /**
     * @param string $underline
     * @param bool $isFirstLow
     * @return string
     * @throws \Exception
     */
    public function underlineToCamelCase($underline, $isFirstLow = true)
    {
        if (empty($underline)) {
            throw new \Exception('Empty string');
        }
        $words = explode('_', $underline);
        $result = '';

        foreach($words as $word) {
            $result .= ucfirst(strtolower($word));
        }
        return $isFirstLow ? lcfirst($result) : $result;
    }

    /**
     * @param array|Object[] $items
     * @param array $data
     * @return bool
     */
    public function isArrayValuesInAnotherArray(array $items, array $data) {
        foreach($items as $item) {
            if (!in_array($item, $data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array|Object[] $items
     * @param string $keyField
     * @return array
     */
    public function buildIndex($items, $keyField = 'id') {
        $result = [];
        foreach ($items as $item) {
            $key = $this->_getObjectFieldValue($item, $keyField);
            if ($key !== null) {
                $result[$key] = $item;
            }
        }
        return $result;
    }

    /**
     * @param string|array $fieldName
     * @param array $objects
     * @param bool $preserveKey
     * @param mixed $defaultValue
     * @return array
     */
    public function extractField($fieldName, array $objects, $preserveKey = false, $defaultValue = null) {
        $result = [];
        foreach ($objects as $key => $object) {
            $value = $this->arrayGetRecursive($object, (is_array($fieldName) ? $fieldName : [$fieldName]), $defaultValue);
            if ($preserveKey) {
                $result[$key] = $value;
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * @param array $fieldNames
     * @param array $objects
     * @param bool $preserveKey
     * @return array
     * @throws \Exception
     */
    public function extractFields(array $fieldNames, array $objects, $preserveKey = false) {
        if (count($fieldNames) === 0) {
            throw new \Exception("Empty fieldNames passed");
        }

        $result = [];
        foreach ($objects as $key => $object) {
            $element = [];
            foreach ($fieldNames as $fieldName) {
                $element[$fieldName] = $this->_getObjectFieldValue($object, $fieldName);
            }

            if ($preserveKey) {
                $result[$key] = $element;
            } else {
                $result[] = $element;
            }
        }

        return $result;
    }

    /**
     * @param Object[] $object
     * @param array|Object[] $items
     * @return mixed new, typed object
     * @throws \InvalidArgumentException
     */
    public function fillObjectBy($object, $items) {
        if (!is_array($items)) {
            throw new \InvalidArgumentException('Argument is not an array');
        }

        foreach($object as $property=>$value) {
            $value = $this->_getObjectFieldValue($items, $property);
            if ($value !== null) {
                $object->$property = $value;
            }
        }
        return $object;
    }

    /**
     * @param mixed $data
     * @param array $keys
     * @param mixed $default
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function arrayGetRecursive($data, array $keys, $default = null) {
        if (!$this->arrayKeyExistRecursive($data, $keys)) {
            return $default;
        }

        /** @var mixed $tempData */
        $tempData = $data;
        foreach ($keys as $key) {
            $getMethodName = "get" . ucfirst($key);
            if (is_object($tempData) && array_key_exists($key, get_object_vars($tempData))) {
                $tempData = $tempData->$key;
            } elseif (is_object($tempData) && method_exists($tempData, $key)) {
                $tempData = $tempData->$key();
            } elseif (is_object($tempData) && method_exists($tempData, $getMethodName)) {
                $tempData = $tempData->$getMethodName();
            } elseif (is_array($tempData)) {
                $tempData = $tempData[$key];
            } else {
                return $default;
            }
        }
        return $tempData;
    }

    /**
     * @param mixed $data
     * @param array $keys
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function arrayKeyExistRecursive($data, array $keys) {
        if (count($keys) === 0) {
            throw new \InvalidArgumentException('Keys must be a non-empty array');
        }
        /** @var mixed $tempData */
        $tempData = $data;
        foreach ($keys as $key) {
            $getMethodName = "get" . ucfirst($key);
            if (is_array($tempData) && array_key_exists($key, $tempData)) {
                $tempData = $tempData[$key];
            } elseif (is_object($tempData) && array_key_exists($key, get_object_vars($tempData))) {
                $tempData = $tempData->$key;
            } elseif (is_object($tempData) && method_exists($tempData, $getMethodName)) {
                $tempData = $tempData->$getMethodName();
            } elseif (is_object($tempData) && method_exists($tempData, $key)) {
                $tempData = $tempData->$key();
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed $object
     * @param string $fieldName
     * @return mixed
     */
    private function _getObjectFieldValue($object, $fieldName) {
        $isArrayKeyExistAndScalar        = is_array($object) && array_key_exists($fieldName, $object) && is_scalar($object[$fieldName]);
        $isObjectKeyExistAndScalar       = is_object($object) && array_key_exists($fieldName, get_object_vars($object)) && is_scalar($object->$fieldName);
        $isObjectMethodExistAndScalar    = is_object($object) && method_exists($object, $fieldName) && is_scalar($object->$fieldName());
        $getMethodName                   = 'get' . ucfirst($fieldName);
        $isObjectGetMethodExistAndScalar = is_object($object) && method_exists($object, $getMethodName) && is_scalar($object->$getMethodName());

        $value = null;
        if ($isObjectKeyExistAndScalar) {
            $value = $object->$fieldName;
        }

        if ($isArrayKeyExistAndScalar) {
            $value = $object[$fieldName];
        }

        if ($isObjectMethodExistAndScalar) {
            $value = $object->$fieldName();
        }

        if ($isObjectGetMethodExistAndScalar) {
            $value = $object->$getMethodName();
        }

        return $value;
    }
}