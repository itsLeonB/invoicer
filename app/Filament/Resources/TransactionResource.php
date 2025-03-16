<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Product;
use App\Models\Transaction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema(static::getDetailsFormSchema())
                    ->columns(3),

                Section::make('Transaction products')
                    ->headerActions([
                        Action::make('reset')
                            ->modalHeading('Are you sure?')
                            ->modalDescription('All existing products will be removed from the transaction.')
                            ->requiresConfirmation()
                            ->color('danger')
                            ->action(fn(Set $set) => $set('products', [])),
                    ])
                    ->schema([
                        static::getProductsRepeater(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Transaction date')
                    ->dateTime(format: 'l\, j F Y\, h:i:s A', timezone: config('app.timezone'))
                    ->sortable(),

                TextColumn::make('amount')
                    ->money('IDR')
                    ->label('Total amount')
                    ->sortable(),

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
            ->defaultSort('created_at', 'desc');
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

    public static function getDetailsFormSchema(): array
    {
        return [
            TextInput::make('amount')
                ->label('Total amount')
                ->numeric()
                ->prefix('Rp')
                ->readOnly()
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->afterStateHydrated(function (Get $get, Set $set) {
                    if ($get('amount') === null) {
                        static::updateTransactionAmount($get, $set);
                    }
                }),

            TextInput::make('created_at')
                ->label('Transaction date')
                ->disabled()
                ->dehydrated(false) // Exclude from form submission
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->timezone(config('app.timezone'))->format('l, j F Y, h:i:s A')),

            TextInput::make('updated_at')
                ->label('Updated at')
                ->disabled()
                ->dehydrated(false) // Exclude from form submission
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->timezone(config('app.timezone'))->format('l, j F Y, h:i:s A')),
        ];
    }

    public static function getProductsRepeater(): Repeater
    {
        return Repeater::make('products')
            ->relationship()
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Product')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 3,
                    ])
                    ->searchable()
                    ->createOptionForm(ProductResource::getSchema())
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->modalHeading('Create product')
                            ->modalSubmitActionLabel('Create product')
                            ->modalWidth('lg');
                    }),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        static::updateTotal($get, $set);
                    })
                    ->columnSpan([
                        'md' => 1,
                    ])
                    ->required(),

                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->live()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->step(1000)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        static::updateTotal($get, $set);
                    })
                    ->columnSpan([
                        'md' => 3,
                    ]),

                TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->live()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        static::updateTotal($get, $set);
                    })
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])
            ->extraItemActions([
                Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['product_id']);

                        if (! $product) {
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->defaultItems(1)
            ->hiddenLabel()
            ->live()
            ->afterStateUpdated(function (Get $get, Set $set) {
                static::updateTransactionAmount($get, $set);
            })
            ->columns([
                'md' => 10,
            ])
            ->required();
    }

    public static function updateTotal(Get $get, Set $set): void
    {
        $total = static::calculateTotal($get('quantity'), $get('price'));
        $total = number_format($total, 0, '.', ',');
        $set('total', $total);
    }

    public static function updateTransactionAmount(Get $get, Set $set): void
    {
        $selectedProducts = collect($get('products'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']) && !empty($item['price']));

        $subtotal = $selectedProducts->reduce(function ($subtotal, $product) {
            return $subtotal + static::calculateTotal($product['quantity'], $product['price']);
        }, 0);

        $subtotal = number_format($subtotal, 0, '.', ',');

        $set('amount', $subtotal);
    }

    public static function calculateTotal(?string $quantity, ?string $price): int
    {
        if ($quantity === null || $price === null) {
            return 0;
        }

        $price = (int) str_replace(',', '', $price);

        return $quantity * $price;
    }
}
