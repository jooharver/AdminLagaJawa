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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\SelectColumn;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationLabel = 'Booking';

    protected static ?string $pluralLabel = 'Booking';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Administrasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'no_pemesanan')
                    ->label('Order ID')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('court_id')
                    ->relationship('court', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('booking_date')
                    ->required(),
                Forms\Components\Select::make('time_slots')
                    ->label('Slot Waktu')
                    ->multiple()
                    ->options([
                        '06:00:00' => '06:00',
                        '07:00:00' => '07:00',
                        '08:00:00' => '08:00',
                        '09:00:00' => '09:00',
                        '10:00:00' => '10:00',
                        '11:00:00' => '11:00',
                        '12:00:00' => '12:00',
                        '13:00:00' => '13:00',
                        '14:00:00' => '14:00',
                        '15:00:00' => '15:00',
                        '16:00:00' => '16:00',
                        '17:00:00' => '17:00',
                        '18:00:00' => '18:00',
                        '19:00:00' => '19:00',
                        '20:00:00' => '20:00',
                        '21:00:00' => '21:00',
                        '22:00:00' => '22:00',
                        '23:00:00' => '23:00',
                    ]),
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
            Tables\Columns\TextColumn::make('court_id')
            ->label('Court')
            ->sortable()
            ->getStateUsing(function ($record) {
                return \App\Models\Court::find($record->court_id)->name ?? 'N/A'; // Get the name of the court
            }),
            Tables\Columns\TextColumn::make('transaction.no_pemesanan')
            ->label('Order ID')
            ->sortable()
            ->searchable(),
            Tables\Columns\TextColumn::make('duration')
            ->label('Durasi')
            ->formatStateUsing(fn ($state) => $state . ' Jam'),
            Tables\Columns\TextColumn::make('time_slots')
            ->label('Slot Waktu')
            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
            ->sortable(),
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
                ->icon('heroicon-o-arrow-down-tray')
                ->openUrlInNewTab(),

                Tables\Actions\Action::make('Export PDF')
                    ->label('Export PDF')
                    ->url(route('export-bookinglog')) // Perbaiki nama route di sini
                    ->icon('heroicon-o-document-arrow-down')
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
