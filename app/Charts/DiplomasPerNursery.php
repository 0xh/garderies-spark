<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

class DiplomasPerNursery extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct($nursery)
    {
        parent::__construct();

        $users = DB::table('users')
            ->join('diplomas', 'users.diploma_id', '=', 'diplomas.id')
            ->select(DB::raw("COUNT(users.id) count"), 'diploma_id')
            ->where('nursery_id', $nursery)
            ->groupBy('diploma_id')
            ->orderBy('diploma_id')
            ->get();

        $this->labels(['Auxiliaire', 'ASE', 'Educatrice ES']);

        $dataset = [0, 0, 0];

        foreach ($users as $user) {
            $dataset[$user->diploma_id - 1] = $user->count;
        }

        $this->dataset('Diplomes', 'bar', $dataset)
            ->options(['backgroundColor' => ['#20aee3', '#4caf50', '#e81e63']]);

        $this->options([
            'animation' => ['duration' => 1000],
            'legend'    => ['display' => false],
            'layout'    => [
                'padding' => [
                    'top'       => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'left'      => 0
                ]
            ],
            'elements' => [
                'point' => [
                    'radius' => 2,
                    'hoverRadius' => 5,
                    'hitRadius' => 10,
                ]
            ],
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => ['beginAtZero' => true, 'stepSize' => 1],
                        'gridLines' => ['color' => 'rgba(0, 0, 0, 0.05)']
                    ]
                ],
                'xAxes' => [
                    [
                        'gridLines' => ['color' => 'rgba(0, 0, 0, 0.0)']
                    ]
                ]
            ]
        ]);

    }
}
