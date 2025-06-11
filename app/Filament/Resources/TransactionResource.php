<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $pluralLabel = 'Transaksi';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Administrasi';

    public static function getNavigationBadge(): ?string
    {
        return (string) Transaction::whereDate('created_at', Carbon::today())
            ->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Nama Pemesan')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('no_pemesanan')
                ->label('Order ID')
                ->readOnly()
                ->default(fn ($context) => $context === 'create' ? 'NTR-' . strtoupper(Str::random(9)) : null)
                ->maxLength(255)
                ->rule(fn ($record) => \Illuminate\Validation\Rule::unique('transactions', 'no_pemesanan')->ignore($record?->id_transaction, 'id_transaction')),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'transfer' => 'Transfrer',
                        'cod' => 'COD (Bayar di Tempat)',
                    ])->default('transfer'),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->hidden()
                    ->default(0),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'waiting' => 'Waiting',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->default('waiting'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_transaction')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pemesan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('no_pemesanan')
                    ->label('Order ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment Status')
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'waiting',
                        'danger' => 'failed'
                    ]),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->placeholder('Pilih Status:')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable()
                    ->default('pending'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('today')
                ->label('Transaksi Hari Ini')
                ->query(fn ($query) => $query->whereDate('created_at', Carbon::today())),
                Tables\Filters\Filter::make('yesterday')
                ->label('Transaksi Kemarin')
                ->query(fn ($query) => $query->whereDate('created_at', Carbon::yesterday())),
                Tables\Filters\Filter::make('last_week')
                ->label('Transaksi Seminggu Terakhir')
                ->query(fn ($query) => $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                ->label('Export Excel')
                ->url(route('export-booking'))
                ->icon('heroicon-o-arrow-down-tray')
                ->openUrlInNewTab(),

                Tables\Actions\Action::make('Export PDF')
                    ->label('Export PDF')
                    ->url(route('export-bookinglog'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
