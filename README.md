# laravel-repository
Laravel Repository implementation

[![Build Status](https://travis-ci.com/Ark4ne/laravel-repository.svg?branch=master)](https://travis-ci.com/Ark4ne/laravel-repository)
[![Coverage Status](https://coveralls.io/repos/github/Ark4ne/laravel-repository/badge.svg)](https://coveralls.io/github/Ark4ne/laravel-repository)

## Usage

```php
use Illuminate\Database\Eloquent\Model;

class Pet extends Model {
    // ...
}
```

```php
use Ark4ne\Repositories\Contracts\RepositoryContract;

interface PetRepositoryContract extends RepositoryContract {
    //
}
```

```php
use Ark4ne\Repositories\Eloquent\Repository;

class PetRepository extends Repository implements PetRepositoryContract {
    protected function getModel() : Pet {
        return new Pet;
    }
}
```

```php
// RepositoryServiceProvider.php

public function register() {
    $this->app->bind(PetRepositoryContract::class, PetRepository::class);
}
```

```php
// PetController.php

class PetController extends Controller {
    private $repository;
    
    public function __construct(PetRepositoryContract $repository) {
        $this->repository = $repository;
    }
    
    public function store(PetStoreRequest $request) {
        $data = $request->validated();
        
        $pet = $this->repository->store($data);
        
        return new PetResource($pet);
    }
}

```

## Methods
#### count

Count models matching $criteria.

```php
count(array<string, null|int|string|Closure> $criteria): int
```

#### all

Return all models matching $criteria.

```php
all(): \Illuminate\Support\Collection
```

#### paginate

Return a paginate list of model matching $criteria.

```php
paginate(array<string, null|int|string|Closure> $criteria, int|null $per_page): \Illuminate\Contracts\Pagination\LengthAwarePaginator
```

#### find

Return a model by his id.

```php
find(int|string $id): \Illuminate\Database\Eloquent\Model
```

#### findBy

Return a model where $field match the given value.

```php
findBy(string $field, mixed $value): \Illuminate\Database\Eloquent\Model
```

#### findByMany

Return a model matching $criteria.

```php
findByMany(array<string, null|int|string|Closure> $criteria): \Illuminate\Database\Eloquent\Model
```

#### getWhere

Return a collection of model where $field match the given value.

```php
getWhere(string $field, mixed $value): \Illuminate\Support\Collection
```

#### getWhereMany

Return a collection of model matching $criteria.

```php
getWhereMany(array<string, null|int|string|Closure> $criteria): \Illuminate\Support\Collection
```

#### store

Create and return a new model.

```php
store(array<string, mixed> $data): \Illuminate\Database\Eloquent\Model
```

#### update

Update an existing model

```php
update(int|string $id, array<string, mixed> $data): \Illuminate\Database\Eloquent\Model
```

#### delete

Delete an existing model

```php
delete(int|string $id): bool
```

#### withCriteria

Add criteria

```php
withCriteria(array<string, null|int|string|Closure> $criteria): self
```


#### withRelationships

Add relationships that should be eager loaded.

```php
withRelationships(array<string> $relationships): self
```
  
