<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WordResource\Pages;
use App\Models\Word;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class WordResource extends Resource
{
    protected static ?string $model = Word::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lemma')->required(),
                Forms\Components\Select::make('gender')
                    ->options([
                        'm' => 'der',
                        'f' => 'die',
                        'n' => 'das',
                    ])->required(),
                Forms\Components\TextInput::make('plural')->required(),
                Forms\Components\TextInput::make('level'),
                Forms\Components\Textarea::make('example_sentence')->required(),
                Forms\Components\Textarea::make('example_translation')->required(),
                Forms\Components\TagsInput::make('tags'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lemma')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('gender')->label('Artikel')->sortable(),
                Tables\Columns\TextColumn::make('plural'),
                Tables\Columns\TextColumn::make('level'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d.m.Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')->options([
                    'm' => 'der',
                    'f' => 'die',
                    'n' => 'das',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWords::route('/'),
            'create' => Pages\CreateWord::route('/create'),
            'edit' => Pages\EditWord::route('/{record}/edit'),
        ];
    }
}
