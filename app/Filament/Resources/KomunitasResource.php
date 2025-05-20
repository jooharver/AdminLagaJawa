<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomunitasResource\Pages;
use App\Filament\Resources\KomunitasResource\RelationManagers;
use App\Models\Komunitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KomunitasResource extends Resource
{
    protected static ?string $model = Komunitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(20),
                Forms\Components\DatePicker::make('booking_date')
                    ->required(),
                Forms\Components\TextInput::make('time_slots')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('court')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required()
                    ->directory('community')
                    ->preserveFilenames()
                    ->maxSize(1024)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_logo')
                    ->image()
                    ->required()
                    ->directory('community')
                    ->preserveFilenames()
                    ->maxSize(1024)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_komunitas')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Komunitas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Jadwal')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_slots')
                    ->label('Waktu Booking')
                    ->sortable(),
                Tables\Columns\TextColumn::make('court')
                    ->label('Court')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListKomunitas::route('/'),
            'create' => Pages\CreateKomunitas::route('/create'),
            'edit' => Pages\EditKomunitas::route('/{record}/edit'),
        ];
    }
}
