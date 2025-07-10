<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolydayTypeResource\Pages;
use App\Filament\Resources\HolydayTypeResource\RelationManagers;
use App\Models\HolydayType;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolydayTypeResource extends Resource
{
    protected static ?string $model = HolydayType::class;

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?int $navigationSort = 7;
    
    public static function getNavigationGroup(): string
    {
        // dd(_('lang'));
         return \Lang::get('lang.title.settinggroups');
    }

    public static function getPluralModelLabel(): string
        {
           return \Lang::get('lang.title.holyday_types');
        }

    public static function getNavigationLabel(): string
        {
            return \Lang::get('lang.title.holyday_types');
        }    


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpanFull()
                    ->label(__('lang.title.holyday_name'))
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->label(__('lang.title.holyday_description'))
                    ->required(),
                Forms\Components\Checkbox::make('active')
                    ->label(__('lang.title.active'))
                    ->default(true),
                Forms\Components\Checkbox::make('is_paid')
                    ->label(__('lang.title.is_paid'))
                    ->default(false),
                Forms\Components\TextInput::make('hours')
                    ->label(__('lang.title.holyday_hours'))
                    ->numeric()
                    ->required(),
                Forms\Components\ColorPicker::make('color')
                    ->label(__('lang.title.color'))
                    ->default('#000000'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('lang.title.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('lang.title.description'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours')
                    ->label(__('lang.title.hours'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('lang.title.color'))
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
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHolydayTypes::route('/'),
            'create' => Pages\CreateHolydayType::route('/create'),
            'edit' => Pages\EditHolydayType::route('/{record}/edit'),
        ];
    }
}
