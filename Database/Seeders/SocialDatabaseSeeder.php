<?php

namespace Cms\Modules\Social\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SocialDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        // $this->call(__NAMESPACE__.'\Seeder');
    }
}
