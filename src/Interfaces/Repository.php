<?php

namespace Sheenazien8\Hascrudactions\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Sheenazien8\Hascrudactions\Abstracts\LaTable;

interface Repository
{
    public function datatable(Request $request): LaTable;

    public function paginate(Request $request, array $columns, string $search): LengthAwarePaginator;

    public function all(Request $request, array $columns, string $search): Collection;

    public function get(Request $request, array $columns, string $search): Collection;

    public function create(Request $request): Model;

    public function update(Request $request, $model): Model;

    public function delete(int $id): bool;

    public function find(int $id): Model;

    public function findByUuid(string $id): Model;

    public function bulkDestroy(Request $request, string $column): void;

    public function findByKeyArray(array $key, string $id = "id"): Collection;

    public function getObjectModel(): Model;

    public function query(): Builder;
}
