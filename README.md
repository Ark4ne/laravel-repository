# laravel-repository
Laravel Repository implementation

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
    protected static string $model = Pet::class;
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
```php
count(array<string, null|int|string|Closure> $criteria): int
```

Count models matching $criteria.


#### all
```php
all(): \Illuminate\Support\Collection
```

Return all models matching $criteria.


#### paginate
```php
paginate(array<string, null|int|string|Closure> $criteria, int|null $per_page): \Illuminate\Contracts\Pagination\LengthAwarePaginator
```

Return a paginate list of model matching $criteria.


#### find
```php
find(int|string $id): \Illuminate\Database\Eloquent\Model
```

Return a model by his id.


#### findBy
```php
findBy(string $field, mixed $value): \Illuminate\Database\Eloquent\Model
```

Return a model where $field match the given value.


#### findByMany
```php
findByMany(array<string, null|int|string|Closure> $criteria): \Illuminate\Database\Eloquent\Model
```

Return a model matching $criteria.


#### getWhere
```php
getWhere(string $field, mixed $value): \Illuminate\Support\Collection
```

Return a collection of model where $field match the given value.


#### getWhereMany
```php
getWhereMany(array<string, null|int|string|Closure> $criteria): \Illuminate\Support\Collection
```

Return a collection of model matching $criteria.


#### store
```php
store(array<string, mixed> $data): \Illuminate\Database\Eloquent\Model
```

Create and return a new model.


#### update
```php
update(int|string $id, array<string, mixed> $data): \Illuminate\Database\Eloquent\Model
```

Update an existing model


#### delete
```php
delete(int|string $id): bool
```

Delete an existing model


#### withCriteria
```php
withCriteria(array<string, null|int|string|Closure> $criteria): self
```

Add criteria


#### withRelationships
```php
withRelationships(array<string> $relationships): self
```

Add relationships that should be eager loaded.
  
  
