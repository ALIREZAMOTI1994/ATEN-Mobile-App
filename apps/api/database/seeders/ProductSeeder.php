<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Industry;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Seeds the real ATEN product catalog (2025 printed catalog edition), ported from
 * the previously built Lovable prototype's src/data/catalog.ts. Titles, groupings
 * and specification tables are preserved as-is; product photography is not
 * committed to the repository and must be uploaded through the admin media
 * library into S3-compatible storage — the `images` below are the storage
 * paths that photography will be uploaded to.
 */
class ProductSeeder extends Seeder
{
    private const INDUSTRY_SLUGS = [
        'Food & Beverage' => 'food-beverage',
        'Pharmaceutical' => 'pharmaceutical',
        'Medical' => 'medical',
        'Manufacturing' => 'manufacturing',
        'Petrochemical' => 'petrochemical',
        'Agriculture' => 'agriculture',
        'Automation' => 'automation',
        'Ventilation' => 'ventilation',
        'Packaging' => 'packaging',
        'Automotive' => 'automotive',
    ];

    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'slug');
        $industryIds = Industry::pluck('id', 'slug');

        foreach ($this->products() as $data) {
            $images = $data['images'];
            $industries = $data['industries'];
            $categorySlug = $data['category'];
            unset($data['images'], $data['industries'], $data['category']);

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [...$data, 'category_id' => $categoryIds[$categorySlug]]
            );

            $product->images()->delete();
            foreach ($images as $i => $key) {
                $product->images()->create([
                    'path' => "products/{$data['slug']}/{$key}.jpg",
                    'alt_en' => $data['name_en'],
                    'alt_fa' => $data['name_fa'],
                    'sort_order' => $i,
                ]);
            }

            $industrySlugIds = collect($industries)
                ->map(fn ($name) => $industryIds[self::INDUSTRY_SLUGS[$name]])
                ->all();
            $product->industries()->sync($industrySlugIds);
        }
    }

    private function suctionSpec(int $bp): array
    {
        $sizes = ['1', '1-1/4', '1-1/2', '2', '2-1/2', '3', '4', '5', '6', '8'];
        $lengths = [40, 40, 40, 36, 30, 30, 30, 30, 25, 6];

        return [
            'columns' => ['Measurement (Inch)', 'Length (m)', 'B.P. (bar)'],
            'rows' => array_map(fn ($s, $l) => [$s, $l, $bp], $sizes, $lengths),
        ];
    }

    private function ductSpec(): array
    {
        $sizes = ['1', '1-1/4', '1-1/2', '2', '2-1/2', '3', '4', '5', '6', '8'];
        $lengths = [30, 30, 30, 30, 30, 30, 30, 25, 25, 15];

        return [
            'columns' => ['Measurement (Inch)', 'Length (m)'],
            'rows' => array_map(fn ($s, $l) => [$s, $l], $sizes, $lengths),
        ];
    }

    private function wireDuctSpec(): array
    {
        $sizes = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 23, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70];

        return [
            'columns' => ['Measurement (cm)', 'Length (m)'],
            'rows' => array_map(fn ($s) => [$s, 6], $sizes),
        ];
    }

    private function rubberSpec(): array
    {
        return [
            'columns' => ['Measurement (Inch)', 'Length (m)', 'W.P. (bar)', 'B.P. (bar)', 'Bending Radius (mm)'],
            'rows' => [
                ['1/4', 100, 20, 60, 50],
                ['5/16', 100, 20, 60, 50],
                ['3/8', 100, 20, 60, 50],
                ['1/2', 50, 20, 60, 50],
                ['5/8', 50, 20, 60, 50],
                ['3/4', 50, 20, 60, 50],
                ['1', 50, 20, 60, 50],
                ['1-1/4', 50, 20, 60, 50],
                ['1-1/2', 50, 20, 60, 50],
                ['2', 40, 20, 60, 50],
            ],
        ];
    }

    private function braidSpec(): array
    {
        return [
            'columns' => ['Measurement (Inch)', 'Length (m)', 'W.P. (bar)', 'B.P. (bar)'],
            'rows' => [
                ['1/4', 100, 7, 20],
                ['5/16', 100, 7, 20],
                ['3/8', 100, 7, 20],
                ['1/2', 50, 7, 20],
                ['5/8', 50, 5, 15],
                ['3/4', 50, 5, 15],
                ['1', 50, 5, 15],
                ['1-1/4', 50, 5, 15],
            ],
        ];
    }

    private function products(): array
    {
        return [
            [
                'slug' => 'blue-oil-transfer-suction-hose', 'name_en' => 'Blue Oil Transfer Suction Hose', 'name_fa' => 'خرطومی آبی نفتی',
                'category' => 'suction-transfer-hoses', 'material' => 'PVC + spiral reinforcement',
                'summary_en' => 'Oil, fuel and chemical transfer with high pressure and abrasion resistance.',
                'description_en' => 'Suitable for transferring oil, fuel and chemical media. High resistance against pressure, abrasion and heat, engineered for long service life in industrial environments.',
                'applications' => ['Oil transfer', 'Fuel transfer', 'Chemical transfer'],
                'industries' => ['Petrochemical', 'Manufacturing', 'Agriculture'],
                'images' => ['p04_0', 'p04_1'], 'specs' => $this->suctionSpec(10),
                'size_range' => '1" – 8"', 'length_range' => '6 – 40 m / coil', 'pressure' => 'B.P. 10 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-SUC-BLU', 'catalog_page' => 3,
            ],
            [
                'slug' => 'green-general-purpose-suction-hose', 'name_en' => 'Green General Purpose Suction Hose', 'name_fa' => 'خرطومی سبز',
                'category' => 'suction-transfer-hoses', 'material' => 'PVC + spiral reinforcement',
                'summary_en' => 'Flexible, abrasion resistant hose for agriculture, gardening and water transfer.',
                'description_en' => 'High flexibility with balanced weight and good abrasion resistance. Suitable for general use such as agriculture, gardening and water transfer.',
                'applications' => ['Water transfer', 'Irrigation', 'Gardening'],
                'industries' => ['Agriculture', 'Manufacturing'],
                'images' => ['p05_0'], 'specs' => $this->suctionSpec(7),
                'size_range' => '1" – 8"', 'length_range' => '6 – 40 m / coil', 'pressure' => 'B.P. 7 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-SUC-GRN', 'catalog_page' => 4,
            ],
            [
                'slug' => 'grey-air-cable-protection-hose', 'name_en' => 'Grey Air & Cable Protection Hose', 'name_fa' => 'خرطومی طوسی',
                'category' => 'suction-transfer-hoses', 'material' => 'PVC + spiral reinforcement',
                'summary_en' => 'Light hose for air and dust transfer and cable protection in workshops.',
                'description_en' => 'Good flexibility and strength against varied environmental conditions. This light hose is used for air and dust transfer as well as cable protection in industrial and workshop environments.',
                'applications' => ['Air transfer', 'Dust extraction', 'Cable protection'],
                'industries' => ['Manufacturing', 'Ventilation', 'Automation'],
                'images' => ['p06_0', 'p06_1'], 'specs' => $this->suctionSpec(7),
                'size_range' => '1" – 8"', 'length_range' => '6 – 40 m / coil', 'pressure' => 'B.P. 7 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SUC-GRY', 'catalog_page' => 5,
            ],
            [
                'slug' => 'polyurethane-ducting-hose', 'name_en' => 'Polyurethane Ducting Hose', 'name_fa' => 'خرطومی هواکشی استخوانی پلی اورتان',
                'category' => 'ducting-hoses', 'material' => 'Polyurethane',
                'summary_en' => 'Extreme abrasion resistance for powder, granule and sawdust conveying.',
                'description_en' => 'Outstanding resistance to abrasion, oil and chemicals. Smooth internal wall and very high flexibility for conveying abrasive media such as powder, granules and sawdust across different industries.',
                'applications' => ['Powder conveying', 'Granule conveying', 'Sawdust extraction'],
                'industries' => ['Manufacturing', 'Packaging', 'Ventilation'],
                'images' => ['p07_0'], 'specs' => $this->ductSpec(),
                'size_range' => '1" – 8"', 'length_range' => '15 – 30 m / coil', 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-DCT-PU', 'catalog_page' => 6,
            ],
            [
                'slug' => 'pvc-ducting-hose', 'name_en' => 'PVC Ducting Hose', 'name_fa' => 'خرطومی هواکشی استخوانی پی وی سی',
                'category' => 'ducting-hoses', 'material' => 'PVC',
                'summary_en' => 'Light, highly flexible ducting for air, fume and dust extraction.',
                'description_en' => 'A light and highly flexible structure that is ideal for transferring air, fumes and dust. Suitable pressure and abrasion resistance makes it an excellent choice for ventilation systems and workshop use.',
                'applications' => ['Ventilation', 'Fume extraction', 'Dust extraction'],
                'industries' => ['Ventilation', 'Manufacturing'],
                'images' => ['p08_0'], 'specs' => $this->ductSpec(),
                'size_range' => '1" – 8"', 'length_range' => '15 – 30 m / coil', 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-DCT-PVC', 'catalog_page' => 7,
            ],
            [
                'slug' => 'pvc-steel-wire-ducting-hose', 'name_en' => 'PVC Steel-Wire Ducting Hose', 'name_fa' => 'خرطومی هواکشی سیمی پی وی سی',
                'category' => 'ducting-hoses', 'material' => 'PVC + steel spring',
                'summary_en' => 'Steel spring reinforced ducting for air, liquids and light powders.',
                'description_en' => 'Light and very flexible structure reinforced with a steel spring for high strength. Used for suction and transfer of air, liquids and light powders in industrial workshops and ventilation systems.',
                'applications' => ['Air suction', 'Light powder conveying', 'Liquid suction'],
                'industries' => ['Ventilation', 'Manufacturing', 'Automation'],
                'images' => ['p09_0', 'p09_1'], 'specs' => $this->wireDuctSpec(),
                'size_range' => '3 – 70 cm', 'length_range' => '6 m / coil', 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-DCT-WPVC', 'catalog_page' => 8,
            ],
            [
                'slug' => 'polyurethane-steel-wire-ducting-hose', 'name_en' => 'Polyurethane Steel-Wire Ducting Hose', 'name_fa' => 'خرطومی هواکشی سیمی پلی اورتان',
                'category' => 'ducting-hoses', 'material' => 'Polyurethane + steel spring',
                'summary_en' => 'Abrasion resistant wire-reinforced ducting for powder and chemicals.',
                'description_en' => 'Light, highly flexible and abrasion resistant, reinforced with a steel spring for high strength. Used for suction and transfer of powder, granules and chemical media.',
                'applications' => ['Powder conveying', 'Granule conveying', 'Chemical extraction'],
                'industries' => ['Manufacturing', 'Petrochemical', 'Packaging'],
                'images' => ['p10_0', 'p10_1'], 'specs' => $this->wireDuctSpec(),
                'size_range' => '3 – 70 cm', 'length_range' => '6 m / coil', 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-DCT-WPU', 'catalog_page' => 9,
            ],
            [
                'slug' => 'white-food-grade-ducting-hose', 'name_en' => 'White Food-Grade Ducting Hose', 'name_fa' => 'خرطومی سفید استخوانی مواد غذایی',
                'category' => 'ducting-hoses', 'material' => 'Food-grade PVC',
                'summary_en' => 'Hygienic ducting for safe transfer of food and pharmaceutical materials.',
                'description_en' => 'An ideal solution for safe and hygienic transfer of food and pharmaceutical materials. Produced from sanitary, non-toxic materials with hygiene approvals and a flexible structure for work in varied environments.',
                'applications' => ['Food transfer', 'Pharmaceutical transfer', 'Hygienic conveying'],
                'industries' => ['Food & Beverage', 'Pharmaceutical', 'Packaging'],
                'images' => ['p11_1', 'p11_0'], 'specs' => $this->suctionSpec(7),
                'size_range' => '1" – 8"', 'length_range' => '20 – 40 m / coil', 'pressure' => 'B.P. 7 bar',
                'food_grade' => true, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-DCT-FOOD', 'catalog_page' => 10,
            ],
            [
                'slug' => 'single-layer-ribbed-rubber-hose', 'name_en' => 'Single-Layer Ribbed Rubber Hose', 'name_fa' => 'شیلنگ لاستیکی تک لایه آج دار',
                'category' => 'rubber-hoses', 'material' => 'Rubber',
                'summary_en' => 'Ribbed single layer rubber hose for general workshop duty.', 'description_en' => null,
                'applications' => ['Water transfer', 'General workshop use'],
                'industries' => ['Manufacturing', 'Agriculture'],
                'images' => ['p12_1', 'p12_0'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'Length (m)', 'B.P. (bar)'],
                    'rows' => [['1/2', 50, 7], ['3/4', 50, 7], ['1', 50, 7], ['1-1/4', 40, 7], ['1-1/2', 40, 7], ['2', 30, 7]],
                ],
                'size_range' => '1/2" – 2"', 'length_range' => null, 'pressure' => 'B.P. 7 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-RUB-SL', 'catalog_page' => 11,
            ],
            [
                'slug' => 'oil-and-petrol-braided-rubber-hose', 'name_en' => 'Oil & Petrol Braided Rubber Hose', 'name_fa' => 'شیلنگ لاستیکی نخ دار روغن و بنزین',
                'category' => 'rubber-hoses', 'material' => 'Rubber + textile braid',
                'summary_en' => 'Textile reinforced rubber hose for oil and petrol service.', 'description_en' => null,
                'applications' => ['Oil transfer', 'Petrol transfer', 'Fuel dispensing'],
                'industries' => ['Petrochemical', 'Automotive', 'Manufacturing'],
                'images' => ['p13_0'], 'specs' => $this->rubberSpec(),
                'size_range' => '1/4" – 2"', 'length_range' => null, 'pressure' => 'W.P. 20 bar · B.P. 60 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-RUB-OIL', 'catalog_page' => 12,
            ],
            [
                'slug' => 'air-braided-rubber-hose', 'name_en' => 'Air Braided Rubber Hose', 'name_fa' => 'شیلنگ لاستیکی نخ دار هوا',
                'category' => 'rubber-hoses', 'material' => 'Rubber + textile braid',
                'summary_en' => 'Textile reinforced rubber air hose for compressed air lines.', 'description_en' => null,
                'applications' => ['Compressed air', 'Workshop air lines'],
                'industries' => ['Manufacturing', 'Automation', 'Automotive'],
                'images' => ['p14_1', 'p14_0'], 'specs' => $this->rubberSpec(),
                'size_range' => '1/4" – 2"', 'length_range' => null, 'pressure' => 'W.P. 20 bar · B.P. 60 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-RUB-AIR', 'catalog_page' => 13,
            ],
            [
                'slug' => 'vopex-steel-reinforced-food-hose', 'name_en' => 'VOPEX Steel-Reinforced Food-Grade Hose', 'name_fa' => 'آبنما فنردار سه خط مواد غذایی VOPEX',
                'category' => 'reinforced-pvc-hoses', 'material' => 'Clear PVC + steel spiral',
                'summary_en' => 'Steel spiral clear hose with three lines for food-grade transfer.', 'description_en' => null,
                'applications' => ['Food liquid transfer', 'Suction lines', 'Beverage transfer'],
                'industries' => ['Food & Beverage', 'Pharmaceutical', 'Manufacturing'],
                'images' => ['p16_0', 'p16_2'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'I.D. (mm)', 'O.D. (mm)', 'Length (m)', 'B.P. (bar)'],
                    'rows' => [
                        ['3/8', 10, 18, 50, 10], ['1/2', 12, 20, 50, 10], ['5/8', 16, 24, 50, 10], ['3/4', 18, 26, 50, 8],
                        ['1', 25, 34, 50, 8], ['1-1/4', 32, 41, 50, 7], ['1-1/2', 38, 47, 50, 7], ['2', 50, 60, 50, 7],
                        ['2-1/2', 63, 73, 30, 7], ['3', 75, 85, 30, 7], ['4', 100, 112, 30, 7],
                    ],
                ],
                'size_range' => '3/8" – 4"', 'length_range' => null, 'pressure' => 'B.P. 7 – 10 bar',
                'food_grade' => true, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-PVC-VPX3', 'catalog_page' => 15,
            ],
            [
                'slug' => 'single-layer-braided-hose', 'name_en' => 'Single-Layer Braided Hose', 'name_fa' => 'شیلنگ تک لایه نخ دار',
                'category' => 'reinforced-pvc-hoses', 'material' => 'PVC + single textile braid',
                'summary_en' => 'Light, flexible braided hose for water, compressed air and light liquids.',
                'description_en' => 'Light and flexible, reinforced with a single braid layer. Used for water, compressed air and light liquids in domestic, gardening and agricultural applications.',
                'applications' => ['Water transfer', 'Compressed air', 'Gardening'],
                'industries' => ['Agriculture', 'Manufacturing'],
                'images' => ['p18_0', 'p18_1'], 'specs' => $this->braidSpec(),
                'size_range' => '1/4" – 1-1/4"', 'length_range' => null, 'pressure' => 'W.P. 5 – 7 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-PVC-B1', 'catalog_page' => 17,
            ],
            [
                'slug' => 'two-layer-braided-hose', 'name_en' => 'Two-Layer Braided Hose', 'name_fa' => 'شیلنگ دو لایه نخ دار',
                'category' => 'reinforced-pvc-hoses', 'material' => 'PVC + double textile braid',
                'summary_en' => 'Double braid construction for higher pressure and tensile strength.',
                'description_en' => 'Reinforced with braid for resistance and strength against pressure and tension. For high pressure use in agriculture and for air and liquid transfer.',
                'applications' => ['High pressure water', 'Air transfer', 'Liquid transfer'],
                'industries' => ['Agriculture', 'Manufacturing', 'Petrochemical'],
                'images' => ['p19_1', 'p19_0'], 'specs' => $this->braidSpec(),
                'size_range' => '1/4" – 1-1/4"', 'length_range' => null, 'pressure' => 'W.P. 5 – 7 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-PVC-B2', 'catalog_page' => 18,
            ],
            [
                'slug' => 'transparent-three-line-food-hose', 'name_en' => 'Transparent 3-Line Food-Grade Braided Hose', 'name_fa' => 'شیلنگ شفاف نخ دار سه خط مواد غذایی',
                'category' => 'reinforced-pvc-hoses', 'material' => 'Clear PVC + textile braid',
                'summary_en' => 'Fully transparent braided hose with smooth bore for food liquids.',
                'description_en' => 'Completely transparent structure reinforced with braid. High pressure resistance for transferring liquids and food products, with a smooth internal surface that prevents bacterial build-up.',
                'applications' => ['Food liquid transfer', 'Beverage lines', 'Clean water'],
                'industries' => ['Food & Beverage', 'Pharmaceutical', 'Packaging'],
                'images' => ['p20_0', 'p20_1'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'Length (m)', 'B.P. (bar)'],
                    'rows' => [['1/4', 100, 15], ['5/16', 100, 15], ['3/8', 100, 15], ['1/2', 50, 15], ['5/8', 50, 15], ['3/4', 50, 15], ['1', 50, 15], ['1-1/4', 50, 15], ['1-1/2', 50, 15], ['2', 50, 15]],
                ],
                'size_range' => '1/4" – 2"', 'length_range' => null, 'pressure' => 'B.P. 15 bar',
                'food_grade' => true, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-PVC-CLR3', 'catalog_page' => 19,
            ],
            [
                'slug' => 'single-layer-silicone-hose', 'name_en' => 'Single-Layer Silicone Hose', 'name_fa' => 'شیلنگ تک لایه سیلیکونی',
                'category' => 'silicone', 'material' => 'Silicone',
                'summary_en' => 'Flexible silicone tubing for heat resistant and hygienic transfer.', 'description_en' => null,
                'applications' => ['Heat resistant transfer', 'Laboratory lines', 'Food processing'],
                'industries' => ['Food & Beverage', 'Pharmaceutical', 'Medical'],
                'images' => ['p22_1', 'p22_4'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => true, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-SL', 'catalog_page' => 21,
            ],
            [
                'slug' => 'braided-silicone-hose', 'name_en' => 'Braided Silicone Hose', 'name_fa' => 'شیلنگ نخ دار سیلیکونی',
                'category' => 'silicone', 'material' => 'Silicone + textile braid',
                'summary_en' => 'Reinforced silicone hose for pressurised hot media.', 'description_en' => null,
                'applications' => ['Hot fluid transfer', 'Process lines'],
                'industries' => ['Pharmaceutical', 'Food & Beverage', 'Manufacturing'],
                'images' => ['p22_0'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-BR', 'catalog_page' => 21,
            ],
            [
                'slug' => 'silicone-cord', 'name_en' => 'Silicone Cord', 'name_fa' => 'مفتول سیلیکونی',
                'category' => 'silicone', 'material' => 'Silicone',
                'summary_en' => 'Solid silicone cord for sealing and gasket applications.', 'description_en' => null,
                'applications' => ['Sealing', 'Gasketing'], 'industries' => ['Manufacturing', 'Packaging'],
                'images' => ['p23_3'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-CRD', 'catalog_page' => 22,
            ],
            [
                'slug' => 'sponge-silicone-cord', 'name_en' => 'Sponge Silicone Cord', 'name_fa' => 'مفتول سیلیکونی اسفنجی',
                'category' => 'silicone', 'material' => 'Sponge silicone',
                'summary_en' => 'Compressible sponge silicone cord for oven and door seals.', 'description_en' => null,
                'applications' => ['Thermal sealing', 'Door seals'], 'industries' => ['Manufacturing', 'Food & Beverage'],
                'images' => ['p23_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-SPG', 'catalog_page' => 22,
            ],
            [
                'slug' => 'silicone-sheet', 'name_en' => 'Silicone Sheet', 'name_fa' => 'ورق سیلیکونی',
                'category' => 'silicone', 'material' => 'Silicone',
                'summary_en' => 'Silicone sheet rolls for gaskets, press pads and hygienic surfaces.', 'description_en' => null,
                'applications' => ['Gasket cutting', 'Press pads', 'Hygienic lining'],
                'industries' => ['Manufacturing', 'Food & Beverage', 'Pharmaceutical'],
                'images' => ['p24_3'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-SHT', 'catalog_page' => 23,
            ],
            [
                'slug' => 'silicone-profile', 'name_en' => 'Silicone Profile', 'name_fa' => 'پروفیل سیلیکونی',
                'category' => 'silicone', 'material' => 'Silicone',
                'summary_en' => 'Extruded silicone profiles in a wide range of sections.', 'description_en' => null,
                'applications' => ['Sealing profiles', 'Edge protection'], 'industries' => ['Manufacturing', 'Packaging', 'Automation'],
                'images' => ['p24_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-PRF', 'catalog_page' => 23,
            ],
            [
                'slug' => 'silicone-radiator-hose', 'name_en' => 'Silicone Radiator Hose & Joints', 'name_fa' => 'انواع جنت و لوله رادیاتور سیلیکونی',
                'category' => 'silicone', 'material' => 'Silicone',
                'summary_en' => 'Moulded silicone radiator hoses and joints for engines.', 'description_en' => null,
                'applications' => ['Coolant lines', 'Engine joints'], 'industries' => ['Automotive', 'Manufacturing'],
                'images' => ['p25_0'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-RAD', 'catalog_page' => 24,
            ],
            [
                'slug' => 'silicone-wire-and-cable', 'name_en' => 'Silicone Wire & Power Cable', 'name_fa' => 'سیم و کابل برق سیلیکونی',
                'category' => 'silicone', 'material' => 'Silicone insulated copper',
                'summary_en' => 'Heat resistant silicone insulated wire and cable.', 'description_en' => null,
                'applications' => ['High temperature wiring', 'Industrial panels'], 'industries' => ['Manufacturing', 'Automation'],
                'images' => ['p25_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-SIL-WIRE', 'catalog_page' => 24,
            ],
            [
                'slug' => 'one-wire-hydraulic-hose', 'name_en' => 'One-Wire Braided Hydraulic Hose', 'name_fa' => 'شیلنگ هیدرولیک تک لایه سیم دار',
                'category' => 'hydraulic', 'material' => 'Rubber + 1 steel wire braid',
                'summary_en' => 'Single wire braid hydraulic hose up to 250 bar working pressure.', 'description_en' => null,
                'applications' => ['Hydraulic power lines', 'Mobile equipment'], 'industries' => ['Manufacturing', 'Automotive', 'Agriculture'],
                'images' => ['p28_1'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'W.P. (bar)', 'B.P. (bar)', 'Bending Radius (mm)'],
                    'rows' => [
                        ['3/16', 250, 1000, 90], ['1/4', 225, 900, 100], ['5/16', 215, 850, 115], ['3/8', 180, 720, 130],
                        ['1/2', 160, 640, 180], ['5/8', 130, 520, 200], ['3/4', 105, 420, 240], ['1', 88, 350, 300],
                        ['1-1/4', 62, 250, 420], ['1-1/2', 50, 200, 500], ['2', 40, 160, 630],
                    ],
                ],
                'size_range' => '3/16" – 2"', 'length_range' => null, 'pressure' => 'W.P. up to 250 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-HYD-1W', 'catalog_page' => 27,
            ],
            [
                'slug' => 'two-wire-hydraulic-hose', 'name_en' => 'Two-Wire Braided Hydraulic Hose', 'name_fa' => 'شیلنگ هیدرولیک دو لایه سیم دار',
                'category' => 'hydraulic', 'material' => 'Rubber + 2 steel wire braids',
                'summary_en' => 'Double wire braid hose up to 415 bar working pressure.', 'description_en' => null,
                'applications' => ['High pressure hydraulics', 'Presses', 'Heavy equipment'], 'industries' => ['Manufacturing', 'Petrochemical', 'Automotive'],
                'images' => ['p29_1'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'W.P. (bar)', 'B.P. (bar)', 'Bending Radius (mm)'],
                    'rows' => [
                        ['3/16', 415, 1660, 90], ['1/4', 400, 1600, 100], ['5/16', 350, 1400, 115], ['3/8', 330, 1320, 130],
                        ['1/2', 275, 1100, 180], ['5/8', 250, 1000, 200], ['3/4', 215, 860, 240], ['1', 165, 660, 300],
                        ['1-1/4', 125, 500, 420], ['1-1/2', 90, 360, 500], ['2', 80, 320, 630],
                    ],
                ],
                'size_range' => '3/16" – 2"', 'length_range' => null, 'pressure' => 'W.P. up to 415 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-HYD-2W', 'catalog_page' => 28,
            ],
            [
                'slug' => 'four-spiral-hydraulic-hose', 'name_en' => 'Four-Spiral Hydraulic Hose', 'name_fa' => 'شیلنگ هیدرولیک چهار لایه سیم دار',
                'category' => 'hydraulic', 'material' => 'Rubber + 4 steel spirals',
                'summary_en' => 'Four spiral hose for sustained very high pressure duty.', 'description_en' => null,
                'applications' => ['Heavy hydraulics', 'Mining and construction equipment'], 'industries' => ['Manufacturing', 'Petrochemical'],
                'images' => ['p30_0'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'W.P. (bar)', 'B.P. (bar)', 'Bending Radius (mm)'],
                    'rows' => [['3/4', 420, 1680, 210], ['1', 380, 1538, 220], ['1-1/4', 325, 1400, 420], ['1-1/2', 290, 1200, 560], ['2', 250, 1000, 700]],
                ],
                'size_range' => '3/4" – 2"', 'length_range' => null, 'pressure' => 'W.P. up to 420 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-HYD-4S', 'catalog_page' => 29,
            ],
            [
                'slug' => 'six-spiral-hydraulic-hose', 'name_en' => 'Six-Spiral Hydraulic Hose', 'name_fa' => 'شیلنگ هیدرولیک شش لایه سیم دار',
                'category' => 'hydraulic', 'material' => 'Rubber + 6 steel spirals',
                'summary_en' => 'Six spiral construction with constant 350 bar working pressure.', 'description_en' => null,
                'applications' => ['Extreme pressure hydraulics'], 'industries' => ['Petrochemical', 'Manufacturing'],
                'images' => ['p30_1'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'W.P. (bar)', 'B.P. (bar)', 'Bending Radius (mm)'],
                    'rows' => [['1-1/4', 350, 1400, 420], ['1-1/2', 350, 1400, 500], ['2', 350, 1400, 640]],
                ],
                'size_range' => '1-1/4" – 2"', 'length_range' => null, 'pressure' => 'W.P. 350 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'Made to order',
                'sku' => 'ATN-HYD-6S', 'catalog_page' => 29,
            ],
            [
                'slug' => 'steam-hose', 'name_en' => 'Steam Hose', 'name_fa' => 'شیلنگ استیم بخار',
                'category' => 'hydraulic', 'material' => 'Rubber + textile/wire reinforcement',
                'summary_en' => 'Saturated steam hose for cleaning and process steam lines.', 'description_en' => null,
                'applications' => ['Steam cleaning', 'Process steam'], 'industries' => ['Food & Beverage', 'Petrochemical', 'Manufacturing'],
                'images' => ['p31_3', 'p31_4'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'On request',
                'sku' => 'ATN-HYD-STM', 'catalog_page' => 30,
            ],
            [
                'slug' => 'composite-hose', 'name_en' => 'Composite Hose', 'name_fa' => 'شیلنگ کامپوزیت',
                'category' => 'hydraulic', 'material' => 'Composite multi-layer',
                'summary_en' => 'Composite hose assemblies for chemical and fuel loading.', 'description_en' => null,
                'applications' => ['Chemical loading', 'Fuel loading', 'Tanker transfer'], 'industries' => ['Petrochemical', 'Manufacturing'],
                'images' => ['p31_0', 'p31_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'On request',
                'sku' => 'ATN-HYD-CMP', 'catalog_page' => 30,
            ],
            [
                'slug' => 'pneumatic-polyurethane-tube', 'name_en' => 'Pneumatic Polyurethane Tube', 'name_fa' => 'شیلنگ پنوماتیک',
                'category' => 'pneumatic', 'material' => 'Polyurethane',
                'summary_en' => 'Colour coded PU tubing for automation and compressed air circuits.', 'description_en' => null,
                'applications' => ['Compressed air circuits', 'Automation lines'], 'industries' => ['Automation', 'Manufacturing', 'Packaging'],
                'images' => ['p38_1', 'p38_0'],
                'specs' => [
                    'columns' => ['I.D. (mm)', 'O.D. (mm)', 'Length (m)', 'W.P. (bar)', 'B.P. (bar)'],
                    'rows' => [[2, 4, 200, 10, 30], [4, 6, 200, 10, 30], [5, 8, 100, 10, 30], [6.5, 10, 100, 10, 30], [8, 12, 100, 10, 30], [10, 14, 100, 10, 30], [12, 16, 100, 10, 30], [14, 18, 100, 10, 30]],
                ],
                'size_range' => '2 – 14 mm I.D.', 'length_range' => null, 'pressure' => 'W.P. 10 bar · B.P. 30 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => true, 'availability' => 'In stock',
                'sku' => 'ATN-PNE-PU', 'catalog_page' => 37,
            ],
            [
                'slug' => 'fire-hose', 'name_en' => 'Layflat Fire Hose', 'name_fa' => 'شیلنگ برزنتی ضد حریق',
                'category' => 'fire-safety', 'material' => 'Synthetic jacket + rubber lining',
                'summary_en' => 'Layflat fire hose supplied with couplings.', 'description_en' => null,
                'applications' => ['Fire fighting', 'Emergency water supply'], 'industries' => ['Manufacturing', 'Petrochemical'],
                'images' => ['p36_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'On request',
                'sku' => 'ATN-FIR-RED', 'catalog_page' => 35,
            ],
            [
                'slug' => 'anti-acid-layflat-hose', 'name_en' => 'Anti-Acid Layflat Hose', 'name_fa' => 'شیلنگ برزنتی ضد اسید',
                'category' => 'fire-safety', 'material' => 'Chemical resistant jacket',
                'summary_en' => 'Anti-acid layflat hose for aggressive media discharge.', 'description_en' => null,
                'applications' => ['Acid discharge', 'Chemical transfer'], 'industries' => ['Petrochemical', 'Manufacturing'],
                'images' => ['p36_3'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'On request',
                'sku' => 'ATN-FIR-ACD', 'catalog_page' => 35,
            ],
            [
                'slug' => 'fire-fighting-couplings', 'name_en' => 'Fire Fighting Couplings & Fittings', 'name_fa' => 'اتصالات آتش‌نشانی',
                'category' => 'fire-safety', 'material' => 'Aluminium / brass',
                'summary_en' => 'Storz and instantaneous couplings, nozzles and adapters.', 'description_en' => null,
                'applications' => ['Fire hose coupling', 'Hydrant connection'], 'industries' => ['Manufacturing', 'Petrochemical'],
                'images' => ['p36_1'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'On request',
                'sku' => 'ATN-FIR-FIT', 'catalog_page' => 35,
            ],
            [
                'slug' => 'worm-drive-hose-clamps', 'name_en' => 'Worm Drive Hose Clamps', 'name_fa' => 'بست فشار قوی',
                'category' => 'clamps-fittings', 'material' => 'Galvanised / stainless steel',
                'summary_en' => 'Worm drive clamps in a full range of clamping diameters.', 'description_en' => null,
                'applications' => ['Hose clamping', 'Line fixing'], 'industries' => ['Manufacturing', 'Automotive', 'Agriculture'],
                'images' => ['p45_4'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-CLP-WRM', 'catalog_page' => 44,
            ],
            [
                'slug' => 't-bolt-hose-clamp', 'name_en' => 'T-Bolt Hose Clamp', 'name_fa' => 'بست آچاری',
                'category' => 'clamps-fittings', 'material' => 'Steel',
                'summary_en' => 'Heavy duty T-bolt clamp for high pressure hose ends.', 'description_en' => null,
                'applications' => ['Heavy hose clamping'], 'industries' => ['Manufacturing', 'Petrochemical'],
                'images' => ['p45_0'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-CLP-TBT', 'catalog_page' => 44,
            ],
            [
                'slug' => 'handle-hose-clamp', 'name_en' => 'Handle Hose Clamp', 'name_fa' => 'بست هندلی',
                'category' => 'clamps-fittings', 'material' => 'Steel',
                'summary_en' => 'Handle clamp for large diameter suction hose assemblies.', 'description_en' => null,
                'applications' => ['Suction hose clamping'], 'industries' => ['Agriculture', 'Manufacturing'],
                'images' => ['p45_3'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-CLP-HND', 'catalog_page' => 44,
            ],
            [
                'slug' => 'cable-ties', 'name_en' => 'Cable Ties', 'name_fa' => 'بست کمربندی',
                'category' => 'clamps-fittings', 'material' => 'Nylon',
                'summary_en' => 'Nylon cable ties in industrial lengths and widths.', 'description_en' => null,
                'applications' => ['Cable bundling', 'Line fixing'], 'industries' => ['Automation', 'Manufacturing'],
                'images' => ['p44_0'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-CLP-TIE', 'catalog_page' => 43,
            ],
            [
                'slug' => 'stainless-steel-cable-ties', 'name_en' => 'Stainless Steel Cable Ties', 'name_fa' => 'بست کمربندی استیل',
                'category' => 'clamps-fittings', 'material' => 'Stainless steel',
                'summary_en' => 'Stainless steel banding ties for harsh environments.', 'description_en' => null,
                'applications' => ['Outdoor fixing', 'High temperature fixing'], 'industries' => ['Petrochemical', 'Manufacturing'],
                'images' => ['p44_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-CLP-SST', 'catalog_page' => 43,
            ],
            [
                'slug' => 'tygon-hose', 'name_en' => 'TYGON Hose', 'name_fa' => 'شیلنگ تایگون',
                'category' => 'specialty-hoses', 'material' => 'Clear flexible polymer',
                'summary_en' => 'Clear TYGON tubing supplied on reels for laboratory and process use.', 'description_en' => null,
                'applications' => ['Laboratory transfer', 'Analytical lines'], 'industries' => ['Pharmaceutical', 'Medical', 'Food & Beverage'],
                'images' => ['p46_1'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => true, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-TYG', 'catalog_page' => 45,
            ],
            [
                'slug' => 'korean-style-clear-braided-hose', 'name_en' => 'Korean-Style Clear Braided Hose', 'name_fa' => 'شیلنگ شفاف نخدار طرح کره',
                'category' => 'specialty-hoses', 'material' => 'Clear PVC + braid',
                'summary_en' => 'Clear ribbed braided hose with high transparency.', 'description_en' => null,
                'applications' => ['Water transfer', 'Visual flow lines'], 'industries' => ['Manufacturing', 'Food & Beverage'],
                'images' => ['p46_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-KOR', 'catalog_page' => 45,
            ],
            [
                'slug' => 'side-by-side-fridge-hose', 'name_en' => 'Side-by-Side Fridge Hose', 'name_fa' => 'شیلنگ یخچال ساید',
                'category' => 'specialty-hoses', 'material' => 'Food-grade polymer',
                'summary_en' => 'Small bore water line for side-by-side refrigerators.', 'description_en' => null,
                'applications' => ['Appliance water lines'], 'industries' => ['Manufacturing', 'Food & Beverage'],
                'images' => ['p47_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => true, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-FRG', 'catalog_page' => 46,
            ],
            [
                'slug' => 'bath-and-jacuzzi-hose', 'name_en' => 'Bath & Jacuzzi Hose', 'name_fa' => 'شیلنگ وان و جکوزی',
                'category' => 'specialty-hoses', 'material' => 'Reinforced PVC',
                'summary_en' => 'Flexible reinforced hose for bath and jacuzzi installations.', 'description_en' => null,
                'applications' => ['Sanitary installation', 'Water circulation'], 'industries' => ['Manufacturing'],
                'images' => ['p47_3'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-JAC', 'catalog_page' => 46,
            ],
            [
                'slug' => 'torch-hose', 'name_en' => 'Torch Hose', 'name_fa' => 'شیلنگ تورچ',
                'category' => 'specialty-hoses', 'material' => 'Rubber + braid',
                'summary_en' => 'Braided torch hose for welding and cutting equipment.', 'description_en' => null,
                'applications' => ['Welding torch lines', 'Gas supply'], 'industries' => ['Manufacturing', 'Automotive'],
                'images' => ['p48_1'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-TRC', 'catalog_page' => 47,
            ],
            [
                'slug' => 'polyamide-hose', 'name_en' => 'NAJIN Polyamide Hose', 'name_fa' => 'شیلنگ ناجین پلی آمید',
                'category' => 'specialty-hoses', 'material' => 'Polyamide',
                'summary_en' => 'Polyamide tubing for pneumatic and fuel micro-lines.', 'description_en' => null,
                'applications' => ['Pneumatic lines', 'Fuel micro lines'], 'industries' => ['Automation', 'Automotive'],
                'images' => ['p48_2'],
                'specs' => [
                    'columns' => ['I.D. (mm)', 'O.D. (mm)', 'Length (m)', 'B.P. (bar)'],
                    'rows' => [[2, 4, 200, 20], [4, 6, 100, 20], [5, 8, 100, 20], [7, 10, 100, 20], [9, 12, 100, 20], [10, 14, 100, 20], [12, 16, 100, 20]],
                ],
                'size_range' => '2 – 12 mm I.D.', 'length_range' => null, 'pressure' => 'B.P. 20 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-PA', 'catalog_page' => 47,
            ],
            [
                'slug' => 'level-hose', 'name_en' => 'Level Hose', 'name_fa' => 'شیلنگ تراز',
                'category' => 'specialty-hoses', 'material' => 'Clear PVC',
                'summary_en' => 'Clear levelling hose supplied by weight per coil.', 'description_en' => null,
                'applications' => ['Site levelling', 'Construction'], 'industries' => ['Manufacturing'],
                'images' => ['p49_2'],
                'specs' => [
                    'columns' => ['Measurement', 'Weight (Kg)'],
                    'rows' => [['4 mm', 3], ['5 mm', 5], ['6 mm', 5], ['8 mm', 10], ['10 mm', 10], ['12 mm', 10], ['14 mm', 10], ['16 mm', 10], ['18 mm', 10]],
                ],
                'size_range' => '4 – 18 mm', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-LVL', 'catalog_page' => 48,
            ],
            [
                'slug' => 'twin-welding-hose', 'name_en' => 'Twin Welding & Cutting Hose', 'name_fa' => 'شیلنگ دوقلوی هوابرش',
                'category' => 'specialty-hoses', 'material' => 'Rubber twin line',
                'summary_en' => 'Bonded twin hose for oxygen and fuel gas cutting sets.', 'description_en' => null,
                'applications' => ['Oxy-fuel cutting', 'Welding sets'], 'industries' => ['Manufacturing', 'Automotive'],
                'images' => ['p49_3'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-TWN', 'catalog_page' => 48,
            ],
            [
                'slug' => 'milking-hose', 'name_en' => 'Milking Hose', 'name_fa' => 'شیلنگ شیردوشی',
                'category' => 'specialty-hoses', 'material' => 'Food-grade clear PVC',
                'summary_en' => 'Hygienic clear hose for milking equipment.', 'description_en' => null,
                'applications' => ['Milking machines', 'Dairy transfer'], 'industries' => ['Food & Beverage', 'Agriculture'],
                'images' => ['p50_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => true, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-MLK', 'catalog_page' => 49,
            ],
            [
                'slug' => 'blue-braided-fuel-hose', 'name_en' => 'Blue Braided Fuel & Liquid Hose', 'name_fa' => 'شیلنگ آبی نخدار سوخت و مایعات',
                'category' => 'specialty-hoses', 'material' => 'PVC + braid',
                'summary_en' => 'Braided blue hose for fuel and liquid transfer.', 'description_en' => null,
                'applications' => ['Fuel transfer', 'Liquid transfer'], 'industries' => ['Petrochemical', 'Agriculture'],
                'images' => ['p50_1'],
                'specs' => [
                    'columns' => ['Measurement (Inch)', 'Length (m)', 'B.P. (bar)'],
                    'rows' => [['1/2', 50, 15], ['3/4', 50, 15], ['1', 50, 15], ['1-1/4', 30, 15], ['1-1/2', 25, 15], ['2', 20, 15]],
                ],
                'size_range' => '1/2" – 2"', 'length_range' => null, 'pressure' => 'B.P. 15 bar',
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-BFL', 'catalog_page' => 49,
            ],
            [
                'slug' => 'spraying-hose', 'name_en' => 'Agricultural Spraying Hose', 'name_fa' => 'شیلنگ سمپاشی',
                'category' => 'specialty-hoses', 'material' => 'Reinforced PVC',
                'summary_en' => 'High pressure spraying hose for crop protection equipment.', 'description_en' => null,
                'applications' => ['Crop spraying', 'Pesticide transfer'], 'industries' => ['Agriculture'],
                'images' => ['p51_1'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-SPR', 'catalog_page' => 50,
            ],
            [
                'slug' => 'uv-resistant-cooler-hose', 'name_en' => 'UV Resistant Cooler Hose', 'name_fa' => 'شیلنگ کولری ضد آفتاب',
                'category' => 'specialty-hoses', 'material' => 'UV stabilised PVC',
                'summary_en' => 'Sun resistant hose for evaporative cooler water lines.', 'description_en' => null,
                'applications' => ['Cooler water lines', 'Outdoor water lines'], 'industries' => ['Manufacturing', 'Ventilation'],
                'images' => ['p51_0'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'In stock',
                'sku' => 'ATN-SPC-CLR', 'catalog_page' => 50,
            ],
            [
                'slug' => 'medical-and-laboratory-hoses', 'name_en' => 'Medical & Laboratory Hoses', 'name_fa' => 'شیلنگ های پزشکی و آزمایشگاهی',
                'category' => 'specialty-hoses', 'material' => 'Medical grade polymer',
                'summary_en' => 'Medical and laboratory tubing sets for clinical equipment.', 'description_en' => null,
                'applications' => ['Clinical equipment', 'Laboratory transfer'], 'industries' => ['Medical', 'Pharmaceutical'],
                'images' => ['p52_2'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => true, 'featured' => true, 'availability' => 'On request',
                'sku' => 'ATN-SPC-MED', 'catalog_page' => 51,
            ],
            [
                'slug' => 'ptfe-stainless-braided-hose', 'name_en' => 'PTFE Hose with Stainless Braid', 'name_fa' => 'شیلنگ استیل داخل تفلون',
                'category' => 'specialty-hoses', 'material' => 'PTFE + stainless steel braid',
                'summary_en' => 'PTFE core with stainless braid for aggressive and hot media.', 'description_en' => null,
                'applications' => ['Chemical transfer', 'High temperature lines'], 'industries' => ['Petrochemical', 'Pharmaceutical', 'Manufacturing'],
                'images' => ['p52_3'], 'specs' => null,
                'size_range' => 'Available on request', 'length_range' => null, 'pressure' => null,
                'food_grade' => false, 'medical_grade' => false, 'featured' => false, 'availability' => 'On request',
                'sku' => 'ATN-SPC-PTFE', 'catalog_page' => 51,
            ],
        ];
    }
}
