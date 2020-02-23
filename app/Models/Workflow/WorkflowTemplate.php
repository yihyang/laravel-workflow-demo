<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;

class WorkflowTemplate extends Model
{
    //
    protected $fillable = [
        'autostart',
        'model_name',
        'name',
        'start_condition',
    ];
    protected $casts = [
        'start_condition' => 'json'
    ];

    /**
     * workflows that this template has produced
     *
     * @return HasMany
     */
    public function workflows()
    {
        return $this->hasMany(Workflow::class);
    }
}
