<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimpananResource\Pages;
use App\Filament\Resources\SimpananResource\RelationManagers;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\Simpanan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SimpananResource extends Resource
{
    protected static ?string $model = Simpanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Simpan';
    protected static ?string $navigationLabel = 'Simpanan';
    protected static ?int $navigationSort = 11;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Simpanan')
                    ->schema([
                        Select::make('anggota_id')
                            ->label('Anggota')
                            ->required()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $anggota = Anggota::where('id', $state)->first();
                                $set('nik', $anggota->nik ?? '');
                                $set('tempat_lahir', $anggota->tempat_lahir ?? '');
                                $set('provinsi', $anggota->provinsi->name ?? '');
                                $set('kabkota', $anggota->kabkota->name ?? '');
                                $set('kecamatan', $anggota->kecamatan->name ?? '');
                                $set('desa', $anggota->desa->name ?? '');
                                $set('alamat', $anggota->alamat ?? '');
                                $set('rt', $anggota->rt ?? '');
                                $set('rw', $anggota->rw ?? '');
                                $set('no_hp', $anggota->no_hp ?? '');
                                $set('email', $anggota->email ?? '');
                            })
                            ->afterStateHydrated(function ($state, Set $set) {
                                $anggota = Anggota::where('id', $state)->first();
                                $set('nik', $anggota->nik ?? '');
                                $set('tempat_lahir', $anggota->tempat_lahir ?? '');
                                $set('provinsi', $anggota->provinsi->name ?? '');
                                $set('kabkota', $anggota->kabkota->name ?? '');
                                $set('kecamatan', $anggota->kecamatan->name ?? '');
                                $set('desa', $anggota->desa->name ?? '');
                                $set('alamat', $anggota->alamat ?? '');
                                $set('rt', $anggota->rt ?? '');
                                $set('rw', $anggota->rw ?? '');
                                $set('no_hp', $anggota->no_hp ?? '');
                                $set('email', $anggota->email ?? '');
                            })
                            ->options(Anggota::where('is_active', true)->pluck('nama_lengkap', 'id'))
                            ->live()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                        Fieldset::make('Informasi Anggota')
                            // ->relationship('anggota')
                            ->schema([
                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->readOnly(),
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->readOnly(),
                                TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->readOnly(),
                                TextInput::make('kabkota')
                                    ->label('Kab/Kota')
                                    ->readOnly(),
                                TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->readOnly(),
                                TextInput::make('desa')
                                    ->label('Desa')
                                    ->readOnly(),
                                TextInput::make('alamat')
                                    ->label('Alamat')
                                    ->readOnly(),
                                TextInput::make('rt')
                                    ->label('RT')
                                    ->readOnly(),
                                TextInput::make('rw')
                                    ->label('RW')
                                    ->readOnly(),
                                TextInput::make('no_hp')
                                    ->label('No Handphone')
                                    ->readOnly(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->readOnly(),
                            ])->columns(3),
                        Select::make('jenis_simpanan_id')
                            ->label('Jenis Simpanan')
                            ->required()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $jenis = JenisSimpanan::where('id', $state)->first();
                                $set('jumlah', $jenis->nominal);
                            })
                            ->live()
                            ->preload()
                            ->options(
                                JenisSimpanan::where('is_active', true)
                                    ->pluck('nama_simpanan', 'id')
                            ),
                        Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('jumlah')
                            ->required()
                            ->numeric(),
                        Select::make('jenis_transaksi')
                            ->required()
                            ->options([
                                'setoran' => 'Setoran',
                                'penarikan' => 'Penarikan',
                            ])
                            ->default('setoran'),
                        Forms\Components\TextInput::make('keterangan')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')
                    ->rowIndex(isFromZero: false),
                Tables\Columns\TextColumn::make('anggota.nik')
                    ->label('NIK')
                    ->sortable(),
                Tables\Columns\TextColumn::make('anggota.nama_lengkap')
                    ->label('Nama Anggota')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis.nama_simpanan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_transaksi')
                ->badge()
                ->color(fn(Simpanan $records): string => $records->jenis_transaksi == 'setoran' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable()
                    ->alignRight()
                    ->summarize(Sum::make()->label('Total')),
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
                    ->label('Jenis Transaksi')
                    ->options([
                        'setoran' => 'Setoran',
                        'penarikan' => 'Penarikan',
                    ]),
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(3)
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
            'index' => Pages\ListSimpanans::route('/'),
            'create' => Pages\CreateSimpanan::route('/create'),
            'edit' => Pages\EditSimpanan::route('/{record}/edit'),
        ];
    }
}
