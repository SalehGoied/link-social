<?php

namespace App\Services;

use App\Models\React;

class ReactService
{
    /**
     * store and unstore react
     * 
     * @authenticated
     * @param Model $model
     * @param $coulmans
     * @return $reacts
     */
    public function store($model, $columns = []){
        
        $react = $model->reacts()->where('user_id', auth()->id())->first();

        if($react){
            $react->delete();
        }
        else{
            $model->reacts()->create($columns+['user_id'=> auth()->id()]);
        }

        return $model->reacts;
    }
}