<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisSimpananResource\Pages;
use App\Filament\Resources\JenisSimpananResource\RelationManagers;
use App\Models\JenisSimpanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisSimpananResource extends Resource
{
    protected static ?string $model = JenisSimpanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Simpan';
    protected static ?string $navigationLabel = 'Jenis';
    protected static ?int $navigationSort = 10;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_simpanan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nominal')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('bunga')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_simpanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bunga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJenisSimpanans::route('/'),
        ];
    }
}
