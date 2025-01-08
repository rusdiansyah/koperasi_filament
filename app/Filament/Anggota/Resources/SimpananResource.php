<?php

namespace App\Filament\Anggota\Resources;

use App\Filament\Anggota\Resources\SimpananResource\Pages;
use App\Filament\Anggota\Resources\SimpananResource\RelationManagers;
use App\Models\Simpanan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class SimpananResource extends Resource
{
    protected static ?string $model = Simpanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Simpanan';
    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make('tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('jenis.nama_simpanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_transaksi')
                ->badge()
                ->color(fn(Simpanan $records): string => $records->jenis_transaksi == 'setoran' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->alignRight(),
                // ->summarize(Sum::make()->label('Total')),
                TextColumn::make('saldo')
                    ->label('Saldo')
                    ->alignRight(),
                // ->formatStateUsing(function(Simpanan $records){
                //     $records->jenis_transaksi;
                // }),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengurus')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal')
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari_tanggal'),
                        DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
                SelectFilter::make('jenis_simpanan_id')
                    ->label('Jenis Simpanan')
                    ->relationship('jenis', 'nama_simpanan'),
                SelectFilter::make('jenis_transaksi')
                    ->options([
                        'setoran' => 'Setoran',
                        'penarikan' => 'Penarikan',
                    ]),
            ])
            ->filtersFormColumns(3)
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
            'index' => Pages\ListSimpanans::route('/'),
            // 'create' => Pages\CreateSimpanan::route('/create'),
            // 'edit' => Pages\EditSimpanan::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('anggota', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }
}
