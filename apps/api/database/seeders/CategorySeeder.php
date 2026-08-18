<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'suction-transfer-hoses', 'name_en' => 'Suction & Transfer Hoses', 'name_fa' => 'خرطومی‌های مکش و انتقال', 'blurb_en' => 'Spiral reinforced PVC hoses for oil, water and abrasive transfer.', 'blurb_fa' => 'خرطومی‌های پی‌وی‌سی مارپیچ‌دار برای انتقال نفت، آب و مواد ساینده.', 'image_path' => 'categories/suction-transfer-hoses.jpg'],
            ['slug' => 'ducting-hoses', 'name_en' => 'Ducting & Ventilation Hoses', 'name_fa' => 'خرطومی‌های هواکشی', 'blurb_en' => 'Light, highly flexible ducting for air, dust, powder and granules.', 'blurb_fa' => 'خرطومی‌های سبک و بسیار انعطاف‌پذیر برای هوا، گرد و غبار، پودر و گرانول.', 'image_path' => 'categories/ducting-hoses.jpg'],
            ['slug' => 'reinforced-pvc-hoses', 'name_en' => 'Reinforced PVC Hoses', 'name_fa' => 'شیلنگ‌های نخ‌دار پی‌وی‌سی', 'blurb_en' => 'Braided and steel-reinforced PVC lines for pressure and food service.', 'blurb_fa' => 'شیلنگ‌های نخ‌دار و فنردار پی‌وی‌سی برای فشار و مصارف غذایی.', 'image_path' => 'categories/reinforced-pvc-hoses.jpg'],
            ['slug' => 'rubber-hoses', 'name_en' => 'Rubber Hoses', 'name_fa' => 'شیلنگ‌های لاستیکی', 'blurb_en' => 'Single layer and textile reinforced rubber hoses for air, oil and fuel.', 'blurb_fa' => 'شیلنگ‌های لاستیکی تک‌لایه و نخ‌دار برای هوا، روغن و سوخت.', 'image_path' => 'categories/rubber-hoses.jpg'],
            ['slug' => 'hydraulic', 'name_en' => 'Hydraulic Hoses', 'name_fa' => 'شیلنگ‌های هیدرولیک', 'blurb_en' => 'One to six wire high pressure hoses, steam and composite lines.', 'blurb_fa' => 'شیلنگ‌های هیدرولیک یک تا شش لایه سیم، شیلنگ بخار و کامپوزیت.', 'image_path' => 'categories/hydraulic.jpg'],
            ['slug' => 'pneumatic', 'name_en' => 'Pneumatic Lines', 'name_fa' => 'شیلنگ‌های پنوماتیک', 'blurb_en' => 'Polyurethane tubing and fittings for automation and workshop air.', 'blurb_fa' => 'لوله‌های پلی‌اورتان و اتصالات برای اتوماسیون و هوای کارگاهی.', 'image_path' => 'categories/pneumatic.jpg'],
            ['slug' => 'silicone', 'name_en' => 'Silicone Products', 'name_fa' => 'محصولات سیلیکونی', 'blurb_en' => 'Silicone hose, cord, sheet and profile for heat and hygiene duty.', 'blurb_fa' => 'شیلنگ، مفتول، ورق و پروفیل سیلیکونی برای مصارف حرارتی و بهداشتی.', 'image_path' => 'categories/silicone.jpg'],
            ['slug' => 'fire-safety', 'name_en' => 'Fire Fighting Hoses', 'name_fa' => 'شیلنگ و اتصالات آتش‌نشانی', 'blurb_en' => 'Layflat fire and anti-acid hoses with couplings.', 'blurb_fa' => 'شیلنگ‌های برزنتی آتش‌نشانی و ضد اسید همراه با اتصالات.', 'image_path' => 'categories/fire-safety.jpg'],
            ['slug' => 'clamps-fittings', 'name_en' => 'Clamps & Fittings', 'name_fa' => 'بست‌ها و اتصالات', 'blurb_en' => 'Hydraulic, pneumatic and hose clamping hardware.', 'blurb_fa' => 'قطعات بست هیدرولیک، پنوماتیک و شیلنگ.', 'image_path' => 'categories/clamps-fittings.jpg'],
            ['slug' => 'specialty-hoses', 'name_en' => 'Specialty Hoses', 'name_fa' => 'شیلنگ‌های تخصصی', 'blurb_en' => 'Medical, food, torch, spraying and appliance-grade hoses.', 'blurb_fa' => 'شیلنگ‌های تخصصی پزشکی، غذایی، تورچ، سمپاشی و لوازم خانگی.', 'image_path' => 'categories/specialty-hoses.jpg'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['sort_order' => $index]
            );
        }
    }
}
