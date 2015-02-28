<?php namespace Packedge\Mongorm\Eloquent;

use ArrayAccess;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class CoreModel implements ArrayAccess
{
    use MutableTrait;
    
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';
    /**
     * Indicates if the model exists.
     *
     * @var bool
     */
    public $exists = false;
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';
    /**
     * The collection associated with the model.
     *
     * @var String
     */
    protected $collection;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['*'];

    /**
     * Return a new instance of the class.
     *
     * @return static
     */
    public static function instance()
    {
        return new static;
    }

    /**
     * Returns a new instance of the Builder.
     *
     * @return Builder
     */
    public function newBuilder()
    {
        $builder = new Builder;
        $builder->setModel($this);

        return $builder;
    }

    /**
     * Get or guess the collection name.
     *
     * @return string
     */
    public function getCollectionName()
    {
        if (isset($this->collection)) {
            return $this->collection;
        }

        $reflector = new ReflectionClass($this);
        $str = new Str;

        return $str->lower(
            $str->plural(
                $str->snake(
                    $reflector->getShortName()
                )
            )
        );
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Set the primary key for the model.
     *
     * @param  string $key
     * @return void
     */
    public function setKeyName($key)
    {
        $this->primaryKey = $key;
    }

    /**
     * Fill the models attributes with values.
     *
     * @param array $attributes
     */
    public function fill(array $attributes)
    {
        $totallyGuarded = $this->totallyGuarded();

        // TODO: check if its fillable
        // TODO: set attribute
        // TODO: else throw exception

        // TODO: return $this for chaining
    }

    /**
     * Determine if the model is totally guarded.
     *
     * @return bool
     */
    public function totallyGuarded()
    {
        return count($this->fillable) == 0 && $this->guarded == ['*'];
    }
}
