<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    //
    protected $fillable = [
        'originator_id',
        'originator_type',
        'state',
        'workflow_template_id'
    ];

    /**
     * The template that this workflow referred from
     */
    public function template()
    {
        return $this->belongsTo(WorkflowTemplate::class);
    }
}
