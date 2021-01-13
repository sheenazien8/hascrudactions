<?php

namespace Sheenazien8\Hascrudactions\Traits;

use Sheenazien8\Hascrudactions\Exceptions\ServiceActionsException;
use Sheenazien8\Hascrudactions\Facades\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Sheenazien8\Hascrudactions\Helpers;

/**
 * Trait HasCrudActions
 * @author sheenazien
 */
trait HasCrudActions
{
    protected $repository;
    /**
     * @param repository
     */
    public function __construct()
    {
        $this->repository = new $this->repositoryClass();
    }

    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index()
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        $request = resolve($this->indexRequest);

        if (isset($this->permission)) {
            if ($this->permission && $request->type != 'select2') {
                $this->authorize("browse-$this->permission");
            }
        }

        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            if (isset($this->indexService)) {
                if (count($this->indexService) > 2) {
                    throw new ServiceActionsException('Index Service property is cant to more 2 index');
                }
                if (!is_array($this->indexService)) {
                    throw new ServiceActionsException('Index Service property must be array');
                }
                $resources = (new $this->indexService[0])->{$this->indexService[1]}($request);

                if (isset($this->return) && $this->return == 'api') {
                    return Response::success($resources);
                }

                return $this->repository->getObjectModel()->table($resources);
            } else {
                if (isset($this->return) && $this->return == 'index') {
                    return;
                }
                if ($request->type == 'select2') {
                    $result = $this->repository->query()->select('id', $request->key)->when(
                        $request->term && $request->key,
                        function ($query) use ($request) {
                            return $query->where($request->key, 'LIKE', '%%' . $request->term . '%%');
                        }
                    )->when(
                        $request->oldValue,
                        function ($query) use ($request) {
                            $str = ltrim($request->oldValue, '[');
                            $str = rtrim($str, ']');
                            $array = explode(',', $str);
                            if ($request->oldValue) {
                                if (is_array($array) && count($array) > 1) {
                                    return $query->whereIn('id', $array);
                                }
                                return $query->where('id', $request->oldValue);
                            }
                        }
                    )->when($request->filter, function ($query) use ($request) {
                        return $query->where($request->filter['key'], $request->filter['value']);
                    })->get()->toArray();

                    return Response::success($result);
                }

                if (isset($this->return) && $this->return == 'api') {
                    $result = $this->repository->query()->get()->toArray();

                    return Response::success($result);
                }

                return $this->repository->datatable($request);
            }
        }

        $resources = $this->permission ?? $this->redirect;

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return view("{$this->viewPath}.index", [
            'resources' => $resources
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        if (isset($this->permission)) {
            $this->authorize("create-$this->permission");
        }

        $resources = $this->permission ?? $this->redirect;

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return view("{$this->viewPath}.create", [
            'resources' => $resources
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mix
     */
    public function store()
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        $request = resolve($this->storeRequest);

        if (isset($this->permission)) {
            $this->authorize("create-$this->permission");
        }

        if (isset($this->storeService)) {
            if (count($this->storeService) > 2) {
                throw new ServiceActionsException('Store Service property is cant to more 2 index');
            }
            if (!is_array($this->storeService)) {
                throw new ServiceActionsException('Store Service property must be array');
            }
            $data = (new $this->storeService[0])->{$this->storeService[1]}($request);
        } else {
            $data = $this->repository->create($request);
        }
        $message = __('hascrudactions::app.global.message.success.create') . ' ' . ucfirst($this->permission ?? '');

        /* if (method_exists($data, 'logs')) { */
        /*     Activity::modelable($data)->auth()->creating(); */
        /* } */

        if (isset($this->return) && $this->return == 'api') {
            return Response::success($data);
        }

        return redirect()->to($this->redirect)->with('message', [
            'success' => Helpers::dash_to_space($message)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $model
     * @return mix
     */
    public function show(int $model)
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        if (isset($this->permission)) {
            $this->authorize("browse-{$this->permission}");
        }

        $data = $this->repository->find($model);

        if (request()->ajax() || isset($this->return) && $this->return == 'api') {
            if (isset($this->showService)) {

                if (count($this->showService) > 2) {
                    throw new ServiceActionsException('Index Service property is cant to more 2 show');
                }
                if (!is_array($this->showService)) {
                    throw new ServiceActionsException('Index Service property must be array');
                }
                $resources = (new $this->showService[0])->{$this->showService[1]}($data);

                if (isset($this->return) && $this->return == 'api') {
                    return Response::success($resources);
                }

                return Response::success($resources);
            }

            return Response::success($data->toArray());
        }

        $resources = $this->permission ?? $this->redirect;

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return view("{$this->viewPath}.show", [
            'resources' => $resources,
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $model
     * @return \Illuminate\View\View
     */
    public function edit(int $model)
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        $data = $this->repository->find($model);
        if (isset($this->permission)) {
            $this->authorize("update-{$this->permission}");
        }
        $resources = $this->permission ?? $this->redirect;

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return view("{$this->viewPath}.edit", [
            'resources' => $resources,
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $model): RedirectResponse
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        $data = $this->repository->find($model);

        $request = resolve($this->updateRequest);

        if (isset($this->permission)) {
            $this->authorize("update-{$this->permission}");
        }

        if (isset($this->updateService)) {
            if (count($this->updateService) > 2) {
                throw new ServiceActionsException('Store Service property is cant to more 2 index');
            }
            if (!is_array($this->updateService)) {
                throw new ServiceActionsException('Store Service property must be array');
            }
            $data = (new $this->updateService[0])->{$this->updateService[1]}($data, $request);
        } else {
            $data = $this->repository->update($request, $data);
        }

        $message = __('hascrudactions::app.global.message.success.update') . ' ' . ucfirst($this->permission ?? '');

        if (isset($this->return) && $this->return == 'api') {
            return response()->json($data, 200);
        }

        /* if (method_exists($data, 'logs')) { */
        /*     Activity::modelable($data)->auth()->updating(); */
        /* } */

        return redirect()->to($this->redirect)->with('message', [
            'success' => Helpers::dash_to_space($message)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $model): RedirectResponse
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        if (isset($this->permission)) {
            $this->authorize("delete-{$this->permission}");
        }

        $data = $this->repository->find($model);

        $data->delete();

        $message = __('hascrudactions::app.global.message.success.delete') . ' ' . ucfirst($this->permission ?? '');


        return redirect()->to($this->redirect)->with('message', [
            'success' => Helpers::dash_to_space($message)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(): RedirectResponse
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        if (isset($this->permission)) {
            $this->authorize("create-$this->permission");
        }

        /* $request = resolve($this->bulkDestroyRequest); */

        $this->repository->bulkDestroy(request());

        $message = __('hascrudactions::app.global.message.success.delete') . ' ' . ucfirst($this->permission ?? '');

        return redirect()->to($this->redirect)->with('message', [
            'success' => Helpers::dash_to_space($message)
        ]);
    }

    public function downloadTemplate()
    {
        if ($this->permission) {
            $this->authorize("create-$this->permission");
        }

        $string = Str::title(dash_to_space($this->permission));

        $classExport = 'App\Exports\\Template' . $string . 'Export';

        return Excel::download(new $classExport, now()->format('Y-m-d-his') . "-template-{$this->permission}s.xlsx");
    }

    public function importTemplate(Request $request)
    {
        if ($this->permission) {
            $this->authorize("create-$this->permission");
        }
        $string = Str::title(dash_to_space($this->permission));

        $classExport = 'App\Imports\\' . $string . 'Import';

        Excel::import(new $classExport, $request->file("{$this->permission}-import"));

        return;
    }
}
