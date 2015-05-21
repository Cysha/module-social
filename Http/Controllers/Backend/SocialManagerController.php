<?php namespace Cms\Modules\Social\Http\Controllers\Backend;

use Cms\Modules\Core\Http\Controllers\BaseAdminController;
use Cms\Modules\Social\Services\Social;

class SocialManagerController extends BaseAdminController
{
    /**
     * Render a table for the providers
     * @return View
     */
    public function getIndex(Social $social)
    {
        $this->theme->setTitle('<i class="fa fa-share-alt-square"></i> Social Manager');
        $this->theme->breadcrumb()->add('Social Manager', route('admin.config.social'));

        return $this->setView('admin.config.social', [
            'socialiteProviders' => $social->getProviders(),
            'installedProviders' => $social->getInstalledProviders(),
        ]);
    }
}
