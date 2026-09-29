<?php

namespace App\Filament\Resources\Applicants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplicantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pelamar'),
                TextColumn::make('phone_whatsapp')
                    ->searchable()
                    ->label('No HP/WA'),
                TextColumn::make('education_level')
                    ->searchable()
                    ->sortable()
                    ->label('Tamatan'),
                TextColumn::make('position_applied')
                    ->searchable()
                    ->sortable()
                    ->label('Posisi'),
                TextColumn::make('expected_salary')
                    ->money('IDR')
                    ->sortable()
                    ->label('Gaji Harapan'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Masuk'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
