<?php

namespace Cms\Modules\Social\Models;

class UserProvider extends BaseModel
{
    protected $table = 'user_providers';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'username', 'email', 'name', 'provider', 'provider_id', 'avatar', 'created_at', 'updated_at'];

    public function users()
    {
        return $this->hasOne(config('auth.model'));
    }
}
