<?php

namespace App\Models\Traits;

use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowTemplate;
use JWadhams\JsonLogic;

trait HasWorkflowTrait
{
    /**
     * Workflows that this entity attached to
     *
     * @return     MorphOne
     */
    public function workflows()
    {
        return $this->morphOne(Workflow::class, 'originator');
    }

    /**
     * added bindings to the model for event bindings
     */
    public static function bootHasWorkflowTrait()
    {
        // whent the model is created
        static::created(function ($entity) {
            // get the name of the model
            $modelName = get_class($entity);
            // find the auto start workflow templates
            $templates = WorkflowTemplate::where([
                'model_name' => $modelName,
                'autostart'  => true,
            ])->get();

            // filter the workflow that fulfil the condition
            $autostartWorkflowTemplates = $templates->filter(function ($template) use ($entity) {
                $logic = $template->start_condition;
                $data  = $entity->toArray();

                // apply the logic with the data
                $result = JsonLogic::apply($logic, $data);

                // logic is wrong therefore it didn't return boolean
                if (!is_bool($result)) {
                    return false;
                }

                return $result;
            });

            // create the workflow
            $autostartWorkflowTemplates->each(function ($template) use ($modelName, $entity) {
                Workflow::create([
                    'state'                => 'started',
                    'originator_type'      => $modelName,
                    'originator_id'        => $entity->id,
                    'workflow_template_id' => $template->id,
                ]);
            });
        });
    }
}
