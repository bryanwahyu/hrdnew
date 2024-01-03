<?php
namespace Infra\Staff\Model;

use Infra\Shared\Models\BaseModel;

class Divison extends BaseModel{
    public function sub(){
        return $this->hasMany($this);
    }
    public function main(){
        return $this->belongsTo($this);
    }
}