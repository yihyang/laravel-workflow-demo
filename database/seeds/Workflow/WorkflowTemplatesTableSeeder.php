<?php

use App\Models\Workflow\WorkflowTemplate;
use App\Transaction;
use Illuminate\Database\Seeder;

class WorkflowTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 'Low Amount Loan' workflow
        WorkflowTemplate::firstOrCreate([
            'name'            => 'Low Amount Loan',
            'model_name'      => Transaction::class,
            'autostart'       => true,
            'start_condition' => [
                'and' => [
                    [
                        '==' => [['var' => 'type'], 'Personal Loan'],
                    ],
                    [
                        '<' => [['var' => 'data.amount'], 10000],
                    ],
                ],
            ],
        ]);

        // 'Medium Amount Loan' workflow
        WorkflowTemplate::firstOrCreate([
            'name'            => 'Medium Amount Loan',
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
                                '>=' => [['var' => 'data.amount'], 10000]
                            ],
                            [
                                '<'  => [['var' => 'data.amount'], 50000]
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        // 'High Amount Loan' workflow
        WorkflowTemplate::firstOrCreate([
            'name'            => 'High Amount Loan',
            'model_name'      => Transaction::class,
            'autostart'       => true,
            'start_condition' => [
                'and' => [
                    [
                        '==' => [['var' => 'type'], 'Personal Loan'],
                    ],
                    [
                        '>=' => [['var' => 'data.amount'], 50000],
                    ],
                ],
            ],
        ]);
    }
}
