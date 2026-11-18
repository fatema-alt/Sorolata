# Sorolata — Hostinger Cloud Web Hosting Deployment Guide

## ১. Hostinger hPanel-এ Git রিপোজিটরি কানেক্ট করা

1. **Hostinger hPanel**-এ প্রবেশ করুন এবং আপনার ডোমেইনটি নির্বাচন করুন।
2. বাম পাশের মেনু থেকে যান: **Advanced ➔ Git**
3. সেখানে নিম্নলিখিত তথ্য দিন:
   * **Repository:** `https://github.com/fatema-alt/Sorolata.git`
   * **Branch:** `main`
   * **Install Directory:** `public_html` (বা খালি রেখে ডিফল্ট)
4. যদি রিপোজিটরি Private হয়, Hostinger একটি **SSH Deploy Key** প্রদান করবে:
   * সেই কী-টি কপি করে GitHub রিপোজিটরির **Settings ➔ Deploy keys ➔ Add deploy key**-এ পেস্ট করে **"Allow write access"** টিক দিয়ে সংরক্ষণ করুন।
5. এরপর Hostinger-এ **Create / Deploy** বাটনে ক্লিক করুন। সম্পূর্ণ কোড `public_html`-এ ক্লোন হয়ে যাবে।

---

## ২. Document Root পরিবর্তন (অত্যন্ত গুরুত্বপূর্ণ)

Laravel অ্যাপ্লিকেশন চলার জন্য ডোমেইনের রুট ফোল্ডার অবশ্যই `public` ফোল্ডার হতে হবে।

1. hPanel-এ যান: **Websites ➔ Dashboard ➔ General Settings**
2. **Change Document Root** অপশনে যান।
3. ডিরেক্টরি `public_html` এর পরিবর্তে লিখুন:
   ```
   public_html/public
   ```
4. **Save / Update** ক্লিক করুন।

---

## ৩. ডাটাবেজ তৈরি ও ইমপোর্ট

1. hPanel-এ যান: **Databases ➔ Management**
2. নতুন একটি MySQL ডাটাবেজ ও ব্যবহারকারী তৈরি করুন:
   * Database Name: `uXXXXXXX_sorolata`
   * Username: `uXXXXXXX_sorolata`
   * Password: একটি সুরক্ষিত পাসওয়ার্ড দিন।
3. তৈরি হওয়ার পর পাশে থাকা **Enter phpMyAdmin**-এ ক্লিক করুন।
4. **Import** ট্যাবে যান এবং প্রজেক্টের ভেতরে থাকা `database/sorolata_database_backup.sql` ফাইলটি সিলেক্ট করে **Import / Go** ক্লিক করুন। (এতে ৬৪ জেলা, জামদানি, কাঁসা-পিতল, কারিগর ও প্রোডাক্টসহ সব ডাটা লোড হয়ে যাবে)।

---

## ৪. `.env` কনফিগারেশন

1. hPanel-এ যান: **Files ➔ File Manager**
2. `public_html/` ফোল্ডারে প্রবেশ করুন।
3. `.env.hostinger.example` ফাইলটির নাম পরিবর্তন করে `.env` করুন (অথবা নতুন `.env` বানিয়ে কপি পেস্ট করুন)।
4. সেখানে ডাটাবেজের নাম, ইউজারনেম এবং পাসওয়ার্ড বসিয়ে সেভ করুন:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=uXXXXXXX_sorolata
   DB_USERNAME=uXXXXXXX_sorolata
   DB_PASSWORD=আপনার_ডাটাবেজ_পাসওয়ার্ড
   ```

---

## ৫. SSH কমান্ড (অথবা ডিপ্লয়মেন্ট ফিনিশিং)

Hostinger SSH অথবা Terminal-এ গিয়ে একবার রান করুন:
```bash
cd public_html
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

আপনার ওয়েবসাইট এখন লাইভ এবং ১০০% কার্যকর!
