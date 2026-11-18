<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (Bangla / বাংলা)
    |--------------------------------------------------------------------------
    */

    'accepted' => ':attribute গ্রহণ করা আবশ্যক।',
    'accepted_if' => ':other যখন :value তখন :attribute গ্রহণ করা আবশ্যক।',
    'active_url' => ':attribute একটি সঠিক ইউআরএল (URL) নয়।',
    'after' => ':attribute অবশ্যই :date এর পরবর্তী একটি তারিখ হতে হবে।',
    'after_or_equal' => ':attribute অবশ্যই :date এর সমান অথবা পরবর্তী একটি তারিখ হতে হবে।',
    'alpha' => ':attribute এ শুধুমাত্র বর্ণমালা থাকতে পারে।',
    'alpha_dash' => ':attribute এ শুধুমাত্র বর্ণমালা, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
    'alpha_num' => ':attribute এ শুধুমাত্র বর্ণমালা ও সংখ্যা থাকতে পারে।',
    'array' => ':attribute একটি অ্যারে হতে হবে।',
    'ascii' => ':attribute এ শুধুমাত্র একক-বাইট আলফানিউমেরিক অক্ষর ও প্রতীক থাকতে পারে।',
    'before' => ':attribute অবশ্যই :date এর পূর্ববর্তী একটি তারিখ হতে হবে।',
    'before_or_equal' => ':attribute অবশ্যই :date এর সমান অথবা পূর্ববর্তী একটি তারিখ হতে হবে।',
    'between' => [
        'array' => ':attribute এ অবশ্যই :min থেকে :max এর মধ্যে উপাদান থাকতে হবে।',
        'file' => ':attribute অবশ্যই :min থেকে :max কিলোবাইটের মধ্যে হতে হবে।',
        'numeric' => ':attribute অবশ্যই :min থেকে :max এর মধ্যে হতে হবে।',
        'string' => ':attribute অবশ্যই :min থেকে :max অক্ষরের মধ্যে হতে হবে।',
    ],
    'boolean' => ':attribute ক্ষেত্রটি অবশ্যই সত্য (true) বা মিথ্যা (false) হতে হবে।',
    'can' => ':attribute এ অননুমোদিত মান রয়েছে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মিলছে না।',
    'contains' => ':attribute এ প্রয়োজনীয় মান অনুপস্থিত।',
    'current_password' => 'পাসওয়ার্ডটি ভুল।',
    'date' => ':attribute একটি সঠিক তারিখ নয়।',
    'date_equals' => ':attribute অবশ্যই :date এর সমান একটি তারিখ হতে হবে।',
    'date_format' => ':attribute ফরম্যাটটি :format এর সাথে মিলছে না।',
    'decimal' => ':attribute এ অবশ্যই :decimal দশমিক স্থান থাকতে হবে।',
    'declined' => ':attribute অবশ্যই প্রত্যাখ্যান করতে হবে।',
    'declined_if' => ':other যখন :value তখন :attribute প্রত্যাখ্যান করতে হবে।',
    'different' => ':attribute এবং :other অবশ্যই ভিন্ন হতে হবে।',
    'digits' => ':attribute অবশ্যই :digits সংখ্যার হতে হবে।',
    'digits_between' => ':attribute অবশ্যই :min এবং :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions' => ':attribute এর ছবির সাইজ অনুপযুক্ত।',
    'distinct' => ':attribute এর মান পুনরাবৃত্ত হয়েছে।',
    'doesnt_end_with' => ':attribute নিচের কোনোটি দিয়ে শেষ হতে পারবে না: :values।',
    'doesnt_start_with' => ':attribute নিচের কোনোটি দিয়ে শুরু হতে পারবে না: :values।',
    'email' => ':attribute অবশ্যই একটি সঠিক ইমেইল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute অবশ্যই নিচের কোনো একটি দিয়ে শেষ হতে হবে: :values।',
    'enum' => 'নির্বাচিত :attribute সঠিক নয়।',
    'exists' => 'নির্বাচিত :attribute সঠিক নয়।',
    'extensions' => ':attribute এর ফাইল এক্সটেনশন অবশ্যই নিম্নোক্তগুলোর একটি হতে হবে: :values।',
    'file' => ':attribute একটি ফাইল হতে হবে।',
    'filled' => ':attribute পূরণ করা আবশ্যক।',
    'gt' => [
        'array' => ':attribute এ :value এর বেশি উপাদান থাকতে হবে।',
        'file' => ':attribute অবশ্যই :value কিলোবাইটের বেশি হতে হবে।',
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে বড় হতে হবে।',
        'string' => ':attribute অবশ্যই :value অক্ষরের বেশি হতে হবে।',
    ],
    'gte' => [
        'array' => ':attribute এ অবশ্যই :value বা তার বেশি উপাদান থাকতে হবে।',
        'file' => ':attribute অবশ্যই :value কিলোবাইট বা তার বেশি হতে হবে।',
        'numeric' => ':attribute অবশ্যই :value বা তার চেয়ে বড় হতে হবে।',
        'string' => ':attribute অবশ্যই :value বা তার বেশি অক্ষরের হতে হবে।',
    ],
    'hex_color' => ':attribute অবশ্যই একটি সঠিক হেক্সাডেসিমেল রঙ কোড হতে হবে।',
    'image' => ':attribute অবশ্যই একটি ছবি (image) হতে হবে।',
    'in' => 'নির্বাচিত :attribute সঠিক নয়।',
    'in_array' => ':attribute মানটি :other এ থাকতে হবে।',
    'integer' => ':attribute অবশ্যই পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute অবশ্যই একটি সঠিক আইপি (IP) ঠিকানা হতে হবে।',
    'ipv4' => ':attribute অবশ্যই একটি সঠিক IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute অবশ্যই একটি সঠিক IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute অবশ্যই একটি সঠিক JSON স্ট্রিং হতে হবে।',
    'list' => ':attribute একটি তালিকা হতে হবে।',
    'lowercase' => ':attribute অবশ্যই ছোট হাতের অক্ষরে হতে হবে।',
    'lt' => [
        'array' => ':attribute এ :value এর চেয়ে কম উপাদান থাকতে হবে।',
        'file' => ':attribute অবশ্যই :value কিলোবাইটের কম হতে হবে।',
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে ছোট হতে হবে।',
        'string' => ':attribute অবশ্যই :value অক্ষরের কম হতে হবে।',
    ],
    'lte' => [
        'array' => ':attribute এ :value বা তার চেয়ে কম উপাদান থাকতে হবে।',
        'file' => ':attribute অবশ্যই সর্বোচ্চ :value কিলোবাইট হতে হবে।',
        'numeric' => ':attribute অবশ্যই সর্বোচ্চ :value হতে হবে।',
        'string' => ':attribute অবশ্যই সর্বোচ্চ :value অক্ষরের হতে হবে।',
    ],
    'mac_address' => ':attribute একটি সঠিক ম্যাক অ্যাড্রেস হতে হবে।',
    'max' => [
        'array' => ':attribute এ :max এর বেশি উপাদান থাকতে পারবে না।',
        'file' => ':attribute সর্বোচ্চ :max কিলোবাইট হতে পারে।',
        'numeric' => ':attribute :max এর বেশি হতে পারবে না।',
        'string' => ':attribute সর্বোচ্চ :max অক্ষরের হতে পারে।',
    ],
    'max_digits' => ':attribute সর্বোচ্চ :max সংখ্যার হতে পারে।',
    'mimes' => ':attribute অবশ্যই :values ধরনের ফাইল হতে হবে।',
    'mimetypes' => ':attribute অবশ্যই :values ধরনের ফাইল হতে হবে।',
    'min' => [
        'array' => ':attribute এ কমপক্ষে :min টি উপাদান থাকতে হবে।',
        'file' => ':attribute কমপক্ষে :min কিলোবাইট হতে হবে।',
        'numeric' => ':attribute অবশ্যই কমপক্ষে :min হতে হবে।',
        'string' => ':attribute কমপক্ষে :min অক্ষরের হতে হবে।',
    ],
    'min_digits' => ':attribute এ কমপক্ষে :min সংখ্যা থাকতে হবে।',
    'missing' => ':attribute ক্ষেত্রটি খালি থাকতে হবে।',
    'missing_if' => ':other যখন :value তখন :attribute খালি থাকতে হবে।',
    'missing_unless' => ':other যতক্ষণ না :value তখন :attribute খালি থাকতে হবে।',
    'missing_with' => ':values উপস্থিত থাকলে :attribute খালি থাকতে হবে।',
    'missing_with_all' => ':values সবগুলো উপস্থিত থাকলে :attribute খালি থাকতে হবে।',
    'multiple_of' => ':attribute অবশ্যই :value এর গুণিতক হতে হবে।',
    'not_in' => 'নির্বাচিত :attribute সঠিক নয়।',
    'not_regex' => ':attribute এর ফরম্যাট সঠিক নয়।',
    'numeric' => ':attribute অবশ্যই একটি সংখ্যা হতে হবে।',
    'password' => [
        'letters' => ':attribute এ কমপক্ষে একটি বর্ণ থাকতে হবে।',
        'mixed' => ':attribute এ কমপক্ষে একটি বড় ও একটি ছোট হাতের বর্ণ থাকতে হবে।',
        'numbers' => ':attribute এ কমপক্ষে একটি সংখ্যা থাকতে হবে।',
        'symbols' => ':attribute এ কমপক্ষে একটি প্রতীক বা স্পেশাল ক্যারেক্টার থাকতে হবে।',
        'uncompromised' => 'প্রদত্ত :attribute একটি ডেটা লিক-এ ফাঁস হয়েছে। অনুগ্রহ করে ভিন্ন একটি পাসওয়ার্ড বেছে নিন।',
    ],
    'present' => ':attribute ক্ষেত্রটি উপস্থিত থাকা আবশ্যক।',
    'present_if' => ':other যখন :value তখন :attribute ক্ষেত্রটি উপস্থিত থাকতে হবে।',
    'present_unless' => ':other যতক্ষণ না :value তখন :attribute ক্ষেত্রটি উপস্থিত থাকতে হবে।',
    'present_with' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটিও উপস্থিত থাকতে হবে।',
    'present_with_all' => ':values সবগুলো উপস্থিত থাকলে :attribute ক্ষেত্রটিও উপস্থিত থাকতে হবে।',
    'prohibited' => ':attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_if' => ':other যখন :value তখন :attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_unless' => ':other যতক্ষণ না :values এর মধ্যে থাকে তখন :attribute নিষিদ্ধ।',
    'prohibits' => ':attribute উপস্থিতি :other কে নিষিদ্ধ করে।',
    'regex' => ':attribute এর ফরম্যাট সঠিক নয়।',
    'required' => ':attribute পূরণ করা আবশ্যক।',
    'required_array_keys' => ':attribute এ অবশ্যই নিচের এন্ট্রিগুলো থাকতে হবে: :values।',
    'required_if' => ':other যখন :value তখন :attribute পূরণ করা আবশ্যক।',
    'required_if_accepted' => ':other যখন গৃহীত তখন :attribute পূরণ করা আবশ্যক।',
    'required_if_declined' => ':other যখন প্রত্যাখ্যাত তখন :attribute পূরণ করা আবশ্যক।',
    'required_unless' => ':other যতক্ষণ না :values এর মধ্যে থাকে তখন :attribute পূরণ করা আবশ্যক।',
    'required_with' => ':values উপস্থিত থাকলে :attribute পূরণ করা আবশ্যক।',
    'required_with_all' => ':values সবগুলো উপস্থিত থাকলে :attribute পূরণ করা আবশ্যক।',
    'required_without' => ':values অনুপস্থিত থাকলে :attribute পূরণ করা আবশ্যক।',
    'required_without_all' => ':values কোনোটিই উপস্থিত না থাকলে :attribute পূরণ করা আবশ্যক।',
    'same' => ':attribute এবং :other অবশ্যই একই হতে হবে।',
    'size' => [
        'array' => ':attribute এ অবশ্যই :size টি উপাদান থাকতে হবে।',
        'file' => ':attribute অবশ্যই :size কিলোবাইট হতে হবে।',
        'numeric' => ':attribute অবশ্যই :size হতে হবে।',
        'string' => ':attribute অবশ্যই :size অক্ষরের হতে হবে।',
    ],
    'starts_with' => ':attribute অবশ্যই নিচের কোনো একটি দিয়ে শুরু হতে হবে: :values।',
    'string' => ':attribute অবশ্যই একটি টেক্সট স্ট্রিং হতে হবে।',
    'timezone' => ':attribute একটি সঠিক টাইমজোন হতে হবে।',
    'unique' => ':attribute ইতোমধ্যে ব্যবহৃত হয়েছে।',
    'uploaded' => ':attribute আপলোড করতে ব্যর্থ হয়েছে।',
    'uppercase' => ':attribute অবশ্যই বড় হাতের অক্ষরে হতে হবে।',
    'url' => ':attribute অবশ্যই একটি সঠিক ইউআরএল (URL) হতে হবে।',
    'ulid' => ':attribute একটি সঠিক ULID হতে হবে।',
    'uuid' => ':attribute একটি সঠিক UUID হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Here we define user-friendly Bangla labels for standard form inputs.
    |
    */

    'attributes' => [
        'name' => 'নাম',
        'email' => 'ইমেইল',
        'phone' => 'মোবাইল নম্বর',
        'address' => 'ঠিকানা',
        'district' => 'জেলা',
        'password' => 'পাসওয়ার্ড',
        'password_confirmation' => 'পাসওয়ার্ড নিশ্চিতকরণ',
        'customer_name' => 'গ্রাহকের নাম',
        'customer_email' => 'গ্রাহকের ইমেইল',
        'customer_phone' => 'গ্রাহকের ফোন নম্বর',
        'delivery_address' => 'ডেলিভারি ঠিকানা',
        'city_district' => 'জেলা / শহর',
        'payment_gateway' => 'পেমেন্ট মাধ্যম',
        'gift_note' => 'উপহার বার্তা',
        'coupon_code' => 'কুপন কোড',
        'product_id' => 'পণ্য',
        'quantity' => 'পরিমাণ',
        'transaction_id' => 'ট্রানজেকশন আইডি',
        'sender_number' => 'প্রেরকের মোবাইল নম্বর',
        'payment_method' => 'পেমেন্ট পদ্ধতি',
        'customer_notes' => 'গ্রাহকের মন্তব্য',
        'rating' => 'রেটিং',
        'author_name' => 'রিভিউকারীর নাম',
        'author_location' => 'ঠিকানা',
        'title' => 'শিরোনাম',
        'content' => 'বিবরণ',
        'reason' => 'কারণ',
        'notes' => 'নোট',
        'login' => 'ইমেইল অথবা ফোন নম্বর',
    ],

];
