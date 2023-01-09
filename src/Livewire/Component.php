<?php

namespace OEngine\Core\Livewire;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Route;
use OEngine\Core\Facades\Core;
use OEngine\Core\Facades\Theme;
use OEngine\Core\Traits\WithDoAction;
use Illuminate\Support\Facades\File;
use Livewire\Component as ComponentBase;
use Livewire\ImplicitRouteBinding;
use Livewire\LifecycleManager;
use OEngine\Core\Facades\Slug;

class Component extends ComponentBase
{
    public function __invoke(Container $container, Route $route)
    {
        // With octane and full page components the route is caching the
        // component, so always create a fresh instance.
        $instance = new static;

        // For some reason Octane doesn't play nice with the injected $route.
        // We need to override it here. However, we can't remove the actual
        // param from the method signature as it would break inheritance.
        $route = request()->route() ?? $route;

        try {
            $componentParams = (new ImplicitRouteBinding($container))
                ->resolveAllParameters($route, $instance);
        } catch (ModelNotFoundException $exception) {
            if (method_exists($route, 'getMissing') && $route->getMissing()) {
                return $route->getMissing()(request());
            }

            throw $exception;
        }
        foreach (Slug::getParameters() as $key => $value) {
            $componentParams[$key] = $value;
        }
        $manager = LifecycleManager::fromInitialInstance($instance)
            ->boot()
            ->initialHydrate()
            ->mount($componentParams)
            ->renderToView();

        if ($instance->redirectTo) {
            return redirect()->response($instance->redirectTo);
        }

        $instance->ensureViewHasValidLivewireLayout($instance->preRenderedView);

        $layout = $instance->preRenderedView->livewireLayout;

        return app('view')->file(__DIR__ . "/Macros/livewire-view-{$layout['type']}.blade.php", [
            'view' => $layout['view'],
            'params' => $layout['params'],
            'slotOrSection' => $layout['slotOrSection'],
            'manager' => $manager,
        ]);
    }
    public $childSlot;
    use WithDoAction;
    public $_code_permission = "";
    public function checkPermissionView()
    {
        return !$this->_code_permission || ($this->_code_permission && Core::checkPermission($this->_code_permission));
    }
    public $_dataTemps = [];
    protected function getListeners()
    {
        return ['refreshData' . $this->id => '__loadData'];
    }

    public function __loadData()
    {
    }

    public function refreshData($option = [])
    {
        if (!isset($option['id'])) $option['id'] = $this->id;
        $this->dispatchBrowserEvent('reload_component', $option);
    }

    public function redirectCurrent()
    {
        return redirect(request()->header('Referer'));;
    }
    public function showMessage($option)
    {
        $this->dispatchBrowserEvent('swal-message', $option);
    }

    public function __construct($id = null)
    {
        parent::__construct($id);
    }
    public function View($view)
    {
        return File::get(Core::getPathDirFromClass($this) . '/' . str_replace('.', '/', $view) . '.blade.php');
    }
    protected function ensureViewHasValidLivewireLayout($view)
    {
        if ($view == null) {
            return;
        }
        parent::ensureViewHasValidLivewireLayout($view);
        $view->extends(Theme::Layout())->section('content');
    }
}
