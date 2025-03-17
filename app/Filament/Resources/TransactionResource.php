<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Product;
use App\Models\Transaction;
use App\Utils\DatetimeUtils;
use App\Utils\MoneyUtils;
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
use Illuminate\Support\Facades\Log;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $rawJsMoneyFormatter = '$money($input)';

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
                    ->dateTime(format: config('app.datetime.format'), timezone: config('app.timezone'))
                    ->sortable(),

                TextColumn::make('amount')
                    ->money(config('app.currency'))
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
                ->prefix(config('app.currency'))
                ->readOnly()
                ->minValue(0)
                ->mask(RawJs::make(static::$rawJsMoneyFormatter))
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
                ->formatStateUsing(fn($state) => DatetimeUtils::defaultFormat($state)),

            TextInput::make('updated_at')
                ->label('Updated at')
                ->disabled()
                ->dehydrated(false) // Exclude from form submission
                ->formatStateUsing(fn($state) => DatetimeUtils::defaultFormat($state)),
        ];
    }

    public static function getProductsRepeater(): Repeater
    {
        return Repeater::make('products')
            ->relationship()
            ->schema(static::getTransactionProductSchema())
            ->extraItemActions([
                Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['product_id']);

                        if (!$product) {
                            Log::error('Product not found', ['product_id' => $itemData['product_id']]);
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->defaultItems(1)
            ->hiddenLabel()
            ->live()
            ->debounce()
            ->afterStateUpdated(function (Get $get, Set $set) {
                static::updateTransactionAmount($get, $set);
            })
            ->columns([
                'md' => 10,
            ])
            ->required();
    }

    public static function getTransactionProductSchema(): array
    {
        return [
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
                ->debounce()
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
                ->prefix(config('app.currency'))
                ->required()
                ->live()
                ->debounce()
                ->default(0)
                ->minValue(0)
                ->mask(RawJs::make(static::$rawJsMoneyFormatter))
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
                ->prefix(config('app.currency'))
                ->readOnly()
                ->live()
                ->debounce()
                ->minValue(0)
                ->mask(RawJs::make(static::$rawJsMoneyFormatter))
                ->stripCharacters(',')
                ->afterStateHydrated(function (Get $get, Set $set) {
                    static::updateTotal($get, $set);
                })
                ->columnSpan([
                    'md' => 3,
                ]),
        ];
    }

    public static function updateTotal(Get $get, Set $set): void
    {
        $total = static::calculateTotal($get('quantity'), $get('price'));
        $total = MoneyUtils::format($total);
        $set('total', $total);
    }

    public static function updateTransactionAmount(Get $get, Set $set): void
    {
        $products = $get('products') ?? [];

        $selectedProducts = collect($products)->filter(
            fn($item) => !empty($item['product_id']) && !empty($item['quantity']) && !empty($item['price'])
        );

        $subtotal = $selectedProducts->reduce(function ($subtotal, $product) {
            return $subtotal + static::calculateTotal($product['quantity'], $product['price']);
        }, 0);

        $subtotal = MoneyUtils::format($subtotal);

        $set('amount', $subtotal);
    }

    public static function calculateTotal(?string $quantity, ?string $price): int
    {
        if ($quantity === null || $price === null || !is_numeric($quantity)) {
            return 0;
        }

        $price = (int) str_replace(',', '', $price);
        $quantity = (int) $quantity;

        // Additional safety check
        if ($quantity < 0 || $price < 0) {
            return 0;
        }

        return $quantity * $price;
    }
}
