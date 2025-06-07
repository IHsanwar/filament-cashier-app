<?php

namespace App\Filament\Resources;

use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\afterStateUpdated;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\TransactionItem;
use Filament\Forms\Get;
use Filament\Forms\Set;
class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

 
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('date')
                ->label('Transaction Date')
                ->default(now())
                ->required(),

            TextInput::make('total')
                ->label('Total Amount')
                ->numeric()
                ->minValue(1)
                ->default(0)
                ->required()
                ->disabled()
                ->dehydrated(true),

            Repeater::make('items')
                ->relationship('items')
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    if (isset($data['product_id']) && !isset($data['price'])) {
                        $product = Product::find($data['product_id']);
                        if ($product) {
                            $data['price'] = $product->price;
                            $data['subtotal'] = ($data['quantity'] ?? 1) * $product->price;
                        }
                    }
                    return $data;
                })
                ->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->options(fn () => Product::orderBy('name')->pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                            if (!$state) return;

                            $product = Product::find($state);
                            if ($product) {
                                $set('price', $product->price);
                                $quantity = (float) $get('quantity') ?: 1;
                                $subtotal = $quantity * $product->price;
                                $set('subtotal', $subtotal);

                                // Update total
                                $items = $get('../../items');
                                $total = collect($items)->sum(fn ($item) => $item['subtotal'] ?? 0);
                                $set('../../total', $total);
                            }
                        }),

                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $quantity = (float) $get('quantity');
                            $price = (float) $get('price');
                            $subtotal = $quantity * $price;
                            $set('subtotal', $subtotal);

                            // Update total
                            $items = $get('../../items');
                            $total = collect($items)->sum(fn ($item) => $item['subtotal'] ?? 0);
                            $set('../../total', $total);
                        }),

                    TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->dehydrated(true)
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $quantity = (float) $get('quantity');
                            $price = (float) $get('price');
                            $subtotal = $quantity * $price;
                            $set('subtotal', $subtotal);

                            // Update total
                            $items = $get('../../items');
                            $total = collect($items)->sum(fn ($item) => $item['subtotal'] ?? 0);
                            $set('../../total', $total);
                        }),

                    TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->required()
                        ->dehydrated(true),
                ])
                ->columns(2)
                ->createItemButtonLabel('Add Item')
                ->live(), // biar setiap item update langsung trigger reactive logic
        ])
        ->live(onBlur: true);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                    //->sortable(),
                    
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                    //->sortable(),
                
                Tables\Columns\TextColumn::make('total')
                    ->money('IDR', true),
                    //->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items Count')
                    ->counts('items'),
                    //->sortable(),
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
            ])
            ->defaultSort('id', 'desc'); 
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    
}