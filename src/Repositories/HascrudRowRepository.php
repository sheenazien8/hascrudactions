<?php

namespace Sheenazien8\Hascrudactions\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sheenazien8\Hascrudactions\Abstracts\Repository;
use Sheenazien8\Hascrudactions\Models\HascrudRow;

/**
 * Class HascrudRowRepository
 * @author sheenazien8
 */
class HascrudRowRepository extends Repository
{
    /**
     * inject model to repositories
     */
    public function __construct()
    {
        parent::__construct(new HascrudRow());
    }

    /**
     * create hascrud row
     *
     * @param Request $request
     *
     * @return HascrudRow
     */
    public function create(Request $request): HascrudRow
    {
        $input = $this->transformRequest($request);

        $this->createMany($input);

        return $this->getObjectModel();
    }

    /**
     * create many record
     *
     * @param array $data
     *
     * @return void
     */
    private function createMany(array $data): void
    {
        foreach ($data as $value) {
            parent::create(new Request($value));
        }
    }

    /**
     * tranform input request
     *
     * @param Request $request
     *
     * @return array
     */
    private function transformRequest(Request $request): array
    {
        $data = $request->except(['req', 'slug', 'controller', 'permission']);

        return array_map(fn ($value) => array_merge($value, [
            'display_name' => $value['display_name'] ?? Str::title($value['collumn'])
        ]), $data['hascrud_rows']);
    }
}
