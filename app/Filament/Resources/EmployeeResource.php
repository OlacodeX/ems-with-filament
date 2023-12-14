<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\State;
use App\Models\Country;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->description()
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 3,
                    ])
                    ->schema([
                        TextInput::make('first_name')->required()->maxLength(255),
                        TextInput::make('last_name')->required()->maxLength(255),
                        Select::make('country_id')
                        ->label('Country')
                        ->options(Country::all()->pluck('name','id')->toArray())
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('state_id', null))
                        ->required(),
                        Select::make('state_id')
                        ->label('State')
                        ->options(
                            function (callable $get) {
                                $country = Country::find($get('country_id'));
                                if (!$country) {
                                    return [];
                                }
                                return $country->states()->pluck('name','id')->toArray();
                            }
                            
                        )
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null))
                        ->required(),
                        Select::make('city_id')
                        ->label('City')
                        ->options(
                            function (callable $get) {
                                $state = State::find($get('state_id'));
                                if (!$state) {
                                    return [];
                                }
                                return $state->cities()->pluck('name','id')->toArray();
                            }
                            
                        )
                        ->reactive()
                        ->required(),
                        Select::make('department_id')
                        ->relationship(name: 'department', titleAttribute: 'name')
                        ->required(),
                        TextInput::make('address')->required()->maxLength(255),
                        TextInput::make('zip_code')->required()->maxLength(6),
                        DatePicker::make('birth_date')->required(),
                        DatePicker::make('date_hired')->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('city.name')->sortable()->searchable(),
                TextColumn::make('state.name')->sortable()->searchable(),
                TextColumn::make('country.name')->sortable()->searchable(),
                TextColumn::make('date_hired')->date(),
                TextColumn::make('birth_date')->date(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }    
}
