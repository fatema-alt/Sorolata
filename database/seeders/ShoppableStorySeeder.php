<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;
use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShoppableStorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update Products with rich Bengali information
        $p1 = Product::where('slug', 'the-imperial-sonargaon-muslin-scarf')->first();
        if ($p1) {
            $p1->update([
                'title_bn' => 'সোনারগাঁ ইম্পেরিয়াল মসলিন স্কার্ফ ও ওড়না',
                'subtitle_bn' => '৩০০ কাউন্টের হাতে বোনা ফুটি কার্পাস তুলা • শীতলক্ষ্যা অববাহিকার মাস্টারপিস',
                'short_description_bn' => 'লুপ্ত ঢাকাই মসলিনের ঐতিহ্য ফিরিয়ে আনতে সোনারগাঁয়ের প্রবীণ কারিগরদের শতবর্ষের সাধনায় বোনা এক অনন্য সংগ্রহ।',
            ]);
        }

        $p2 = Product::where('slug', 'heirloom-tangail-mulberry-silk-jamdani')->first();
        if ($p2) {
            $p2->update([
                'title_bn' => 'ঐতিহ্যবাহী টাঙ্গাইল মালবেরি সিল্ক জামদানি শাড়ি',
                'subtitle_bn' => 'খাঁটি রেশম ও জরি সুতায় বোনা নকশিকাঁথা মোটিফ • জিআই সনদপ্রাপ্ত হস্তশিল্প',
                'short_description_bn' => 'টাঙ্গাইলের তাঁতপল্লীর বংশপরম্পরা কারিগরদের বোনা রাজকীয় জামদানি, যা যুগ যুগ ধরে আভিজাত্যের প্রতীক।',
            ]);
        }

        $p3 = Product::where('slug', 'hand-chiseled-royal-bogura-brass-kettle')->first();
        if ($p3) {
            $p3->update([
                'title_bn' => 'হাতে খোদাইকৃত বগুড়ার রাজকীয় পিতলের কেটলি',
                'subtitle_bn' => 'বগুড়ার প্রাচীন কাঁসারী পাড়ার পিটানো কাঁসা ও পিতলশিল্প',
                'short_description_bn' => 'করতোয়া অববাহিকার শতবর্ষের পুরোনো কর্মশালায় খাঁটি পিতল পিটিয়ে ও খোদাই করে তৈরি ঐতিহাসিক শৈল্পিক কেটলি।',
            ]);
        }

        // 2. Update Artisans with Bengali names
        $artisan1 = Artisan::first();
        if ($artisan1) {
            $artisan1->update([
                'full_name_bn' => 'মাস্টার কাজী ফারহান',
                'atelier_name_bn' => 'সোনারগাঁ হেরিটেজ লুম গিল্ড',
                'biography_bn' => 'শীতলক্ষ্যা নদীর তীরে চার পুরুষ ধরে ঐতিহ্যবাহী ঢাকাই মসলিন ও জামদানির বুনন কৌশলকে বাঁচিয়ে রেখেছেন তিনি।',
                'quote_bn' => 'প্রতিটি সুতোর ফাঁকে আমরা শুধু কাপড় বুনি না, বাংলার গৌরবময় ইতিহাসকে ধরে রাখি।',
            ]);
        }

        // 3. Create Flagship Bilingual Shoppable Story
        $storyContentEn = <<<MARKDOWN
The morning mist over the Shitalakshya River has long been the silent partner of Bengal’s finest weavers. Here in the historical capital of Sonargaon, spinning fine cotton requires exact atmospheric humidity—a secret preserved across centuries by artisanal guilds.

For generations, travelers from Constantinople to Paris marvelled at fabric so light it was christened *Baft-Hawa*—woven air. Each scarf represents more than three months of patient craftsmanship on traditional pit looms.

[product:the-imperial-sonargaon-muslin-scarf]

Moving further northwest toward the fertile banks of the Jamuna, the rhythm of Tangail’s handloom villages shifts to Mulberry silk. Unlike machine-stamped imitations, authentic Jamdani motifs are woven by hand using bamboo needles directly into the warp and weft.

The floral jaal and paisley borders are never drawn on paper first; they are preserved in the living memory of the weaver, passed down from father to son like an unwritten folk symphony.

[product:heirloom-tangail-mulberry-silk-jamdani]

When you drape one of these heirloom creations, you become the custodian of a living heritage—ensuring that the fires of Bengal's traditional looms burn bright for generations yet unborn.
MARKDOWN;

        $storyContentBn = <<<MARKDOWN
