<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DayoffResource\Pages;
use App\Filament\Resources\DayoffResource\RelationManagers;
use App\Models\Dayoff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Components\DateTimeTextColumn;

class DayoffResource extends Resource
{
    protected static ?string $model = Dayoff::class;

    public static function getPluralModelLabel(): string
        {
           return \Lang::get('lang.title.dayoff');
        }

    public static function getNavigationGroup(): string
        {
            return \Lang::get('lang.title.settinggroups');
        }

    public static function getNavigationLabel(): string
        {
                return \Lang::get('lang.title.dayoff');
        }        

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() || false;
    }


    public static function form(Form $form): Form
    {


        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nazwa')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('date')
                    ->label('Data')
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->locale(\Config::get('app.locale'))
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Checkbox::make('active')
                    ->label('Aktywny')
                    ->default(true)
                    ->columnSpanFull(),
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nazwa'),
                DateTimeTextColumn::make('date')->label('Data')->customFormat('Y-m-d'),
                Tables\Columns\BooleanColumn::make('active')->label('Aktywny'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDayoffs::route('/'),
            'create' => Pages\CreateDayoff::route('/create'),
            'edit' => Pages\EditDayoff::route('/{record}/edit'),
        ];
    }
}
