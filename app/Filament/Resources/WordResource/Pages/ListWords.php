<?php

namespace App\Filament\Resources\WordResource\Pages;

use App\Filament\Resources\WordResource;
use App\Models\Word;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ListWords extends ListRecords
{
    protected static string $resource = WordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make(),
            Tables\Actions\Action::make('importCsv')
                ->label('CSV İçe Aktar')
                ->form([
                    Forms\Components\FileUpload::make('csv')
                        ->acceptedFileTypes(['text/csv', 'text/plain'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $path = $data['csv'];
                    $file = Storage::path($path);
                    $rows = Collection::make(array_map('str_getcsv', file($file)));
                    $header = $rows->shift();
                    foreach ($rows as $row) {
                        $record = array_combine($header, $row);
                        if (! $record) {
                            continue;
                        }
                        Word::updateOrCreate([
                            'lemma' => $record['lemma'],
                        ], [
                            'gender' => $record['gender'],
                            'plural' => $record['plural'],
                            'example_sentence' => $record['example_sentence'],
                            'example_translation' => $record['example_translation'],
                            'level' => $record['level'] ?? null,
                            'tags' => json_decode($record['tags'] ?? '[]', true),
                        ]);
                    }

                    Notification::make()
                        ->title('İçe aktarma tamamlandı')
                        ->success()
                        ->send();
                }),
        ];
    }
}
