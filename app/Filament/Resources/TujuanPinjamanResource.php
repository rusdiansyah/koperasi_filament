<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TujuanPinjamanResource\Pages;
use App\Filament\Resources\TujuanPinjamanResource\RelationManagers;
use App\Models\TujuanPinjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TujuanPinjamanResource extends Resource
{
    protected static ?string $model = TujuanPinjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Pinjam';
    protected static ?string $navigationLabel = 'Tujuan';
    protected static ?int $navigationSort = 21;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_tujuan')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->inline(false)
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_tujuan')
                ->label('Nama')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                ->label('Aktif')
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
            'index' => Pages\ManageTujuanPinjamen::route('/'),
        ];
    }
}
