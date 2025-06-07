<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action; 

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('print_invoice')
                ->label('Print Invoice')
                ->url(fn () => route('invoice.download', ['id' => $this->record->id]))
                ->icon('heroicon-o-printer')
                ->openUrlInNewTab(false) // sebenarnya ini opsional karena default tidak buka tab

        ];
    }
}
