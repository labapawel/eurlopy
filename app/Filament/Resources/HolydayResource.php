<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolydayResource\Pages;
use App\Filament\Resources\HolydayResource\RelationManagers;
use App\Models\Holyday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolydayResource extends Resource
{
    protected static ?string $model = Holyday::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        if(!auth()->user()->isAdmin())
        return parent::getEloquentQuery()->where('user_id', auth()->user()->getKey());
    return parent::getEloquentQuery();
    }

    public static function getPluralModelLabel(): string
        {
           return \Lang::get('lang.title.holydays');
        }

    public static function getNavigationBadgeColor(): ?string
        {
            return static::getModel()::count() > 10 ? 'warning' : 'primary';
        }

        public static function getNavigationBadge(): ?string
        {
            return '';
            return static::getModel()::count();
        }   


        // public static function getNavigationGroup(): string
        // {
        //     // dd(_('lang'));
        //     return \Lang::get('lang.title.settinggroups');
        // }

        public static function getNavigationLabel(): string
            {
                return \Lang::get('lang.title.holydays');
            }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label(__('lang.title.user'))
                    ->options(function () {
                        $user = auth()->user();
                        if ($user->isAdmin()) {
                            return \App\Models\User::pluck('name', 'id');
                        }
                        return \App\Models\User::where('id', $user->id)->pluck('name', 'id');
                    })
                    // ->options(\App\Models\User::pluck('name', 'id'))
                    ->required(),
                Select::make('holyday_type_id')
                    ->label(__('lang.title.holyday_type'))
                    ->options(\App\Models\HolydayType::pluck('name', 'id'))
                    ->required(),
                DateTimePicker::make('start_date')
                    ->label(__('lang.title.start_date'))
                    ->required(),
                DateTimePicker::make('end_date')
                    ->label(__('lang.title.end_date'))
                    ->required(),
                TextInput::make('hours')
                    ->label(__('lang.title.hours'))
                    ->numeric()
                    ->required(),
                Textarea::make('description')
                    ->label(__('lang.title.description')),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('lang.title.user'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('holydaytype.name')
                    ->label(__('lang.title.holyday_type'))
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('lang.title.start_date'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('lang.title.end_date'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours')
                    ->label(__('lang.title.hours'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('lang.title.description'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved')
                    ->label(__('lang.title.approved'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid')
                    ->label(__('lang.title.paid'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('active')
                    ->label(__('lang.title.active'))
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListHolydays::route('/'),
            'create' => Pages\CreateHolyday::route('/create'),
            'edit' => Pages\EditHolyday::route('/{record}/edit'),
        ];
    }
}
