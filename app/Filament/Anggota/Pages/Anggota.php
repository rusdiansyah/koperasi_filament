<?php

namespace App\Filament\Anggota\Pages;

use App\Models\Anggota as ModelsAnggota;
use App\Models\Desa;
use App\Models\KabKota;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Anggota extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.anggota.pages.anggota';

    protected static ?string $title = 'Profil Anggota';
    use InteractsWithForms;

    public ?array $data = [];

    public $user_id = '';
    public ?string $nik = null;
    public ?string $nama_lengkap = null;
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $jenis_kelamin = '';
    public $provinsi_id = '';
    public $kabkota_id = '';
    public $kecamatan_id = '';
    public $desa_id = '';
    public $alamat = '';
    public $rt = '';
    public $rw = '';
    public $no_hp = '';
    public $email = '';
    public $tanggal_masuk = '';
    public $is_active = '';


    public function mount()
    {
        $this->user_id = auth()->user()->id;
        // dd($this->user_id);
        $this->getAnggota();
        $this->form->fill();
    }

    public function getAnggota()
    {
        $anggota = ModelsAnggota::where('user_id', $this->user_id)->first();
        if ($anggota) {
            $this->nik = $anggota->nik;
            $this->nama_lengkap = $anggota->nama_lengkap;
            $this->tempat_lahir = $anggota->tempat_lahir;
            $this->tanggal_lahir = $anggota->tanggal_lahir;
            $this->jenis_kelamin = $anggota->jenis_kelamin;
            $this->provinsi_id = $anggota->provinsi_id;
            $this->kabkota_id = $anggota->kabkota_id;
            $this->kecamatan_id = $anggota->kecamatan_id;
            $this->desa_id = $anggota->desa_id;
            $this->alamat = $anggota->alamat;
            $this->rt = $anggota->rt;
            $this->rw = $anggota->rw;
            $this->no_hp = $anggota->no_hp;
            $this->email = $anggota->email;
            $this->tanggal_masuk = $anggota->tanggal_masuk;
            $this->is_active = $anggota->is_active;
        } else {
            $this->nik = null;
            $this->nama_lengkap = null;
            $this->tempat_lahir = null;
            $this->tanggal_lahir = null;
            $this->jenis_kelamin = null;
            $this->provinsi_id = null;
            $this->kabkota_id = $anggota->kabkota_id;
            $this->kecamatan_id = $anggota->kecamatan_id;
            $this->desa_id = $anggota->desa_id;
            $this->alamat = $anggota->alamat;
            $this->rt = $anggota->rt;
            $this->rw = $anggota->rw;
            $this->no_hp = $anggota->no_hp;
            $this->email = $anggota->email;
            $this->tanggal_masuk = $anggota->tanggal_masuk;
            $this->is_active = $anggota->is_active;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Anggota')
                    ->schema([
                        Fieldset::make('Biodata')
                            ->schema([
                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->maxLength(16)
                                    ->default($this->nik)
                                    ->required(),
                                TextInput::make('nama_lengkap')
                                    ->label('Nama Lengkap')
                                    ->default($this->nama_lengkap)
                                    ->required(),
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->default($this->tempat_lahir)
                                    ->required(),
                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->default($this->tanggal_lahir)
                                    ->required(),
                                Select::make('jenis_kelamin')
                                    ->label('jenis_kelamin Lahir')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->default($this->jenis_kelamin)
                                    ->required(),
                            ]),
                        Fieldset::make('Domisili')
                            ->schema([
                                Select::make('provinsi_id')
                                    ->label('Provinsi')
                                    ->options(Provinsi::orderBy('name')->pluck('name', 'id'))
                                    ->default($this->provinsi_id)
                                    ->required()
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
                                    ->default($this->kabkota_id)
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
                                    ->default($this->kecamatan_id)
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
                                    ->default($this->desa_id)
                                    ->preload()
                                    ->searchable(),
                                TextInput::make('alamat')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->default($this->alamat)
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                TextInput::make('rt')
                                    ->label('RT')
                                    ->required()
                                    ->default($this->rt)
                                    ->maxLength(3),
                                TextInput::make('rw')
                                    ->label('RW')
                                    ->default($this->rw)
                                    ->required()
                                    ->maxLength(3),
                            ]),
                            Fieldset::make('Kontak')
                            ->schema([
                                TextInput::make('no_hp')
                                    ->label('No HP')
                                    ->default($this->no_hp)
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->default($this->email)
                                    ->email()
                                    ->required(),
                            ]),


                    ])->columns(2)
            ])
            ->statePath('data');
    }
}
