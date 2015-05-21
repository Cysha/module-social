<?php namespace Cms\Modules\Social\Services;

use Config;
use Request;

/**
 * This class will inject a new column into the user datatable
 * and fix the collection to make it use the providers
 */
class UserDatatable
{

    public function boot()
    {
        if (Request::url() !== route('admin.user.manager')) {
            return;
        }

        $this->resetCollection();
        $this->addProviderColumn();
    }

    private function resetCollection()
    {
        Config::set('cms.auth.datatable.user-manager.options.collection', function () {
            $model = config('auth.model');
            return $model::with(['roles', 'providers'])->get();
        });
    }

    public function addProviderColumn()
    {
        $config = config('cms.auth.datatable.user-manager.columns');
        $column = [
            'providers' => [
                'th'        => 'Providers',
                'tr'        => function ($model) {
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
                'filtering' => true,
                'width'     => '15%',
            ]
        ];

        array_splice($config, 4, 0, $column);
        Config::set('cms.auth.datatable.user-manager.columns', $config);
    }

}
