<?php


namespace Tests\Dummies;


use Ark4ne\Repositories\Eloquent\Repository;

/**
 * @extends Repository<DummyModel>
 */
class DummyRepository extends Repository implements DummyRepositoryContract
{
    protected static string $model = DummyModel::class;
}
