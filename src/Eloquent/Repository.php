<?php


namespace Ark4ne\Repositories\Eloquent;

use Ark4ne\Repositories\Contracts\RepositoryContract;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Repository
 *
 * @package Ark4ne\Repositories\Eloquent
 *
 * @psalm-immutable
 * @template T of \Illuminate\Database\Eloquent\Model
 */
abstract class Repository implements RepositoryContract
{
    /**
     * @var class-string<T>
     */
    protected static string $model;

    /**
     * Default relationships that should be eager loaded.
     *
     * @var array<string, null|int|string|Closure>
     */
    protected array $criteria = [];

    /**
     * Default relationships that should be eager loaded.
     *
     * @var string[]
     */
    protected array $relationships = [];

    /**
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return int
     */
    public function count(array $criteria = []): int
    {
        return $this->getQueryBuilder($criteria)->count();
    }

    /**
     * @return \Illuminate\Support\Collection|Model[]|T[]
     */
    public function all(): Collection
    {
        return $this->getQueryBuilder()->get();
    }

    /**
     * @param array<string, null|int|string|Closure> $criteria
     * @param int|null                               $per_page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(array $criteria = [], ?int $per_page = null): LengthAwarePaginator
    {
        return $this->getQueryBuilder($criteria)->paginate($per_page);
    }

    /**
     * @param int|string $id
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function find($id): Model
    {
        return $this->getQueryBuilder()->findOrFail($id);
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function findBy(string $field, $value): Model
    {
        return $this->findByMany([$field => $value]);
    }

    /**
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function findByMany(array $criteria): Model
    {
        return $this->getQueryBuilder($criteria)->firstOrFail();
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return \Illuminate\Support\Collection|Model[]|T[]
     */
    public function getWhere(string $field, $value): Collection
    {
        return $this->getWhereMany([$field => $value]);
    }

    /**
     * @param array<string, mixed> $criteria
     *
     * @return \Illuminate\Support\Collection|Model[]|T[]
     */
    public function getWhereMany(array $criteria): Collection
    {
        return $this->getQueryBuilder($criteria)->get();
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function store(array $data): Model
    {
        $model = $this->getNewInstance();

        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * @param int|string           $id
     * @param array<string, mixed> $data
     *
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    public function update($id, array $data): Model
    {
        $model = $this->find($id);

        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * @param int|string $id
     *
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->find($id)->delete();
    }

    /**
     * @param array<string, mixed> $criteria
     *
     * @return $this
     */
    public function withCriteria(array $criteria): self
    {
        $new = clone $this;
        $new->criteria = array_merge($this->criteria, $criteria);
        return $new;
    }

    /**
     * @param string[] $relationships
     *
     * @return $this
     */
    public function withRelationships(array $relationships): self
    {
        $new = clone $this;
        $new->relationships = array_merge($this->relationships, $relationships);
        return $new;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|T
     */
    protected function getNewInstance(): Model
    {
        $modelClass = static::$model;

        return new $modelClass;
    }

    /**
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQueryBuilder(array $criteria = []): Builder
    {
        $query = $this->getNewInstance()->newQuery();

        $this->applyCriteria($query, array_merge($this->criteria, $criteria));

        $query->with($this->relationships);

        return $query;
    }

    /**
     * @param Builder                                $instance
     * @param array<string, null|int|string|Closure> $criteria
     *
     * @return Builder
     */
    protected function applyCriteria(Builder $instance, array $criteria): Builder
    {
        foreach ($criteria as $field => $value) {
            if ($value === null) {
                $instance->whereNull($field);
            } elseif (is_array($value)) {
                $instance->whereIn($field, $value);
            } elseif($value instanceof Closure) {
                $instance->where($value);
            } else {
                $instance->where($field, $value);
            }
        }

        return $instance;
    }
}
