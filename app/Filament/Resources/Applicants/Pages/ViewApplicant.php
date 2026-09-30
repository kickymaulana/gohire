<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewApplicant extends ViewRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Print PDF')
                ->icon(Heroicon::OutlinedPrinter)
                ->url(fn (): string => route('applicants.print', $this->record))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
