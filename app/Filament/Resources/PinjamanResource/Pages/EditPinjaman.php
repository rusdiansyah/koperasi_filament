<?php

namespace App\Filament\Resources\PinjamanResource\Pages;

use App\Filament\Resources\PinjamanResource;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
class EditPinjaman extends EditRecord
{
    protected static string $resource = PinjamanResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->hidden(fn(Pinjaman $record) => $record->angsurans()->count() > 1),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['total'] = str_replace(',','',$data['total']);
        $data['user_id'] = Filament::auth()->id();
        $record->update($data);
        $tenor = $data['tenor'];
        $angusran_bulanan = ($data['jumlah']/$tenor)+(($data['jumlah']*$data['bunga'])/100);

        Angsuran::where('pinjaman_id',$record->id)->delete();
        for($x=0;$x<$tenor;$x++)
        {
            $bln = $x+1;
            Angsuran::create([
                'pinjaman_id' => $record->id,
                'tanggal_jatuh_tempo' => date('Y-m-d', strtotime("+$bln months", strtotime($data['tanggal']))),
                'jumlah' => $angusran_bulanan,
            ]);
        }
        return $record;
    }
}
