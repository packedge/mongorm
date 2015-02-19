<?php

namespace Fruits {
    use Packedge\Mongorm\Model;

    class Banana extends Model {}

    class RedGrape extends Model {}
}


namespace Model{
    use Fruits\Banana;
    use Fruits\RedGrape;
    use Packedge\Mongorm\Model;

    class Apple extends Model
    {
        protected $collection = 'some_collection';
    }

    class Orange extends Model
    {
    }


    class CollectionNamingTest extends \PHPUnit_Framework_TestCase
    {
        /** @test */
        public function it_allows_you_to_set_the_collection_name()
        {
            $model = new Apple;
            $this->assertSame('some_collection', $model->getCollectionName());
        }

        /** @test */
        public function it_pluralises_class_name_for_collection_name()
        {
            $model = new Orange;
            $this->assertSame('oranges', $model->getCollectionName());
        }

        /** @test */
        public function it_supports_namespaced_classes_for_determining_collection_name()
        {
            $model = new Banana;
            $this->assertSame('bananas', $model->getCollectionName());
        }

        /** @test */
        public function it_uses_underscores_for_multi_worded_class_names_for_collection_name()
        {
            $model = new RedGrape;
            $this->assertSame('red_grapes', $model->getCollectionName());
        }
    }
}
 