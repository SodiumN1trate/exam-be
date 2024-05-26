<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $stats = null;
        if ($request->sort && $request->sortBy === 'asc') {
            $stats = $this->usersStats()->sortBy($request->sort);
        } elseif ($request->sort) {
            $stats = $this->usersStats()->sortBy($request->sort ?? 'distance')->reverse();
        } else {
            $stats = $this->usersStats()->sortBy('distance')->reverse();
        }
        return view('dashboard')->with([
            'a' => $this->overallPercentage(),
            'b' => $this->averageDistance(),
            'stats' => $stats,
            'graph' => $this->graph(),
        ]);
    }


    // Calculate overall percentage
    private function overallPercentage()
    {
        $trips = Trip::count();
        $carTrips = Trip::where('transportation_id', 1)->count();
        $bicycleTrips = Trip::where('transportation_id', 2)->count();
        $publicTransportTrips = Trip::where('transportation_id', 3)->count();

        return [
            'cars' =>  round(($carTrips * 100) / $trips, 2),
            'bicycles' =>  round(($bicycleTrips * 100) / $trips, 2),
            'publicTransports' =>  round(($publicTransportTrips * 100) / $trips, 2)
        ];
    }


    // Calculate average distance for every transport type
    private function averageDistance()
    {
        return [
            'cars' => round(array_sum(Trip::where('transportation_id', 1)->pluck('distance')->toArray()) / Trip::where('transportation_id', 1)->count(), 2),
            'bicycles' => round(array_sum(Trip::where('transportation_id', 2)->pluck('distance')->toArray()) / Trip::where('transportation_id', 2)->count(), 2),
            'publicTransports' => round(array_sum(Trip::where('transportation_id', 3)->pluck('distance')->toArray()) / Trip::where('transportation_id', 3)->count(), 2),
        ];
    }

    // Calculate user statistics of transport usage
    private function usersStats()
    {
        return Employee::get()->map(function($user) {
            // Calculate Percentage
            $count = $user->transport()->count();

            // Car usage
            $cars = $user->transport()->where('transportation_id', 1)->count();
            if ($cars === 0) {
                $user->cars = 0;
            } else {
                $user->cars = round((($cars * 100)) / $count, 2);
            }

            // bicycles usage
            $bicycles = $user->transport()->where('transportation_id', 2)->count();
            if ($bicycles === 0) {
                $user->bicycles = 0;
            } else {
                $user->bicycles = round((($bicycles * 100)) / $count, 2);
            }

            // publicTransports usage
            $publicTransports = $user->transport()->where('transportation_id', 3)->count();
            if ($publicTransports === 0) {
                $user->publicTransports = 0;
            } else {
                $user->publicTransports = round((($publicTransports * 100)) / $count, 2);
            }

            // Average distance
            if (count($user->transport()->pluck('distance')) > 0) {
                $user->distance = round(array_sum($user->transport()->pluck('distance')->toArray()) / $count, 2);
            } else {
                $user->distance = 0;
            }

            // Average duration
            $user->durations = round(array_sum($user->transport()->get()->map(function ($transport) {
                return (new Carbon($transport->end_date))->diffInMinutes((new Carbon($transport->start_date)));
            })->toArray()) / $count, 2);

            return $user;
        });
    }


    // Graph data
    private function graph()
    {
        $cars = [
            Trip::where('transportation_id', 1)->whereDate('start_date', '2024-03-18')->count(),
            Trip::where('transportation_id', 1)->whereDate('start_date', '2024-03-19')->count(),
            Trip::where('transportation_id', 1)->whereDate('start_date', '2024-03-20')->count(),
            Trip::where('transportation_id', 1)->whereDate('start_date', '2024-03-21')->count(),
            Trip::where('transportation_id', 1)->whereDate('start_date', '2024-03-22')->count(),
        ];

        $bicycles = [
            Trip::where('transportation_id', 2)->whereDate('start_date', '2024-03-18')->count(),
            Trip::where('transportation_id', 2)->whereDate('start_date', '2024-03-19')->count(),
            Trip::where('transportation_id', 2)->whereDate('start_date', '2024-03-20')->count(),
            Trip::where('transportation_id', 2)->whereDate('start_date', '2024-03-21')->count(),
            Trip::where('transportation_id', 2)->whereDate('start_date', '2024-03-22')->count(),
        ];

        $publicTransports = [
            Trip::where('transportation_id', 3)->whereDate('start_date', '2024-03-18')->count(),
            Trip::where('transportation_id', 3)->whereDate('start_date', '2024-03-19')->count(),
            Trip::where('transportation_id', 3)->whereDate('start_date', '2024-03-20')->count(),
            Trip::where('transportation_id', 3)->whereDate('start_date', '2024-03-21')->count(),
            Trip::where('transportation_id', 3)->whereDate('start_date', '2024-03-22')->count(),
        ];

        return [
            'cars' => $cars,
            'bicycles' => $bicycles,
            'publicTransports' => $publicTransports,
        ];
    }
}
