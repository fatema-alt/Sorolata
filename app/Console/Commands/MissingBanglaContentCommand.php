<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class MissingBanglaContentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:missing-bn {--model= : Filter audit by specific model} {--limit=20 : Max records per model in detail view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit database records lacking Bangla (*_bn) translations';

    /**
     * Models and their bilingual field mappings to check:
     * modelClass => ['table' => ..., 'identifier' => ..., 'fields' => [...]]
     */
    protected array $auditTargets = [
        \App\Models\Product::class => [
            'identifier' => 'title',
            'fields' => ['title_bn', 'subtitle_bn', 'short_description_bn', 'long_description_bn'],
        ],
        \App\Models\Category::class => [
            'identifier' => 'name',
            'fields' => ['name_bn', 'description_bn'],
        ],
        \App\Models\Collection::class => [
            'identifier' => 'title',
            'fields' => ['title_bn', 'name_bn', 'description_bn'],
        ],
        \App\Models\Artisan::class => [
            'identifier' => 'full_name',
            'fields' => ['full_name_bn', 'atelier_name_bn', 'biography_bn', 'quote_bn'],
        ],
        \App\Models\District::class => [
            'identifier' => 'name_en',
            'fields' => ['name_bn', 'gi_specialty_bn', 'river_basin_bn', 'historical_narrative_bn'],
        ],
        \App\Models\Story::class => [
            'identifier' => 'title',
            'fields' => ['title_bn', 'excerpt_bn', 'content_bn'],
        ],
        \App\Models\Page::class => [
            'identifier' => 'title',
            'fields' => ['title_bn'],
        ],
        \App\Models\MenuItem::class => [
            'identifier' => 'title',
            'fields' => ['title_bn', 'label_bn'],
        ],
        \App\Models\ProductFaq::class => [
            'identifier' => 'question',
            'fields' => ['question_bn', 'answer_bn'],
        ],
        \App\Models\ProductSpecification::class => [
            'identifier' => 'attribute_name',
            'fields' => ['group_name_bn', 'attribute_name_bn', 'attribute_value_bn'],
        ],
        \App\Models\Announcement::class => [
            'identifier' => 'text',
            'fields' => ['text_bn'],
        ],
        \App\Models\MarketingPopup::class => [
            'identifier' => 'title',
            'fields' => ['title_bn', 'content_bn'],
        ],
        \App\Models\OrderTimeline::class => [
            'identifier' => 'note',
            'fields' => ['note_bn'],
        ],
    ];

    public function handle(): int
    {
        $filterModel = $this->option('model');
        $limit = (int) $this->option('limit');

        $this->info('╔══════════════════════════════════════════════════════════╗');
        $this->info('║       Sorolata Database Bilingual Content Audit          ║');
        $this->info('║             Tracking Records Missing *_bn                ║');
        $this->info('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();

        $summaryRows = [];
        $detailRows = [];
        $totalMissingRecords = 0;

        foreach ($this->auditTargets as $modelClass => $config) {
            $shortName = class_basename($modelClass);

            if ($filterModel && !str_contains(strtolower($shortName), strtolower($filterModel))) {
                continue;
            }

            if (!class_exists($modelClass)) {
                continue;
            }

            /** @var \Illuminate\Database\Eloquent\Model $modelInstance */
            $modelInstance = new $modelClass();
            $table = $modelInstance->getTable();

            if (!Schema::hasTable($table)) {
                continue;
            }

            // Filter fields to those that actually exist in the table
            $existingFields = array_filter($config['fields'], fn ($f) => Schema::hasColumn($table, $f));
            if (empty($existingFields)) {
                continue;
            }

            $totalRecords = $modelClass::count();
            if ($totalRecords === 0) {
                $summaryRows[] = [$shortName, 0, 0, 'No records in database'];
                continue;
            }

            // Query records where ANY of the existing *_bn fields is null or empty
            $query = $modelClass::query()->where(function ($q) use ($existingFields) {
                foreach ($existingFields as $idx => $field) {
                    if ($idx === 0) {
                        $q->whereNull($field)->orWhere($field, '');
                    } else {
                        $q->orWhereNull($field)->orWhere($field, '');
                    }
                }
            });

            $missingCount = $query->count();
            $totalMissingRecords += $missingCount;

            $summaryRows[] = [
                $shortName,
                $totalRecords,
                $missingCount,
                implode(', ', $existingFields),
            ];

            // Fetch sample details for table
            if ($missingCount > 0 && $limit > 0) {
                $identifierCol = $config['identifier'];
                $hasIdentifier = Schema::hasColumn($table, $identifierCol);

                $records = $query->take($limit)->get();
                foreach ($records as $record) {
                    $missingInRecord = [];
                    foreach ($existingFields as $f) {
                        if (empty($record->{$f})) {
                            $missingInRecord[] = $f;
                        }
                    }

                    $identValue = $hasIdentifier ? ($record->{$identifierCol} ?? '#'.$record->getKey()) : '#'.$record->getKey();
                    $truncated = mb_strimwidth((string) $identValue, 0, 35, '...');

                    $detailRows[] = [
                        $shortName,
                        $record->getKey(),
                        $truncated,
                        implode(', ', $missingInRecord),
                    ];
                }
            }
        }

        $this->table(
            ['Model', 'Total Records', 'Missing *_bn', 'Audited Columns'],
            $summaryRows
        );

        if (!empty($detailRows)) {
            $this->newLine();
            $this->info("Detailed Breakdown (First up to {$limit} per model):");
            $this->table(
                ['Model', 'ID', 'English Content / Title', 'Missing Bangla Fields'],
                $detailRows
            );
        }

        $this->newLine();
        if ($totalMissingRecords === 0) {
            $this->info('✓ Outstanding! All audited database records have full Bangla (*_bn) translations.');
        } else {
            $this->warn("! Total records requiring Bangla content translation: {$totalMissingRecords}");
            $this->line('  Translations can be populated in admin panel or through localized seeding.');
        }

        return Command::SUCCESS;
    }
}
