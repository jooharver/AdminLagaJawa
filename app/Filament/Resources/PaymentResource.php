<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SelectColumn;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('payment_status')
                ->options([
                    'paid' => 'Paid',
                    'waiting' => 'Waiting',
                    'failed' => 'Failed',
                ])
                ->required(),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'qris' => 'QRIS',
                        'transfer' => 'Transfer',
                        'cod' => 'COD',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_payment')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('booking.no_pemesanan')
                    ->label('No. Pemesanan'),
                Tables\Columns\TextColumn::make('booking.booking_date')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.requester.name'),
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Method')
                    ->colors([
                        'success' => 'qris',
                        'warning' => 'transfer',
                        'danger' => 'cod',
                    ]),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                SelectColumn::make('payment_status')
                    ->label('Status')
                    ->placeholder('Pilih Status:')
                    ->options([
                        'waiting' => 'Waiting',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->sortable()
                    ->default('pending'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
