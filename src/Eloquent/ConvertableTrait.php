<?php namespace Packedge\Mongorm\Eloquent;


use DateTime;
use InvalidArgumentException;
use MongoCode;
use MongoDate;
use MongoId;
use MongoInt32;
use MongoInt64;
use MongoRegex;

trait ConvertableTrait
{
    /**
     * List of available mongodb data types.
     * Not supported: MongoBinData, MongoDBRef, MongoMinKey, MongoMaxKey, MongoTimeStamp
     *
     * @var array
     */
    private $mongoTypes = [
        'MongoId', 'MongoCode', 'MongoDate',
        'MongoRegex', 'MongoInt32', 'MongoInt64'
    ];

    /**
     * Check if a given value is a mongo data type.
     *
     * @param $value
     * @return bool
     */
    public function isMongoType($value)
    {
        if(is_object($value))
        {
            return in_array(get_class($value), $this->mongoTypes);
        }
        return false;
    }

    /**
     * Convert a mongo data type, into a standard PHP type.
     *
     * @param $value
     * @return mixed
     */
    public function convertMongoType($value)
    {
        $type = get_class($value);
        $methodName = 'convert' . $type;
        if(method_exists($this, $methodName))
        {
            return $this->{$methodName}($value);
        }
    }

    /**
     * Convert a MongoId into a string.
     *
     * @param MongoId $mongoId
     * @return string
     */
    public function convertMongoId(MongoId $mongoId)
    {
        return (string) $mongoId;
    }

    /**
     * Convert a string into a MongoId.
     *
     * @param $id
     * @return MongoId
     */
    public function convertToMongoId($id)
    {
        if(!is_string($id) || strlen($id) != 24) throw new InvalidArgumentException;
        return new MongoId($id);
    }

    /**
     * Convert a MongoCode into a string.
     *
     * @param MongoCode $mongoCode
     * @return string
     */
    public function convertMongoCode(MongoCode $mongoCode)
    {
        return (string) $mongoCode;
    }

    /**
     * Convert a MongoDate into a DateTime.
     *
     * @param MongoDate $mongoDate
     * @return DateTime
     */
    public function convertMongoDate(MongoDate $mongoDate)
    {
        return new DateTime($mongoDate->sec);
    }

    /**
     * Convert a DateTime into a MongoDate.
     *
     * @param DateTime $dateTime
     * @return MongoDate
     */
    public function convertToMongoDate(DateTime $dateTime)
    {
        return new MongoDate($dateTime->getTimestamp());
    }

    /**
     * Convert a MongoRegex into a string.
     *
     * @param MongoRegex $mongoRegex
     * @return string
     */
    public function convertMongoRegex(MongoRegex $mongoRegex)
    {
        return (string) $mongoRegex;
    }

    /**
     * Convert a MongoInt32 into a int.
     *
     * @param MongoInt32 $mongoInt32
     * @return int
     */
    public function convertMongoInt32(MongoInt32 $mongoInt32)
    {
        $str =  (string) $mongoInt32;
        return (int) $str;
    }

    /**
     * Convert int into a MongoInt32.
     *
     * @param int $value
     * @return MongoInt32
     */
    public function convertToMongoInt32($value)
    {
        if(!is_int($value)) throw new InvalidArgumentException;
        return new MongoInt32($value);
    }

    /**
     * Convert a MongoInt64 into a int.
     *
     * @param MongoInt64 $mongoInt64
     * @return int
     */
    public function convertMongoInt64(MongoInt64 $mongoInt64)
    {
        $str =  (string) $mongoInt64;
        return (int) $str;
    }

    /**
     * Convert int into a MongoInt64.
     *
     * @param int $value
     * @return MongoInt64
     */
    public function convertToMongoInt64($value)
    {
        if(!is_int($value)) throw new InvalidArgumentException;
        return new MongoInt64($value);
    }
} 