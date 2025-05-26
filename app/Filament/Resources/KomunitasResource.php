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

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Komunitas';

    protected static ?string $pluralLabel = 'Komunitas';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Konten';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pengelola Komunitas')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(20),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                    Forms\Components\FileUpload::make('image_logo')
                    ->maxSize(2048)
                    ->directory('komunitas')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_banner')
                    ->maxSize(2048)
                    ->directory('komunitas')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->maxSize(2048)
                    ->directory('komunitas')
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
                    Tables\Columns\ImageColumn::make('image_logo_url')
                    ->label('Logo')
                    ->rounded()
                    ->size(80),
                Tables\Columns\ImageColumn::make('image_banner_url')
                    ->label('Banner')
                    ->size(80),
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->size(80),
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
