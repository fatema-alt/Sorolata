<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Collections: title_bn, name_bn, description_bn
        if (Schema::hasTable('collections')) {
            Schema::table('collections', function (Blueprint $table): void {
                if (!Schema::hasColumn('collections', 'title_bn')) {
                    $table->string('title_bn')->nullable();
                }
                if (!Schema::hasColumn('collections', 'name_bn')) {
                    $table->string('name_bn')->nullable();
                }
                if (!Schema::hasColumn('collections', 'description_bn')) {
                    $table->text('description_bn')->nullable();
                }
            });
        }

        // 2. Districts: gi_specialty_bn, river_basin_bn, historical_narrative_bn
        if (Schema::hasTable('districts')) {
            Schema::table('districts', function (Blueprint $table): void {
                if (!Schema::hasColumn('districts', 'gi_specialty_bn')) {
                    $table->string('gi_specialty_bn')->nullable();
                }
                if (!Schema::hasColumn('districts', 'river_basin_bn')) {
                    $table->string('river_basin_bn')->nullable();
                }
                if (!Schema::hasColumn('districts', 'historical_narrative_bn')) {
                    $table->text('historical_narrative_bn')->nullable();
                }
            });
        }

        // 3. Pages: title_bn
        if (Schema::hasTable('pages')) {
            Schema::table('pages', function (Blueprint $table): void {
                if (!Schema::hasColumn('pages', 'title_bn')) {
                    $table->string('title_bn')->nullable();
                }
            });
        }

        // 4. Menu Items: title_bn, label_bn
        if (Schema::hasTable('menu_items')) {
            Schema::table('menu_items', function (Blueprint $table): void {
                if (!Schema::hasColumn('menu_items', 'title_bn')) {
                    $table->string('title_bn')->nullable();
                }
                if (!Schema::hasColumn('menu_items', 'label_bn')) {
                    $table->string('label_bn')->nullable();
                }
            });

            // Backfill title_bn from bn_title if present
            if (Schema::hasColumn('menu_items', 'bn_title')) {
                DB::table('menu_items')
                    ->whereNotNull('bn_title')
                    ->where(function ($q): void {
                        $q->whereNull('title_bn')->orWhere('title_bn', '');
                    })
                    ->update([
                        'title_bn' => DB::raw('bn_title'),
                    ]);
            }
        }

        // 5. Product FAQs: question_bn, answer_bn
        if (Schema::hasTable('product_faqs')) {
            Schema::table('product_faqs', function (Blueprint $table): void {
                if (!Schema::hasColumn('product_faqs', 'question_bn')) {
                    $table->text('question_bn')->nullable();
                }
                if (!Schema::hasColumn('product_faqs', 'answer_bn')) {
                    $table->text('answer_bn')->nullable();
                }
            });
        }

        // 6. Product Specifications: group_name_bn, attribute_name_bn, attribute_value_bn
        if (Schema::hasTable('product_specifications')) {
            Schema::table('product_specifications', function (Blueprint $table): void {
                if (!Schema::hasColumn('product_specifications', 'group_name_bn')) {
                    $table->string('group_name_bn')->nullable();
                }
                if (!Schema::hasColumn('product_specifications', 'attribute_name_bn')) {
                    $table->string('attribute_name_bn')->nullable();
                }
                if (!Schema::hasColumn('product_specifications', 'attribute_value_bn')) {
                    $table->string('attribute_value_bn')->nullable();
                }
            });
        }

        // 7. Announcements: text_bn
        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table): void {
                if (!Schema::hasColumn('announcements', 'text_bn')) {
                    $table->string('text_bn')->nullable();
                }
            });

            if (Schema::hasColumn('announcements', 'bn_text')) {
                DB::table('announcements')
                    ->whereNotNull('bn_text')
                    ->where(function ($q): void {
                        $q->whereNull('text_bn')->orWhere('text_bn', '');
                    })
                    ->update([
                        'text_bn' => DB::raw('bn_text'),
                    ]);
            }
        }

        // 8. Marketing Popups: title_bn, content_bn
        if (Schema::hasTable('marketing_popups')) {
            Schema::table('marketing_popups', function (Blueprint $table): void {
                if (!Schema::hasColumn('marketing_popups', 'title_bn')) {
                    $table->string('title_bn')->nullable();
                }
                if (!Schema::hasColumn('marketing_popups', 'content_bn')) {
                    $table->json('content_bn')->nullable();
                }
            });
        }

        // 9. Order Timelines: note_bn
        if (Schema::hasTable('order_timelines')) {
            Schema::table('order_timelines', function (Blueprint $table): void {
                if (!Schema::hasColumn('order_timelines', 'note_bn')) {
                    $table->text('note_bn')->nullable();
                }
            });
        }

        // 10. Orders: locale column & data migration from shipping_address['locale']
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if (!Schema::hasColumn('orders', 'locale')) {
                    $table->string('locale', 10)->nullable();
                }
            });

            try {
                $orders = DB::table('orders')->whereNotNull('shipping_address')->get();
                foreach ($orders as $order) {
                    if (empty($order->shipping_address)) {
                        continue;
                    }
                    $shipping = is_string($order->shipping_address)
                        ? json_decode($order->shipping_address, true)
                        : (array) $order->shipping_address;

                    if (is_array($shipping) && !empty($shipping['locale'])) {
                        DB::table('orders')->where('id', $order->id)->update([
                            'locale' => (string) $shipping['locale'],
                        ]);
                    }
                }
            } catch (\Throwable) {
                // Graceful pass if table is empty or driver differs
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'locale')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropColumn(['locale']);
            });
        }

        if (Schema::hasTable('order_timelines') && Schema::hasColumn('order_timelines', 'note_bn')) {
            Schema::table('order_timelines', function (Blueprint $table): void {
                $table->dropColumn(['note_bn']);
            });
        }

        if (Schema::hasTable('marketing_popups')) {
            Schema::table('marketing_popups', function (Blueprint $table): void {
                $cols = array_filter(['title_bn', 'content_bn'], fn ($c) => Schema::hasColumn('marketing_popups', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }

        if (Schema::hasTable('announcements') && Schema::hasColumn('announcements', 'text_bn')) {
            Schema::table('announcements', function (Blueprint $table): void {
                $table->dropColumn(['text_bn']);
            });
        }

        if (Schema::hasTable('product_specifications')) {
            Schema::table('product_specifications', function (Blueprint $table): void {
                $cols = array_filter(['group_name_bn', 'attribute_name_bn', 'attribute_value_bn'], fn ($c) => Schema::hasColumn('product_specifications', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }

        if (Schema::hasTable('product_faqs')) {
            Schema::table('product_faqs', function (Blueprint $table): void {
                $cols = array_filter(['question_bn', 'answer_bn'], fn ($c) => Schema::hasColumn('product_faqs', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }

        if (Schema::hasTable('menu_items')) {
            Schema::table('menu_items', function (Blueprint $table): void {
                $cols = array_filter(['title_bn', 'label_bn'], fn ($c) => Schema::hasColumn('menu_items', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }

        if (Schema::hasTable('pages') && Schema::hasColumn('pages', 'title_bn')) {
            Schema::table('pages', function (Blueprint $table): void {
                $table->dropColumn(['title_bn']);
            });
        }

        if (Schema::hasTable('districts')) {
            Schema::table('districts', function (Blueprint $table): void {
                $cols = array_filter(['gi_specialty_bn', 'river_basin_bn', 'historical_narrative_bn'], fn ($c) => Schema::hasColumn('districts', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }

        if (Schema::hasTable('collections')) {
            Schema::table('collections', function (Blueprint $table): void {
                $cols = array_filter(['title_bn', 'name_bn', 'description_bn'], fn ($c) => Schema::hasColumn('collections', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
