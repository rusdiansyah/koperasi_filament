<?php

namespace App\Filament\Anggota\Resources;

use App\Filament\Anggota\Resources\PinjamanResource\Pages;
use App\Filament\Anggota\Resources\PinjamanResource\RelationManagers;
use App\Models\Pinjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PinjamanResource extends Resource
{
    protected static ?string $model = Pinjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pinjaman';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')
                    ->rowIndex(isFromZero: false),
                TextColumn::make('jenis.nama_pinjaman')
                    ->searchable(),
                TextColumn::make('tujuan.nama_tujuan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric(),
                Tables\Columns\TextColumn::make('tenor')
                    ->label('Tenor')
                    ->formatStateUsing(fn($state) => $state . ' Bulan'),
                Tables\Columns\TextColumn::make('bunga')
                    ->label('Bunga')
                    ->formatStateUsing(fn($state) => $state . '%'),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->alignRight(),
                Tables\Columns\TextColumn::make('angsuran')
                    ->numeric()
                    ->state(function (Pinjaman $record) {
                        return $record->totalangsuran();
                    })
                    ->alignRight(),
                TextColumn::make('sisa_angsuran')
                    ->label('Sisa Angsuran')
                    ->numeric()
                    ->state(function (Pinjaman $record) {
                        return $record->total - $record->totalangsuran();
                    })
                    ->alignRight(),
                IconColumn::make('is_approve')
                    ->label('Acc Ketua')
                    ->boolean()
                    ->alignRight(),
                IconColumn::make('is_lunas')
                    ->label('Lunas')
                    ->boolean()
                    ->alignRight(),
            ])
            ->defaultSort('tanggal','desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPinjamen::route('/'),
            // 'create' => Pages\CreatePinjaman::route('/create'),
            // 'edit' => Pages\EditPinjaman::route('/{record}/edit'),
        ];
    }
}
