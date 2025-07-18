<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

// Table actions
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TagsColumn;

// form actions
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use App\Filament\Forms\Components\AvailabilityGrid;



class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    protected static ?int $navigationSort = 3;
    
    public static function getPluralModelLabel(): string
        {
           return \Lang::get('lang.title.users');
        }

    public static function getEloquentQuery(): Builder
        {
            if(auth()->user()->isAdmin())
            return parent::getEloquentQuery();
        return parent::getEloquentQuery()->where('id', auth()->user()->getKey());
        }




        public static function getNavigationGroup(): string
        {
            // dd(_('lang'));
            return \Lang::get('lang.title.settinggroups');
        }

        public static function getNavigationLabel(): string
            {
                return \Lang::get('lang.title.users');
            }

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('lang.title.name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('lang.title.email'))
                    ->required(),
                DatePicker::make('start_at')
                        ->label(__('lang.title.start_at')),
                DatePicker::make('expired_at')
                        ->label(__('lang.title.expired_at')),
                TextInput::make('holyday')
                        ->label(__('lang.title.holyday')),
                Select::make('role')
                    ->columnSpanFull() // Sekcja na całą szerokość
                    ->options(__('lang.role.options')) // Assuming you have a translation for role options
                    ->label(__('lang.title.role'))
                    // ->default(0) // Default to user role
                    ->multiple()

                    // ->required()
                    ,
                AvailabilityGrid::make('hours_per_week')
                    ->columnSpanFull() // Sekcja na całą szerokość
                    ->label('Dostępność pracownika'),
                Checkbox::make('active')
                    ->columnSpanFull() // Sekcja na całą szerokość
                    ->label(__('lang.title.active'))
                    ->default(true),
                Forms\Components\TextInput::make('password')
                    ->label(__('lang.title.password'))
                    // ->required()
                    ->minLength(8)
                    ->maxLength(255)
                    ->password()
                    ->confirmed()
                    ->dehydrateStateUsing(fn ($state) => \Hash::make($state)),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label(__('lang.title.password_confirmation'))
                    // ->required()
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => \Hash::make($state))
                    ->same('password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('lang.title.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('lang.title.email'))
                    ->searchable()
                    ->sortable(),

                TagsColumn::make('expired_at')
                    ->label(\Lang::get('lang.title.info'))
                    ->getStateUsing(function ($record) {
                        $tags = [];
                        if ($record->expired_at != null && $record->expired_at < Carbon::now()) {
                            $tags[] = \Lang::get('lang.expired.expired');
                        }
                        return $tags;
                    })
                   ->color(fn (string $state): string => match($state) {
                        \Lang::get('lang.expired.expired') => 'danger',
                        'Premium' => 'warning', 
                        'Zablokowany' => 'danger',
                        default => 'gray',
                    }),
                CheckboxColumn::make('active')
                        ->label(__('lang.title.active'))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
