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

    /**
     * Assign DataTable
     *
     * @param Request $request
     *
     * @return LaTable
     */
    public function datatable(Request $request): LaTable
    {
        $items = $this->query()->latest();

        return $this->getObjectModel()->table($items);
    }

    /**
     * Find by primary key
     *
     * @param int $id
     *
     * @return Model
     */
    public function find(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * findByKeyArray
     *
     * @param array $key
     * @param string $column (optional)
     *
     * @return Collection
     */
    public function findByKeyArray(array $key, string $column = "id"): Collection
    {
        return $this->query()->whereIn($column, $key)->get();
    }

    /**
     * delete method
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->query()->find($id)->delete();
    }

    /**
     * findByUuid
     *
     * @param string $id
     *
     * @return Model
     */
    public function findByUuid(string $id): Model
    {
        return $this->query()->find($id);
    }

    /**
     * Pagination
     *
     * @param Request $request
     * @param array $columns (optional)
     * @param string $search (optional)
     *
     * @return LengthAwarePaginator
     */
    public function paginate(Request $request, array $columns = ['*'], string $search = null): LengthAwarePaginator
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

    /**
     * all
     *
     * @param Request $request
     * @param array $columns (optional)
     * @param string $search (optional)
     *
     * @return Collection
     */
    public function all(Request $request, array $columns = ['*'], string $search = null): Collection
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

    /**
     * get all record Model
     *
     * @param Request $request
     * @param array $columns (optional)
     * @param string $search (optional)
     *
     * @return Collection
     */
    public function get(Request $request, array $columns = ['*'], string $search = null): Collection
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

    /**
     * update record Model
     *
     * @param Request $request
     * @param Model $model
     *
     * @return Model
     */
    public function update(Request $request, Model $model): Model
    {
        $model->fill($request->all());
        $model->save();

        return $model;
    }

    /**
     * Bulk Destroy
     *
     * @param Request $request
     * @param string $column (optional)
     *
     * @return void
     */
    public function bulkDestroy(Request $request, string $column = 'id'): void
    {
        $self = $this;
        if ($self->query()->find($request->ids)->count() == 0) {
            abort(404);
        }
        DB::transaction(static function () use ($request, $self, $column) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($self, $column) {
                    $self->query()->whereIn($column, $bulkChunk)->delete();
                });
        });
    }

    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * getObjectModel
     *
     * @param array $data (optional)
     *
     * @return Model
     */
    public function getObjectModel(array $data = null): Model
    {
        if ($data) {
            return new $this->model($data);
        } else {
            return new $this->model();
        }
    }

    /**
     * newQuery
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->getObjectModel()->query();
    }

    /**
     * You can use this if you want use if in your custome method
     *
     * @param bool $true (optional)
     * @param Closure $closure
     *
     * @return self
     */
    public function if(bool $true = false, Closure $closure): self
    {
        if ($true) {
            $self = $this;
            return $closure($self);
        }

        return $this;
    }
}
