<?php


namespace Tests\Dummies;

use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    protected $fillable = ['id', 'name', 'dummy_relation_model_id'];

    public function relation()
    {
        return $this->belongsTo(DummyRelationModel::class, 'dummy_relation_model_id', 'id');
    }
}
