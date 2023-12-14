<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $us = Country::where('country_code', 'US')->withCount('employees')->first();
        $ng = Country::where('country_code', 'NGN')->withCount('employees')->first();
        return [
            Stat::make('Employees', Employee::count()),
            Stat::make('US Employees', $us ? $us->employees_count : 0),
            Stat::make('Nigeria Employees', $ng ? $ng->employees_count : 0)
        ];
    }
}
