<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EmployeeResource;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }
}
