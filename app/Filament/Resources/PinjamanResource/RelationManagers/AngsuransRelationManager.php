<?php

namespace App\Filament\Resources\PinjamanResource\RelationManagers;

use App\Models\Angsuran;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class AngsuransRelationManager extends RelationManager
{
    protected static string $relationship = 'angsurans';
    protected static ?string $title = 'Angsuran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tanggal_jatuh_tempo')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tanggal_jatuh_tempo')
            ->columns([
                TextColumn::make('#')
                    ->rowIndex(isFromZero: false),
                Tables\Columns\TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d-m-Y'),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->numeric(),
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d-m-Y'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Angsuran')
                        ->icon('heroicon-m-pencil-square')
                        ->form([
                            DatePicker::make('tanggal')
                                ->label('tanggal')
                                ->default(now())
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each->update([
                                'tanggal_bayar' => $data['tanggal'],
                            ]);
                            Notification::make()
                                ->title('Bayar Angsuran berhasil di simpan.')
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('Batal Bayar')
                        ->icon('eos-cancel')
                        ->action(function (Collection $records, array $data) {
                            $records->each->update([
                                'tanggal_bayar' => null,
                            ]);
                            Notification::make()
                                ->title('Bayar Angsuran berhasil di simpan.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}
