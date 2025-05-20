<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('requester_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('court_id')
                    ->relationship('court', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('no_pemesanan')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('booking_date')
                    ->required(),
                Forms\Components\TextInput::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->numeric(),
                Forms\Components\Select::make('approval_status')
                    ->options([
                        'checked_in' => 'Checked In',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('payment_id')
                    ->numeric(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('id_booking')
            ->label('ID'),
            Tables\Columns\TextColumn::make('booking_date')
            ->label('Tanggal')
            ->date('d-m-Y')
            ->sortable(),
            Tables\Columns\TextColumn::make('requester_id')
            ->label('Nama Pemesan')
            ->sortable()
            ->getStateUsing(function ($record) {
                return \App\Models\User::find($record->requester_id)->name ?? 'N/A'; // Get the name of the requester
            }),
            Tables\Columns\TextColumn::make('court_id')
            ->label('Court')
            ->sortable()
            ->getStateUsing(function ($record) {
                return \App\Models\Court::find($record->court_id)->name ?? 'N/A'; // Get the name of the court
            }),
            Tables\Columns\TextColumn::make('no_pemesanan')
                ->searchable(),
            Tables\Columns\TextColumn::make('start_time')
            ->time('H:i'),
            Tables\Columns\TextColumn::make('end_time')
            ->time('H:i'),
            // Tables\Columns\TextColumn::make('approver')
            // ->label('Approver')
            // ->sortable()
            // ->getStateUsing(function ($record) {
            //     return \App\Models\User::find($record->approver)->name ?? 'N/A'; // Get the name of the requester
            // }),
            Tables\Columns\BadgeColumn::make('approval_status')
            ->label('Status')
            ->colors([
                'success' => 'checked_in',
                'warning' => 'pending',
                'danger' => 'cancelled'
            ]),

            Tables\Columns\BadgeColumn::make('payment.payment_status')
            ->label('Payment Status')
            ->colors([
                'success' => 'paid',
                'warning' => 'waiting',
                'danger' => 'failed'
            ]),
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
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                ->label('Export Excel')
                ->url(route('export-booking'))
                ->icon('heroicon-o-arrow-up-tray')
                ->openUrlInNewTab(),

                Tables\Actions\Action::make('Export PDF')
                    ->label('Export PDF')
                    ->url(route('export-bookinglog')) // Perbaiki nama route di sini
                    ->icon('heroicon-o-arrow-up-tray')
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
