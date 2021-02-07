<?php

namespace Sheenazien8\Hascrudactions\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Sheenazien8\Hascrudactions\Abstracts\Repository;
use Sheenazien8\Hascrudactions\Models\Hascrud;

/**
 * Class HascrudRepository
 * @author sheenazien8
 */
class HascrudRepository extends Repository
{
    /**
     * inject model to repositories
     */
    public function __construct()
    {
        parent::__construct(new Hascrud());
    }

    /**
     * create hascrud action file and insert to database
     *
     * @param Request $request
     *
     * @throws Exception
     *
     * @return Hascrud
     */
    public function create(Request $request): Hascrud
    {
        return DB::transaction(function () use ($request) {
            $input = $this->transformRequest($request);

            $hascrud = parent::create(new Request($input));

            app(HascrudRowRepository::class)->setParent($hascrud, 'hascrud')->create($request);
            $this->generateArtisan($input);

            return $hascrud;
        });
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
        $data = $request->except('req');

        return array_merge($data, [
            'request' => json_encode($request->req),
            'show_plural_name' => Str::plural(Str::title($request->slug)),
            'show_singular_name' => Str::singular(Str::title($request->slug))
        ]);
    }

    /**
     * Generate File via artisan command
     *
     * @param array $input
     *
     * @return void
     */
    private function generateArtisan(array $input): void
    {
        $repository = Str::title($input['slug']) . 'Repository';
        $model = config('hascrudactions.model_folder') . $repository;
        Artisan::call('hascrudaction:repository', [
            'name' => $repository,
            '--model' => $model
        ]);
        Artisan::call('hascrudaction:controller', [
            'name' => $input['controller'],
            '--viewpath' => $input['slug'],
            '--repository' => $repository
        ]);
    }
}
