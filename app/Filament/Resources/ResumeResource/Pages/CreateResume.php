<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Enums\QuestionType;
use App\Filament\Resources\ResumeResource;
use App\Models\Category;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;

class CreateResume extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = ResumeResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Basic info')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('linkedin')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->options(
                        Category::query()->get(['id', 'name'])
                            ->pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(
                        fn (Forms\Components\Select $component) => $component->getContainer()
                            ->getParentComponent()
                            ->getContainer()
                            ->getComponent('questionsStep')
                            ->getChildComponentContainer()
                            ->fill()
                    ),
            ]),
            Step::make('Questions')->schema(function (callable $get): array {
                if (! $get('category_id')) {
                    return [];
                }

                return Question::query()->where('category_id', $get('category_id'))->get()->map(function (Question $question) {
                    return match ($question->type) {
                        QuestionType::INPUT => TextInput::make("question.input.$question->id")
                            ->label($question->question),
                        QuestionType::RADIO => Forms\Components\Radio::make("question.radio.$question->id")
                            ->label($question->question)
                            ->options(['a', 'b', 'c'])
                            ->statePath("question.radio.$question->id")
                            ->default('a'),
                        QuestionType::MULTISELECT => Forms\Components\Select::make("question.multiple.$question->id")
                            ->label($question->question)
                            ->options(['one', 'two', 'three'])
                            ->multiple()->statePath("question.multiple.$question->id"),
                    };
                })->toArray();
            })->key('questionsStep'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);

        return parent::mutateFormDataBeforeCreate($data);
    }
}
