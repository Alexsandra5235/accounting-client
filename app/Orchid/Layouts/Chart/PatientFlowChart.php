<?php

namespace App\Orchid\Layouts\Chart;

use App\Services\Api\ApiService;
use Orchid\Screen\Layouts\Chart;

class PatientFlowChart extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'line';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;

}
