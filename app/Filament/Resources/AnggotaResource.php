<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggotaResource\Pages;
use App\Filament\Resources\AnggotaResource\RelationManagers;
use App\Models\Anggota;
use App\Models\Desa;
use App\Models\KabKota;
use App\Models\Kecamatan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'tabler-user-plus';
    protected static ?string $navigationLabel = 'Anggota';
    protected static ?int $navigationSort = 3;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Anggota')
                    ->schema([
                        Wizard::make([
                            Wizard\Step::make('Biodata')
                                ->schema([
                                    TextInput::make('nik')
                                        ->label('NIK')
                                        ->unique(ignoreRecord:true)
                                        ->required()
                                        ->maxLength(16),
                                    Forms\Components\TextInput::make('nama_lengkap')
                                        ->label('Nama Lengkap')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('tempat_lahir')
                                        ->label('Tempat Lahir')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\DatePicker::make('tanggal_lahir')
                                        ->label('Tanggal Lahir')
                                        ->required(),
                                    Radio::make('jenis_kelamin')
                                        ->label('Jenis Kelamin')
                                        ->required()
                                        ->options([
                                            'L' => 'Laki-laki',
                                            'P' => 'Perempuan',
                                        ])
                                        ->inline(),
                                ]),
                            Wizard\Step::make('Alamat')
                                ->schema([
                                    Select::make('provinsi_id')
                                        ->label('Provinsi')
                                        ->required()
                                        ->relationship('provinsi', 'name')
                                        ->live()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('kabkota_id', '');
                                            $set('kecamatan_id', '');
                                            $set('desa_id', '');
                                        })
                                        ->preload()
                                        ->searchable(),
                                    Select::make('kabkota_id')
                                        ->label('Kabupaten/Kota')
                                        ->required()->options(
                                            fn(Get $get): Collection =>
                                            KabKota::query()
                                                ->where('province_id', $get('provinsi_id'))
                                                ->pluck('name', 'id')
                                        )
                                        ->required()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('kecamatan_id', '');
                                            $set('desa_id', '');
                                        })
                                        ->live()
                                        ->preload()
                                        ->searchable(),
                                    Select::make('kecamatan_id')
                                        ->required()
                                        ->label('Kecamatan')
                                        ->options(
                                            fn(Get $get): Collection =>
                                            Kecamatan::query()
                                                ->where('regency_id', $get('kabkota_id'))
                                                ->pluck('name', 'id')
                                        )
                                        ->required()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('desa_id', '');
                                        })
                                        ->live()
                                        ->preload()
                                        ->searchable(),
                                    Select::make('desa_id')
                                        ->label('Desa')
                                        ->required()
                                        ->options(
                                            fn(Get $get): Collection =>
                                            Desa::query()
                                                ->where('district_id', $get('kecamatan_id'))
                                                ->pluck('name', 'id')
                                        )
                                        ->live()
                                        ->required()
                                        ->preload()
                                        ->searchable(),
                                    Forms\Components\TextInput::make('alamat')
                                        ->label('Alamat Lengkap')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('rt')
                                        ->label('RT')
                                        ->required()
                                        ->maxLength(3),
                                    Forms\Components\TextInput::make('rw')
                                        ->label('RW')
                                        ->required()
                                        ->maxLength(3),
                                    Forms\Components\TextInput::make('no_hp')
                                        ->label('Nomor HP')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->label('Email')
                                        ->required(),
                                ]),
                            Wizard\Step::make('Status')
                                ->schema([
                                    Forms\Components\DatePicker::make('tanggal_masuk')
                                        ->label('Tanggal Masuk')
                                        ->required(),
                                    Toggle::make('is_active')
                                        ->label('Aktif')
                                        ->default(true)
                                        ->inline(false),
                                ]),
                        ])->columns(2)
                            ->skippable(),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Langkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->state(fn($record) => $record->jenis_kelamin=='L' ? 'Laki-laki' : 'Perempuan' ),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('Nomor HP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable()
                    ->boolean(),
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
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}
