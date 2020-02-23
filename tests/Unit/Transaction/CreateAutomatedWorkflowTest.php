<?php

namespace Tests\Unit;

use App\Models\Workflow\WorkflowTemplate;
use App\Transaction;
use Tests\TestCase;

class CreateAutomatedWorkflowTest extends TestCase
{
    public function test_workflow_with_correct_condition_and_autostart_flag_set_to_true_will_be_created_automatically()
    {
        $this->workflowTemplate = WorkflowTemplate::create([
            'name'            => 'Automated Workflow 1',
            'model_name'      => Transaction::class,
            'autostart'       => true,
            'start_condition' => [
                'and' => [
                    [
                        '==' => [['var' => 'type'], 'Personal Loan'],
                    ],
                    [
                        'and' => [
                            [
                                '>=' => [['var' => 'data.amount'], 10000],
                            ],
                            [
                                '<' => [['var' => 'data.amount'], 50000],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $transaction = Transaction::create([
            'type' => 'Personal Loan',
            'data' => [
                'amount' => 12345,
            ],
        ]);

        $this->assertDatabaseHas(
            'workflows',
            [
                'originator_type'      => Transaction::class,
                'originator_id'        => $transaction->id,
                'workflow_template_id' => $this->workflowTemplate->id,
                'state'                => 'started',
            ]
        );
    }

    public function test_workflow_with_wrong_condition_and_autostart_flag_set_to_true_will_not_be_created_automatically()
    {
        $this->workflowTemplate = WorkflowTemplate::create([
            'name'            => 'Automated Workflow 2',
            'model_name'      => Transaction::class,
            'autostart'       => true,
            'start_condition' => [
                'and' => [
                    [
                        '==' => [['var' => 'type'], 'Personal Loan'],
                    ],
                    [
                        'and' => [
                            [
                                '>=' => [['var' => 'data.amount'], 10000],
                            ],
                            [
                                '<' => [['var' => 'data.amount'], 50000],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $transaction = Transaction::create([
            'type' => 'Housing Loan',
            'data' => [
                'amount' => 1,
            ],
        ]);

        $this->assertDatabaseMissing(
            'workflows',
            [
                'originator_type'      => Transaction::class,
                'originator_id'        => $transaction->id,
                'workflow_template_id' => $this->workflowTemplate->id,
                'state'                => 'started',
            ]
        );
    }

    public function test_workflow_with_correct_condition_and_autostart_flag_set_to_false_will_not_be_created_automatically()
    {
        $this->workflowTemplate = WorkflowTemplate::create([
            'name'            => 'Automated Workflow 3',
            'model_name'      => Transaction::class,
            'autostart'       => false,
            'start_condition' => [
                'and' => [
                    [
                        '==' => [['var' => 'type'], 'Personal Loan'],
                    ],
                    [
                        'and' => [
                            [
                                '>=' => [['var' => 'data.amount'], 10000],
                            ],
                            [
                                '<' => [['var' => 'data.amount'], 50000],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $transaction = Transaction::create([
            'type' => 'Housing Loan',
            'data' => [
                'amount' => 12345,
            ],
        ]);

        $this->assertDatabaseMissing(
            'workflows',
            [
                'originator_type'      => Transaction::class,
                'originator_id'        => $transaction->id,
                'workflow_template_id' => $this->workflowTemplate->id,
                'state'                => 'started',
            ]
        );
    }

    public function test_workflow_with_wrong_condition_and_autostart_flag_set_to_false_will_not_be_created_automatically()
    {
        $this->workflowTemplate = WorkflowTemplate::create([
            'name'            => 'Automated Workflow 4',
            'model_name'      => Transaction::class,
            'autostart'       => false,
            'start_condition' => [
                'and' => [
                    [
                        '==' => [['var' => 'type'], 'Personal Loan'],
                    ],
                    [
                        'and' => [
                            [
                                '>=' => [['var' => 'data.amount'], 10000],
                            ],
                            [
                                '<' => [['var' => 'data.amount'], 50000],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $transaction = Transaction::create([
            'type' => 'Housing Loan',
            'data' => [
                'amount' => 1,
            ],
        ]);

        $this->assertDatabaseMissing(
            'workflows',
            [
                'originator_type'      => Transaction::class,
                'originator_id'        => $transaction->id,
                'workflow_template_id' => $this->workflowTemplate->id,
                'state'                => 'started',
            ]
        );
    }
}
