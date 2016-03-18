<?php

namespace Cms\Modules\Social\Models;

use Cms\Modules\Core\Models\BaseModel as CBM;

class BaseModel extends CBM
{
    public function __construct()
    {
        parent::__construct();

        $prefix = config('cms.auth.config.table-prefix', 'auth_');
        $this->table = $prefix.$this->table;
    }
}
