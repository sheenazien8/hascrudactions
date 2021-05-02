<?php

namespace Sheenazien8\Hascrudactions\Traits;

use Sheenazien8\Hascrudactions\Exceptions\ServiceActionsException;
use Sheenazien8\Hascrudactions\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Sheenazien8\Hascrudactions\Helpers\StrHelper;

/**
 * Trait HasCrudActions
 * @author sheenazien
 */
trait HasCrudAction
{
    protected $repository;

    /**
     *  available return method
     */
    private $RETURN = ['json', 'index', 'view'];

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

        $request = request();
        if (app_is('laravel')) {
            if (isset($this->indexRequest)) {
                $request = resolve($this->indexRequest);
            }
        }

        if (isset($this->permission)) {
            if ($this->permission && $request->type != 'select2') {
                $this->authorize("browse-$this->permission");
            }
        }

        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $this->repository->datatable($request);
        }

        $resources = except_last_word($request->route()->getName());

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

        $resources = except_last_word(request()->route()->getName());

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

        $request = request();
        if (app_is('laravel')) {
            if (isset($this->storeRequest)) {
                $request = resolve($this->storeRequest);
            }
        }

        if (isset($this->rules)) {
            if (is_array($this->rules)) {
                $this->validate($request, $this->rules);
            }
            if (is_string($this->rules)) {
                $request = resolve($this->rules);
            }
        }

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
        $resources = except_last_word(request()->route()->getName());

        $message = __('hascrudactions::app.global.message.success.create', [
            'item' => ucfirst($resources ?? '')
        ]);

        if (isset($this->return) && $this->return == 'api') {
            return Response::success(array_merge($data->toArray(), [
                'message' => $message
            ]));
        }

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return redirect()->to(route($this->redirect ?? $resources . '.index'))->with('message', [
            'success' => StrHelper::dash_to_space($message)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string|int $model
     * @return mix
     */
    public function show($model)
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
                $data = (new $this->showService[0])->{$this->showService[1]}($data);

                if (isset($this->return) && $this->return == 'api') {
                    return Response::success($data);
                }

                return Response::success($data);
            }

            return Response::success($data->toArray());
        }

        $resources = except_last_word(request()->route()->getName());

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
     * @param  string|int $model
     * @return \Illuminate\View\View
     */
    public function edit($model)
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        $data = $this->repository->find($model);

        if (isset($this->permission)) {
            $this->authorize("update-{$this->permission}");
        }

        $resources = except_last_word(request()->route()->getName());

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
     * @param  string|int $model
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
     */
    public function update($model)
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        $data = $this->repository->find($model);

        $request = request();
        if (app_is('laravel')) {
            if (isset($this->updateRequest)) {
                $request = resolve($this->updateRequest);
            }
        }

        if (isset($this->rules)) {
            if (is_array($this->rules)) {
                $this->validate($request, $this->rules);
            }
            if (is_string($this->rules)) {
                /* dd('ok', $this->rules); */
                $request = resolve($this->rules);
            }
        }

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

        $resources = except_last_word(request()->route()->getName());

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        $message = __('hascrudactions::app.global.message.success.update', [
            'item' => ucfirst($resources ?? '')
        ]);

        if (isset($this->return) && $this->return == 'api') {
            return Response::success(array_merge($data->toArray(), [
                'message' => $message
            ]));
        }

        return redirect()->to(route($this->redirect ?? $resources . '.index'))->with('message', [
            'success' => StrHelper::dash_to_space($message)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string|int $model
     * @return \Illuminate\Http\Response
     */
    public function destroy($model)
    {
        if (function_exists('get_lang')) {
            get_lang();
        }

        if (isset($this->permission)) {
            $this->authorize("delete-{$this->permission}");
        }

        $data = $this->repository->find($model);

        $data->delete();

        $resources = except_last_word(request()->route()->getName());

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        $message = __('hascrudactions::app.global.message.success.delete', [
            'item' => ucfirst($resources ?? '')
        ]);

        if (isset($this->return) && $this->return == 'api') {
            return Response::success([
                'id' => $model,
                'message' => $message
            ]);
        }

        return redirect()->to(route($this->redirect ?? $resources . '.index'))->with('message', [
            'success' => StrHelper::dash_to_space($message)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Illuminate\Http\Response
     */
    public function bulkDestroy()
    {
        $resources = except_last_word(request()->route()->getName());

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        $message = __('hascrudactions::app.global.message.fail.delete', [
            'item' => ucfirst($resources ?? '')
        ]);

        if (!request()->ids) {
            return redirect()->to(route($resources . '.index'))->with('message', [
                'error' => StrHelper::dash_to_space($message)
            ]);
        }

        if (function_exists('get_lang')) {
            get_lang();
        }

        if (isset($this->permission)) {
            $this->authorize("create-$this->permission");
        }

        $request = request();
        if (app_is('laravel')) {
            if (isset($this->bulkDestroyRequest)) {
                $request = resolve($this->bulkDestroyRequest);
            }
        }

        $this->repository->bulkDestroy($request);

        $message = __('hascrudactions::app.global.message.success.delete', [
            'item' => ucfirst($resources ?? '')
        ]);

        if (isset($this->return) && $this->return == 'api') {
            return Response::success([
                'id' => request()->ids,
                'message' => $message
            ]);
        }

        return redirect()->to(route($this->redirect ?? $resources . '.index'))->with('message', [
            'success' => StrHelper::dash_to_space($message)
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
