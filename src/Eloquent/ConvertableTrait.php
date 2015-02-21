<?php namespace Packedge\Mongorm\Eloquent;


use MongoCode;
use MongoId;

trait ConvertableTrait
{
    private $mongoTypes = [
        'MongoId', 'MongoCode', 'MongoDate',
        'MongoRegex', 'MongoBinData', 'MongoInt32',
        'MongoInt64', 'MongoDBRef', 'MongoMinKey',
        'MongoMaxKey', 'MongoTimeStamp'
    ];

    public function isMongoType($value)
    {
        if(is_object($value))
        {
            return in_array(get_class($value), $this->mongoTypes);
        }
        return false;
    }

    public function convertMongoType($value)
    {
        $type = get_class($value);
        $methodName = 'convert' . $type;
        if(method_exists($this, $methodName))
        {
            return $this->{$methodName}($value);
        }
    }

    public function convertMongoId(MongoId $mongoId)
    {
        return (string) $mongoId;
    }

    public function convertMongoCode(MongoCode $mongoCode)
    {
        return (string) $mongoCode;
    }

    public function convertMongoDate(MongoDate $mongoDate)
    {
        // TODO: implement
    }

    public function convertMongoRegex(MongoRegex $mongoRegex)
    {
        // TODO: implement
    }

    public function convertMongoBinData(MongoBinData $mongoBinData)
    {
        // TODO: implement
    }

    public function convertMongoInt32(MongoInt32 $mongoInt32)
    {
        // TODO: implement
    }

    public function convertMongoInt64(MongoInt64 $mongoInt64)
    {
        // TODO: implement
    }

    public function convertMongoDBRef(MongoDBRef $mongoDBRef)
    {
        // TODO: implement
    }

    public function convertMongoMinKey(MongoMinKey $mongoMinKey)
    {
        // TODO: implement
    }

    public function convertMongoMaxKey(MongoMaxKey $mongoMaxKey)
    {
        // TODO: implement
    }

    public function convertMongoTimestamp(MongoTimestamp $mongoTimestamp)
    {
        // TODO: implement
    }
} 