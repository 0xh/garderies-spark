<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

class BookingPurposesPerNursery extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct($nursery)
    {
        parent::__construct();

        $bookings = DB::table('bookings')
            ->join('purposes', 'bookings.purpose_id', '=', 'purposes.id')
            ->select(DB::raw("COUNT(bookings.id) count"), 'purpose_id')
            ->where('nursery_id', $nursery)
            ->whereYear('start', date('Y'))
            ->groupBy('purpose_id')
            ->orderBy('purpose_id')
            ->get();

        $bookings_last_year = DB::table('bookings')
            ->join('purposes', 'bookings.purpose_id', '=', 'purposes.id')
            ->select(DB::raw("COUNT(bookings.id) count"), 'purpose_id')
            ->where('nursery_id', $nursery)
            ->whereYear('start', date('Y') - 1)
            ->groupBy('purpose_id')
            ->orderBy('purpose_id')
            ->get();

        $this->labels(['Maladie', 'Vacances', 'Congé maternité', 'Service mil. / civil']);

        $dataset            = [0, 0, 0, 0];
        $dataset_last_year  = [0, 0, 0, 0];

        foreach ($bookings as $booking) {
            $dataset[$booking->purpose_id - 1] = $booking->count;
        }
        foreach ($bookings_last_year as $booking) {
            $dataset_last_year[$booking->purpose_id - 1] = $booking->count;
        }

        $this->dataset((date('Y') - 1), 'bar', $dataset_last_year)
            ->options(['backgroundColor' => ['#ccc', '#ccc', '#ccc', '#ccc']]);

        $this->dataset(date('Y'), 'bar', $dataset)
            ->options(['backgroundColor' => ['#20aee3', '#4caf50', '#e81e63', '#607d8b']]);

        $this->options([
            'animation' => ['duration' => 1000],
            'legend'    => ['display' => true],
            'layout'    => [
                'padding' => [
                    'top'       => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'left'      => 0
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
                        'gridLines' => ['color' => 'rgba(0, 0, 0, 0)']
                    ]
                ]
            ]
        ]);

    }
}
