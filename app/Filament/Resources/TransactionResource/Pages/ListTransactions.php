<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\TransactionsExport;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;



class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Action::make('export')
            ->label('Sales Report')
            ->icon('heroicon-m-document-arrow-down')
            ->color('secondary')
            ->action(function () {
                return \Maatwebsite\Excel\Facades\Excel::download(
                    new \App\Exports\TransactionsExport,
                    'sales_report.xlsx'
                );
            }),
        CreateAction::make(), 
    
        ];
    }
}
