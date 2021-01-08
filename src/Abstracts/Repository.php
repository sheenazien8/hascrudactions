<?php

namespace Sheenazien8\Hascrudactions\Abstracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Sheenazien8\Hascrudactions\Interfaces\Repository as RepositoryInterface;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Repository implements RepositoryInterface
{
    /** @var string model */
    private Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function datatable(Request $request): LaTable
    {
        $items = $this->query()->latest()->get();

        return $this->getObjectModel()->table($items);
    }

    public function find(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    public function findByKeyArray(array $key, string $column = "id"): Collection
    {
        return $this->query()->whereIn($column, $key)->get();
    }

    public function delete(int $id): bool
    {
        return $this->query()->find($id)->delete();
    }

    public function findByUuid(string $id): Model
    {
        return $this->query()->find($id);
    }

    public function paginate(Request $request, array $columns = ['*'], string $search): LengthAwarePaginator
    {
        $self = $this;
        return $this->query()->select($columns)
            ->when(isset($this->parent) && !is_null($this->parent), function ($query) use ($self) {
                return $query->where($self->column, $self->parent->id);
            })
            ->when(!is_null($request->s), function ($query) use ($request, $search) {
                return $query->where($search, 'LIKE', $request->s . '%%');
            })
            ->orderBy('id', 'desc')
            ->paginate($request->per_page);
    }

    public function all(Request $request, array $columns = ['*'], string $search): Collection
    {
        $self = $this;
        return $this->query()->select($columns)
            ->when(isset($this->parent) && !is_null($this->parent), function ($query) use ($self) {
                return $query->where($self->column, $self->parent->id);
            })
            ->when(!is_null($request->s), function ($query) use ($request, $search) {
                return $query->where($search, 'LIKE', $request->s . '%%');
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function get($request, $columns, $search): Collection
    {
        return $this->query()->select($columns)->when(!is_null($request->s), function ($query) use ($request, $search) {
            return $query->where($search, 'LIKE', $request->s . '%%');
        })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create(Request $request): Model
    {
        $model = new $this->model;
        $model->fill($request->all());
        $model->save();

        return $model;
    }

    public function update(Request $request, $model): Model
    {
        $model->fill($request->all());
        $model->save();

        return $model;
    }

    public function bulkDestroy(Request $request, string $column = 'id'): void
    {
        $self = $this;
        DB::transaction(static function () use ($request, $self, $column) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($self, $column) {
                    $self->model::whereIn($column, $bulkChunk)->delete();
                });
        });
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getObjectModel(array $data = null): Model
    {
        if ($data) {
            return new $this->model($data);
        } else {
            return new $this->model();
        }
    }

    public function query(): Builder
    {
        return $this->getObjectModel()->query();
    }

    public function if($true = false, Closure $closure): self
    {
        if ($true) {
            $self = $this;
            return $closure($self);
        }

        return $this;
    }
}
