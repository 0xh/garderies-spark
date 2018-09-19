<?php

namespace App\Charts;

use App\Nursery;
use App\User;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

class BookingsChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $user       = auth()->user();
        $team       = ($user) ? $user->currentTeam() : null;

        $users      = $team->users->where('id', '!=', $user->id);
        $users_ids  = array_flatten($users->pluck('id'));

        $monthly_bookings = DB::table('bookings')
            ->select(DB::raw("MONTH(start) as month"), DB::raw("COUNT(MONTH(start)) count"))
            ->whereYear('start', date('Y'))
            ->whereNull('deleted_at')
            ->whereIn('user_id', $users_ids)
            ->orWhereIn('substitute_id', $users_ids)
            ->groupBy('month')
            ->get();

        $monthly_bookings_dataset = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($monthly_bookings as $booking) {
            if ( ($booking->month - 1) <= date('m')) {
                $monthly_bookings_dataset[$booking->month - 1] = $booking->count;
            }
        }

        $monthly_availabilities = DB::table('availabilities')
            ->select(DB::raw("MONTH(start) as month"), DB::raw("COUNT(MONTH(start)) count"))
            ->whereYear('start', date('Y'))
            ->whereNull('deleted_at')
            ->whereIn('user_id', $users_ids)
            ->groupBy('month')
            ->get();

        $monthly_availabilities_dataset = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($monthly_availabilities as $availability) {
            $monthly_availabilities_dataset[$availability->month - 1] = $availability->count;
        }

        $this->labels(['Jan.', 'Fév.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.']);

        $this->dataset('Remplacements', 'line', $monthly_bookings_dataset)
            ->options([
                'backgroundColor'       => '#20aee300',
                'borderColor'           => '#20aee390',
                'pointBackgroundColor'  => '#20aee390',
                'pointBorderColor'      => '#20aee300',
                'pointHoverBackgroundColor'  => '#20aee3',
                'pointHoverBorderColor'      => '#20aee3',
                'pointStyle'            => 'circle',
                'borderWidth'           => 3,
                'lineTension'           => 0.3
            ]);

        $this->dataset('Disponibilités', 'line', $monthly_availabilities_dataset)
            ->options([
                'backgroundColor'       => '#cddc3900',
                'borderColor'           => '#cddc3990',
                'pointBackgroundColor'  => '#8bc34a90',
                'pointBorderColor'      => '#8bc34a00',
                'pointHoverBackgroundColor'  => '#8bc34a',
                'pointHoverBorderColor'      => '#8bc34a',

                'pointStyle'            => 'circle',
                'borderWidth'           => 3,
                'lineTension'           => 0.3
            ]);

        $this->options([
            'animation' => ['duration' => 1000],
            'layout'    => [
                'padding' => [
                    'top'       => 10,
                    'right'     => 10,
                    'bottom'    => 10,
                    'left'      => 10
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
                        'ticks' => ['beginAtZero' => true],
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
