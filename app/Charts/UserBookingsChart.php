<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

class UserBookingsChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct($user_ID)
    {
        parent::__construct();

        $monthly_bookings = DB::table('bookings')
            ->select(DB::raw("MONTH(start) as month"), DB::raw("COUNT(MONTH(start)) count"))
            ->where('substitute_id', $user_ID)
            ->whereYear('start', date('Y'))
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->get();
        $monthly_bookings_dataset = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($monthly_bookings as $booking) {
            if (($booking->month - 1) <= date('m')) {
                $monthly_bookings_dataset[$booking->month - 1] = $booking->count;
            }
        }

        $this->labels(['Jan.', 'Fév.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.']);

        $this->dataset('Remplacements', 'line', $monthly_bookings_dataset)
            ->options([
                'backgroundColor'       => '#20aee300',
                'borderColor'           => '#20aee390',
                'pointBackgroundColor'  => '#20aee300',
                'pointBorderColor'      => '#20aee300',
                'pointStyle'            => 'circle',
                'borderWidth'           => 3,
                'lineTension'           => 0.3
            ]);

        $this->options([
            'animation' => ['duration' => 0],
            'layout' => [
                'padding' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10
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
                'xAxes' => [
                    [
                        'gridLines' => [
                            'color' => 'rgba(0, 0, 0, 0.01)'
                        ],
                    ]
                ],
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true,
                            'suggestedMax' => 3,
                            'stepSize' => 1
                        ],
                        'gridLines' => [
                            'color' => 'rgba(0, 0, 0, 0.05)'
                        ]
                    ],
                ],
            ],
        ]);

    }
}