শীতলক্ষ্যা নদীর সকালের কুয়াশা আর মৃদু বাষ্পীয় আর্দ্রতা শতাব্দীর পর শতাব্দী ধরে বাংলার সূক্ষ্ম তাঁতশিল্পীদের প্রধান সহায়। মেঘনা ও শীতলক্ষ্যা অববাহিকার সোনারগাঁয়ে মিহি সুতা কাটার জন্য প্রয়োজন হয় নিখুঁত প্রাকৃতিক আর্দ্রতা—যা বাংলার তাঁতিদের বংশপরম্পরার এক গোপন বিজ্ঞান।

রোমান সম্রাটদের দরবার থেকে প্যারিসের ফ্যাশন হাউস—সারা বিশ্ব ঢাকাই মসলিনকে একবাক্যে আখ্যা দিয়েছিল *'বাফত-হাওয়া'* বা বোনা বাতাস। ৩০০ কাউন্টের ফুটি কার্পাস তুলা থেকে একেকটি মসলিন ওড়না তৈরি করতে নিবেদিতপ্রাণ কারিগরের সময় লাগে প্রায় তিন থেকে চার মাস।

[product:the-imperial-sonargaon-muslin-scarf]

উত্তর-পশ্চিমের যমুনা নদীর অববাহিকায় গেলে পাওয়া যায় টাঙ্গাইলের রেশমি জামদানির মোহনীয় ছন্দ। বর্তমানের সিন্থেটিক কিংবা পাওয়ারলুমের কৃত্রিমতার ভিড়ে খাঁটি মালবেরি রেশম ও জরি সুতায় বোনা জামদানির রূপ সম্পূর্ণ আলাদা। এখানে বাঁশের সূক্ষ্ম কাঠি দিয়ে প্রতিটি মোটিফ সরাসরি কাপড়ের বুননে ফুটিয়ে তোলা হয়।

তাঁতিরা কোনো কাগজে নকশা আঁকেন না; প্রতিটি কলকা, ময়ূরপঙ্খী এবং জলতরঙ্গ নকশা গেঁথে থাকে তাদের মনের ভেতর, যা প্রজন্ম থেকে প্রজন্মে ছড়িয়ে যায় এক অমর বাউল গানের মতো।

[product:heirloom-tangail-mulberry-silk-jamdani]

যখন আপনি একটি খাঁটি জামদানি বা মসলিন সংগ্রহ করেন, আপনি কেবল একটি পোশাক পরছেন না—আপনি বাঁচিয়ে রাখছেন একটি প্রাচীন ঐতিহ্য, একজন কারিগরের আত্মসম্মান এবং বাংলার চিরন্তন শিল্পধারাকে।
MARKDOWN;

        $story = Story::updateOrCreate(
            ['slug' => 'revival-of-sonargaon-muslin-and-jamdani'],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'The Revival of Ethereal Jamdani & Sonargaon Muslin',
                'title_bn' => 'সোনারগাঁয়ের লুপ্ত মসলিন ও জামদানির পুনর্জন্ম',
                'excerpt' => 'An archival voyage into the humid river basins of Bengal, exploring how master artisans are reviving 300-count handspun muslin and heirloom silk Jamdanis.',
                'excerpt_bn' => 'শীতলক্ষ্যা ও যমুনা নদীর অববাহিকায় এক কালজয়ী অভিযাত্রা—কীভাবে বাংলার কারিগররা ফিরিয়ে আনছেন ৩০০ কাউন্টের বোনা বাতাস মসলিন ও ঐতিহ্যবাহী রেশম জামদানি।',
                'content' => $storyContentEn,
                'content_bn' => $storyContentBn,
                'category' => 'Heritage & Textile Craft',
                'category_bn' => 'বস্ত্রশিল্প ও ঐতিহ্য',
                'author_name' => 'Kazi Farhan (Master Curator)',
                'author_name_bn' => 'কাজী ফারহান (প্রধান কিউরেটর)',
                'hero_image' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1200',
                'reading_time' => 4,
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        // 4. Attach products to story via pivot table
        if ($p1 && $p2) {
            $story->products()->syncWithoutDetaching([
                $p1->id => [
                    'sort_order' => 1,
                    'context_note_en' => 'Featured in Muslin Weaving chapter',
                    'context_note_bn' => 'সোনারগাঁ মসলিন পরিচ্ছেদে উল্লেখিত',
                ],
                $p2->id => [
                    'sort_order' => 2,
                    'context_note_en' => 'Featured in Jamdani Loom chapter',
                    'context_note_bn' => 'টাঙ্গাইল জামদানি পরিচ্ছেদে উল্লেখিত',
                ],
            ]);
        }
    }
}
