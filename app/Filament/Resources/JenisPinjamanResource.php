<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisPinjamanResource\Pages;
use App\Filament\Resources\JenisPinjamanResource\RelationManagers;
use App\Models\JenisPinjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisPinjamanResource extends Resource
{
    protected static ?string $model = JenisPinjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Pinjam';
    protected static ?string $navigationLabel = 'Jenis';
    protected static ?int $navigationSort = 20;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pinjaman')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Maksimal')
                    ->prefix('Rp.')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tenor')
                    ->label('Tenor Maksimal')
                    ->suffix('Bulan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('bunga')
                    ->required()
                    ->suffix('Bulan')
                    ->numeric(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pinjaman')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tenor')
                    ->numeric()
                    ->formatStateUsing(fn($state) => $state.' Bulan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bunga')
                    ->numeric()
                    ->formatStateUsing(fn($state) => $state.'%')
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
            'index' => Pages\ManageJenisPinjamen::route('/'),
        ];
    }
}
