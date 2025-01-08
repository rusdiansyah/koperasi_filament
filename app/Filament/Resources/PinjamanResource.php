<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PinjamanResource\Pages;
use App\Filament\Resources\PinjamanResource\RelationManagers;
use App\Filament\Resources\PinjamanResource\RelationManagers\AngsuransRelationManager;
use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\JenisPinjaman;
use App\Models\Pinjaman;
use App\Models\TujuanPinjaman;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PinjamanResource extends Resource
{
    protected static ?string $model = Pinjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Pinjam';
    protected static ?string $navigationLabel = 'Pinjaman';
    protected static ?int $navigationSort = 22;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pinjaman')
                    ->schema([
                        Select::make('anggota_id')
                            ->label('Anggota')
                            ->required()
                            // ->autofocus()
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
                        Select::make('jenis_pinjaman_id')
                            ->required()
                            ->options(JenisPinjaman::where('is_active', true)
                                ->pluck('nama_pinjaman', 'id'))
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $jenis = JenisPinjaman::where('id', $state)->first();
                                $set('jumlah', $jenis->nominal);
                                $set('tenor', $jenis->tenor);
                                $set('bunga', $jenis->bunga);
                                $total = $jenis->nominal + (($jenis->nominal * $jenis->bunga / 100) * $jenis->bunga);
                                $set('total', number_format($total));
                                // $set('total', $total);
                            })
                            ->preload()
                            ->searchable(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->default(now()),
                        Fieldset::make('Informasi Pinjaman')
                            ->schema([
                                Select::make('tujuan_pinjaman_id')
                                    ->required()
                                    ->options(TujuanPinjaman::where('is_active', true)
                                        ->pluck('nama_tujuan', 'id')),
                                Forms\Components\TextInput::make('jumlah')
                                    ->required()
                                    ->prefix('Rp.')
                                    ->readOnly()
                                    ->numeric(),
                                Forms\Components\TextInput::make('tenor')
                                    ->required()
                                    ->minValue(1)
                                    ->suffix(label: 'Bulan')
                                    ->readOnly()
                                    ->numeric(),
                                Forms\Components\TextInput::make('bunga')
                                    ->required()
                                    ->readOnly()
                                    ->suffix(label: '%')
                                    ->numeric()
                                    ->readOnly(),
                                Forms\Components\TextInput::make('total')
                                    ->required()
                                    ->readOnly()
                                    // ->numeric()
                                    // ->formatStateUsing(function($state){
                                    //     number_format($state);
                                    // })
                                    ->dehydrated()
                                    ->prefix('Rp.')
                                    ->readOnly(),
                                Toggle::make('is_approve')
                                    ->label('Di Setujui Ketua')
                                    ->inline(false)
                                    ->required(),
                            ])->columns(3),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')
                    ->rowIndex(isFromZero: false),
                Tables\Columns\TextColumn::make('anggota.nama_lengkap')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis.nama_pinjaman')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tujuan.nama_tujuan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')),
                Tables\Columns\TextColumn::make('tenor')
                    ->formatStateUsing(fn($state) => $state.' Bulan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bunga')
                    ->label('Bunga')
                    ->formatStateUsing(fn($state) => $state.'%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('angsuran')
                    ->numeric()
                    ->state(function(Pinjaman $record){
                        return $record->totalangsuran();
                    })
                    ->sortable(),
                ToggleColumn::make('is_approve')
                    ->label('Acc Ketua'),
                IconColumn::make('is_lunas')
                    ->label('Lunas')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user_id')
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
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn(Pinjaman $record) => $record->is_approve == true),
                    Tables\Actions\DeleteAction::make()
                    ->hidden(fn(Pinjaman $record) => $record->angsurans()->count() > 1),
                ])
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
            AngsuransRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPinjamen::route('/'),
            'create' => Pages\CreatePinjaman::route('/create'),
            'view' => Pages\ViewPinjaman::route('/{record}'),
            'edit' => Pages\EditPinjaman::route('/{record}/edit'),
        ];
    }
}
