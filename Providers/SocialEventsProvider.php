<?php

namespace Cms\Modules\Social\Providers;

use Cms\Modules\Core\Providers\BaseEventsProvider;

class SocialEventsProvider extends BaseEventsProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Cms\Modules\Admin\Events\GotDatatableConfig' => [
            'Cms\Modules\Social\Events\Handlers\ManipulateUserDatatable',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [

    ];

    /**
     * Register any other events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
