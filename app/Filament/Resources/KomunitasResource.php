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
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(20),
                Forms\Components\DatePicker::make('tanggal')
                    ->nullable(),
                Forms\Components\Select::make('jadwal')
                ->nullable()
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
                Forms\Components\Select::make('court')

                    ->options([
                        'Court 1' => 'Court 1',
                        'Court 2' => 'Court 2',
                        'Court 3' => 'Court 3',
                        'Court 4' => 'Court 4',
                        'Court 5' => 'Court 5',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->maxSize(1024) // maks 1MB
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        return md5($file->getClientOriginalName() . microtime()) . '.' . $file->getClientOriginalExtension();
                    })

                    ,
                Forms\Components\FileUpload::make('image_logo')
                    ->image()
                    ->maxSize(1024) // maks 1MB
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        return md5($file->getClientOriginalName() . microtime()) . '.' . $file->getClientOriginalExtension();
                    })

                    ,
                Forms\Components\FileUpload::make('image_banner')
                    ->image()
                    ->maxSize(1024) // maks 1MB
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        return md5($file->getClientOriginalName() . microtime()) . '.' . $file->getClientOriginalExtension();
                    })


                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Komunitas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwal')
                    ->label('Waktu')
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
