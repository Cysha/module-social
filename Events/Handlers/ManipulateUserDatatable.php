<?php

namespace Cms\Modules\Social\Events\Handlers;

use Cms\Modules\Admin\Events\GotDatatableConfig;
use Illuminate\Support\Facades\Request;

class ManipulateUserDatatable
{
    /**
     * Handle the event.
     *
     * @param GotDatatableConfig $event
     */
    public function handle(GotDatatableConfig $event)
    {
        if (Request::url() !== route('admin.user.manager')) {
            return;
        }

        $this->resetCollection($event->config);
        $this->addProviderColumn($event->config);

        return $event->config;
    }

    private function resetCollection(&$config)
    {
        array_set($config, 'options.collection', function () {
            $model = config('cms.auth.config.user_model');

            return $model::with(['roles', 'providers'])->get();
        });
    }

    private function addProviderColumn(&$config)
    {
        $column['providers'] = [
            'th' => 'Providers',
            'tr' => function ($model) {
                $providers = null;

                if (!$model->providers->count()) {
                    return $providers;
                }

                $tpl = '<span class="label label-default">%s</span>&nbsp;';
                foreach ($model->providers as $p) {
                    $providers .= sprintf($tpl, ucwords($p->provider));
                }

                return $providers;
            },
            'sortable' => true,
            'width' => '15%',
        ];

        array_splice($config['columns'], 4, 0, $column);
        //array_set($config, 'columns', $config);
    }
}
