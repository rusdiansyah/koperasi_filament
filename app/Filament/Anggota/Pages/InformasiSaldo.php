<?php

namespace App\Filament\Anggota\Pages;

use App\Models\Anggota;
use App\Models\Simpanan;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class InformasiSaldo extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.anggota.pages.informasi-saldo';

    public ?array $data = [];

    public $user_id = '';
    public $saldo = 0;

    public function mount()
    {
        $this->user_id = auth()->user()->id;
        $this->saldo = $this->Saldo();
        $this->form->fill();
    }

    public function Saldo()
    {
        // Cari rekening berdasarkan user_id
        $rekening = Anggota::where('id', $this->user_id )->with('simpanan')->first();

        if (!$rekening) {
            return response()->json(['message' => 'Rekening tidak ditemukan'], 404);
        }

        // Hitung total setoran dan penarikan
        $totalSetoran = $rekening->simpanan
        ->where('jenis_transaksi', 'setoran')
        ->whereNotIn('jenis_simpanan_id',[1,2])
        ->sum('jumlah');
        $totalPenarikan = $rekening->simpanan->where('jenis_transaksi', 'penarikan')->sum('jumlah');

        // Saldo terkini
        $saldoTerkini = $rekening->saldo_awal + $totalSetoran - $totalPenarikan;
        return $saldoTerkini;

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Saldo Efektif')
                    ->schema([
                        TextInput::make('saldo')
                            ->label('Saldo Efektif')
                            ->prefix('Rp.')
                            ->default(number_format($this->saldo))
                            ->inlineLabel()
                            ->disabled(),
                    ])
            ])
            ->statePath('data');
    }
}
