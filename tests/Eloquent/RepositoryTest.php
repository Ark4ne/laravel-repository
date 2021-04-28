<?php

namespace Tests\Eloquent;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Dummies\DummyModel;
use Tests\Dummies\DummyRelationModel;
use Tests\Dummies\DummyRepository;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    protected static function newModel(string $model, array $data)
    {
        $model = tap($model::make($data), function ($model) {
            $model->save();
        });

        $model->refresh();

        return $model;
    }

    public function testCount()
    {
        self::newModel(DummyModel::class, ['name' => 'dummy']);

        $repository = new DummyRepository();

        self::assertEquals(1, $repository->count());
    }

    public function testAll()
    {
        $model = self::newModel(DummyModel::class, ['name' => 'dummy']);

        $repository = new DummyRepository();

        $all = $repository->all();

        self::assertEquals(
            (new Collection([$model]))->toArray(),
            $all->toArray()
        );
    }

    public function testPaginate()
    {
        $model_1 = self::newModel(DummyModel::class, ['name' => "dummy1"]);
        $model_2 = self::newModel(DummyModel::class, ['name' => "dummy2"]);
        $model_3 = self::newModel(DummyModel::class, ['name' => "dummy3"]);
        $model_4 = self::newModel(DummyModel::class, ['name' => "dummy4"]);

        $repository = new DummyRepository();

        $paginator = $repository->paginate();

        self::assertEquals(4, $paginator->total());

        $paginator = $repository->paginate([], 2);

        self::assertCount(2, $paginator->items());
        self::assertEquals(4, $paginator->total());
    }

    public function testPaginateWithCriteria()
    {
        $model_1 = self::newModel(DummyModel::class, ['name' => "dummy1"]);
        $model_2 = self::newModel(DummyModel::class, ['name' => "dummy2"]);
        $model_3 = self::newModel(DummyModel::class, ['name' => "dummy3"]);
        $model_4 = self::newModel(DummyModel::class, ['name' => "dummy4"]);

        $repository = new DummyRepository();

        $paginator = $repository->paginate(['id' => [1, 3]]);

        self::assertEquals(2, $paginator->total());
        self::assertEquals(
            (new Collection([$model_1, $model_3]))->toArray(),
            (new Collection($paginator->items()))->toArray()
        );
    }

    public function testFind()
    {
        $expected = self::newModel(DummyModel::class, ['name' => "dummy1"]);

        $repository = new DummyRepository();

        $actual = $repository->find(1);

        self::assertEquals($expected->toArray(), $actual->toArray());
    }

    public function testFindBy()
    {
        $expected = self::newModel(DummyModel::class, ['name' => "dummy1"]);

        $repository = new DummyRepository();

        $actual = $repository->findBy('id', 1);

        self::assertEquals($expected->toArray(), $actual->toArray());
    }

    public function testFindByMany()
    {
        $expected = self::newModel(DummyModel::class, ['name' => "dummy1"]);

        $repository = new DummyRepository();

        $actual = $repository->findByMany(['id' => 1]);

        self::assertEquals($expected->toArray(), $actual->toArray());
    }

    public function testGetWhere()
    {
        $expected = self::newModel(DummyModel::class, ['name' => "dummy1"]);

        $repository = new DummyRepository();

        $actual = $repository->getWhere('id', 1);

        self::assertEquals((new Collection([$expected]))->toArray(), $actual->toArray());
    }

    public function testGetWhereMany()
    {
        $expected = self::newModel(DummyModel::class, ['name' => "dummy1"]);

        $repository = new DummyRepository();

        $actual = $repository->getWhereMany(['id' => 1]);

        self::assertEquals((new Collection([$expected]))->toArray(), $actual->toArray());
    }

    public function testStore()
    {
        $repository = new DummyRepository();

        $actual = $repository->store(['id' => 12, 'name' => "dummy"]);
        $actual->refresh();

        self::assertEquals(DummyModel::find(12)->toArray(), $actual->toArray());
    }

    public function testUpdate()
    {
        $expected = self::newModel(DummyModel::class, ['name' => "dummy1"]);

        $repository = new DummyRepository();

        $repository->update(1, ['name' => 'test']);

        $expected->refresh();

        self::assertEquals('test', $expected->name);
    }

    public function testDelete()
    {
        $this->expectException(ModelNotFoundException::class);

        $expected = self::newModel(DummyModel::class, ['name' => "dummy1"]);

        $repository = new DummyRepository();

        $repository->delete(1);

        $expected->refresh();
    }

    public function testWithCriteria()
    {
        $model_1 = self::newModel(DummyModel::class, ['name' => "dummy1"]);
        $model_2 = self::newModel(DummyModel::class, ['name' => "dummy2"]);
        $model_3 = self::newModel(DummyModel::class, ['name' => "dummy3"]);
        $model_4 = self::newModel(DummyModel::class, ['name' => "dummy4"]);

        $repository = new DummyRepository();

        $repositoryWithCriteria = $repository->withCriteria(['id' => [1, 2, 3]]);

        self::assertNotEquals($repository, $repositoryWithCriteria);
        self::assertCount(4, $repository->all());
        self::assertCount(3, $repositoryWithCriteria->all());
    }

    public function testWithRelationships()
    {
        $relation = self::newModel(DummyRelationModel::class, ['name' => "relation"]);

        $model = self::newModel(DummyModel::class, [
            'id' => 1,
            'name' => "dummy",
            "dummy_relation_model_id" => $relation->id
        ]);

        $repository = new DummyRepository();

        $repositoryWithRelation = $repository->withRelationships(['relation']);

        self::assertNotEquals($repository, $repositoryWithRelation);

        self::assertEquals($model->toArray(), $repository->find(1)->toArray());
        self::assertEquals(
            array_merge($model->toArray(), ['relation' => $relation->toArray()]),
            $repositoryWithRelation->find(1)->toArray()
        );
    }

    public function testCriteriaWhereNull()
    {
        $expected = self::newModel(DummyModel::class, ['name' => "dummy"]);

        $repository = new DummyRepository();

        $actual = $repository->findBy('dummy_relation_model_id', null);

        self::assertEquals($expected->toArray(), $actual->toArray());
    }
}
