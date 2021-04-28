<?php


namespace Ark4ne\Repositories\Contracts;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface RepositoryInterface
 *
 * @package Ark4ne\Repositories\Contracts
 *
 * @psalm-immutable
 * @template T as \Illuminate\Database\Eloquent\Model
 */
interface RepositoryContract
{
    /**
     * Count models matching $criteria.
     *
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return int
     */
    public function count(array $criteria = []): int;

    /**
     * Return all models matching $criteria.
     *
     * @return \Illuminate\Support\Collection|Model[]|T[]
     */
    public function all(): Collection;

    /**
     * Return a paginate list of model matching $criteria.
     *
     * @param array<string, null|int|string|Closure> $criteria
     * @param int|null                               $per_page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(array $criteria = [], ?int $per_page = null): LengthAwarePaginator;

    /**
     * Return a model by his id.
     *
     * @param int|string $id
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function find($id): Model;

    /**
     * Return a model where $field match the given value.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function findBy(string $field, $value): Model;

    /**
     * Return a model matching $criteria.
     *
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function findByMany(array $criteria): Model;

    /**
     * Return a collection of model where $field match the given value.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return \Illuminate\Support\Collection|Model[]|T[]
     */
    public function getWhere(string $field, $value): Collection;

    /**
     * Return a collection of model matching $criteria.
     *
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return \Illuminate\Support\Collection|Model[]|T[]
     */
    public function getWhereMany(array $criteria): Collection;

    /**
     * Create and return a new model.
     *
     * @param array<string, mixed> $data
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function store(array $data): Model;

    /**
     * Update an existing model
     *
     * @param int|string           $id
     * @param array<string, mixed> $data
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function update($id, array $data): Model;

    /**
     * Delete an existing model
     *
     * @param int|string $id
     *
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Add criteria
     *
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return $this
     */
    public function withCriteria(array $criteria): self;

    /**
     * Add relationships that should be eager loaded.
     *
     * @param array<string> $relationships
     *
     * @return $this
     */
    public function withRelationships(array $relationships): self;
}
