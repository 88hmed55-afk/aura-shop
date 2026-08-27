<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::create([
            'name' => 'AURA Master Administrator',
            'email' => 'admin@aura.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+966 50 000 1111',
            'locale' => 'en',
            'theme' => 'dark',
        ]);

        // 2. Create Demo Customer
        $customer = User::create([
            'name' => 'Sultan Al-Mansoor',
            'email' => 'customer@aura.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+966 55 123 4567',
            'locale' => 'ar',
            'theme' => 'dark',
        ]);

        // Address for customer
        Address::create([
            'user_id' => $customer->id,
            'title' => 'Palace Residence',
            'full_name' => 'Sultan Al-Mansoor',
            'phone' => '+966 55 123 4567',
            'address_line_1' => 'King Fahd Road, Olaya District',
            'city' => 'Riyadh',
            'state' => 'Riyadh Province',
            'postal_code' => '12211',
            'country' => 'Saudi Arabia',
            'is_default' => true,
        ]);

        // 3. Create Categories
        $categoriesData = [
            [
                'name_en' => 'Haute Horlogerie & Watches',
                'name_ar' => 'الساعات الفاخرة',
                'slug' => 'watches',
                'description_en' => 'Swiss-engineered chronographs and master craftsmanship timepieces.',
                'description_ar' => 'ساعات سويسرية فاخرة مصممة بأعلى درجات الدقة والمهارة.',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'name_en' => 'High-Fidelity Audio',
                'name_ar' => 'الصوتيات عالية الدقة',
                'slug' => 'audio',
                'description_en' => 'Studio-grade noise canceling headphones and planar magnetic acoustics.',
                'description_ar' => 'سماعات رأس لاسلكية بنقاء صوت المسارح الاحترافية وعزل تام للضوضاء.',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'name_en' => 'Royal Fragrance & Perfumes',
                'name_ar' => 'العطور والنسائم الملكية',
                'slug' => 'perfume',
                'description_en' => 'Rare Cambodian oud, pure damask rose, and bespoke luxury extraits.',
                'description_ar' => 'عطور ملكية نادرة مقتناة من العود الكمبودي والورد الجوري الأصيل.',
                'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'name_en' => 'Modern Tech & Wearables',
                'name_ar' => 'التقنيات الحديثة والأجهزة',
                'slug' => 'tech',
                'description_en' => 'Ergonomic titanium devices, smart rings, and futuristic workspace gear.',
                'description_ar' => 'أجهزة ذكية من التيتانيوم ومعدات رقمية متطورة لمساحة عملك.',
                'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'name_en' => 'Bespoke Leather Goods',
                'name_ar' => 'الجلديات والإكسسوارات',
                'slug' => 'accessories',
                'description_en' => 'Handmade full-grain Italian leather duffles, wallets, and travel cases.',
                'description_ar' => 'حقائب ومحفظات جلدية إيطالية فائقة الجودة مصنوعة يدوياً.',
                'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::create($c);
        }

        // 4. Create Luxury Products
        $productsData = [
            // Watches
            [
                'category_slug' => 'watches',
                'name_en' => 'AURA Chrono Royal Titanium Automatic',
                'name_ar' => 'ساعة أورا كرونو رويال تيتانيوم اتوماتيك',
                'slug' => 'aura-chrono-royal-titanium',
                'price' => 3450.00,
                'compare_at_price' => 4100.00,
                'sku' => 'AUR-WAT-001',
                'stock_quantity' => 8,
                'is_featured' => true,
                'rating' => 4.95,
                'reviews_count' => 14,
                'short_description_en' => 'Crafted with grade-5 titanium, sapphire crystal front, and skeletonized tourbillon caliber.',
                'short_description_ar' => 'مصنوعة من التيتانيوم عالي الجودة مع زجاج السفير المقاوم للخدش ومحرك توربيون المفتوح.',
                'description_en' => 'The Chrono Royal Titanium represents the pinnacle of watchmaking mastery. Designed with an ultralight grade-5 titanium case, luminescent hands, and 70-hour power reserve automatic movement. Water resistant to 100 meters.',
                'description_ar' => 'تجسد ساعة كرونو رويال تيتانيوم قمة الإبداع في صناعة الساعات السويسرية. هيكل خفيف للغاية من التيتانيوم، مؤشرات مضيئة في الظلام، واحتياطي طاقة يدوم حتى 70 ساعة.',
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1539185441755-769473a23570?w=800&auto=format&fit=crop&q=80'
                ],
            ],
            [
                'category_slug' => 'watches',
                'name_en' => 'Vanguard Obsidian Ceramic Diver',
                'name_ar' => 'ساعة فانغارد أوبسيديان سيراميك للغوص',
                'slug' => 'vanguard-obsidian-ceramic-diver',
                'price' => 2890.00,
                'compare_at_price' => 3200.00,
                'sku' => 'AUR-WAT-002',
                'stock_quantity' => 4,
                'is_featured' => true,
                'rating' => 4.88,
                'reviews_count' => 9,
                'short_description_en' => 'Full black ceramic bezel, helium escape valve, and matte midnight dial.',
                'short_description_ar' => 'إطار سيراميك أسود بالكامل مع صمام هليوم وميناء أسود ملمس الفخامة.',
                'description_en' => 'Uncompromising precision for the deep ocean. Features a scratch-proof high-tech ceramic case, 300M water resistance, and a vulcanized rubber strap.',
                'description_ar' => 'دقة لا تتنازل لأعماق المحيط. تتميز بهيكل سيراميك متطور مقاوم للخدوش ومقاومة للماء حتى عمق 300 متر.',
                'images' => [
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            // Audio
            [
                'category_slug' => 'audio',
                'name_en' => 'AURA Studio Pro Wireless Spatial ANC',
                'name_ar' => 'سماعات أورا ستوديو برو اللاسلكية بنظام العزل الفائق',
                'slug' => 'aura-studio-pro-wireless-anc',
                'price' => 1250.00,
                'compare_at_price' => 1490.00,
                'sku' => 'AUR-AUD-001',
                'stock_quantity' => 15,
                'is_featured' => true,
                'rating' => 4.92,
                'reviews_count' => 28,
                'short_description_en' => 'Lossless Bluetooth 5.4, beryllium audio drivers, and active noise reduction.',
                'short_description_ar' => 'صوت لاسلكي عالي الدقة دون فقدان، مشغلات بريليوم وصوت محيطي مذهل.',
                'description_en' => 'Immerse yourself in acoustic perfection. Custom 40mm custom beryllium drivers paired with real-time adaptive noise canceling deliver uncompressed studio sound.',
                'description_ar' => 'انغمس في الصوت الخالص النقي. مشغلات صوتية مقاس 40 ملم مصنوعة من البريليوم مع تقنية العزل التكيفي الذكي.',
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'audio',
                'name_en' => 'Spheric Sound Desktop Tube Amplifier & DAC',
                'name_ar' => 'مكبر الصوت أورا سفيريك تيوب & أنالوج داك',
                'slug' => 'spheric-sound-desktop-tube-amplifier',
                'price' => 1850.00,
                'compare_at_price' => 2100.00,
                'sku' => 'AUR-AUD-002',
                'stock_quantity' => 3,
                'is_featured' => false,
                'rating' => 5.00,
                'reviews_count' => 6,
                'short_description_en' => 'Vacuum tube warm analog fidelity with 32-bit/384kHz audiophile DAC.',
                'short_description_ar' => 'أنبوب تفريغ تناظري دافئ يقدم نقاء صوتي استثنائي بدقة 32بت.',
                'description_en' => 'Handcrafted aluminium body housing dual vintage vacuum tubes for unmatched warm analog harmonic sound quality.',
                'description_ar' => 'هيكل ألومنيوم مصنوع يدوياً يحتوي على أنابيب تفريغ زجاجية كلاسيكية لمنحك تجربة استماع دافئة وعميقة.',
                'images' => [
                    'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            // Perfume
            [
                'category_slug' => 'perfume',
                'name_en' => 'Royal Amber & Imperial Cambodian Oud Extrait',
                'name_ar' => 'عطر الرويال أمبر والعود الكمبودي الملكي',
                'slug' => 'royal-amber-cambodian-oud-extrait',
                'price' => 1650.00,
                'compare_at_price' => 1950.00,
                'sku' => 'AUR-PRF-001',
                'stock_quantity' => 12,
                'is_featured' => true,
                'rating' => 4.98,
                'reviews_count' => 42,
                'short_description_en' => 'Concentrated 35% perfume oil blending aged Cambodian wild oud, amber, and Taif rose.',
                'short_description_ar' => 'تركيز زيتي 35% يجمع بين العود الكمبودي المعتق، العنبر الفاخر، وورد الطائف.',
                'description_en' => 'A fragrance reserved for royalty. Distilled from rare 50-year aged wild agarwood and hand-picked Taif roses, presented in a crystal faceted bottle with gold leaf cap.',
                'description_ar' => 'عطر ملكي ساحر مستخلص من العود الكمبودي المعتق لأكثر من 50 عاماً مع الورد الطائفي النادر في زجاجة كريستالية متألقة.',
                'images' => [
                    'https://images.unsplash.com/photo-1594035910387-fea47794261f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1547887537-6158d64c35b3?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'perfume',
                'name_en' => 'Velour Noir Vanilla & Smoked Saffron',
                'name_ar' => 'عطر فيلور نوار الفانيليا والزعفران المدخن',
                'slug' => 'velour-noir-vanilla-smoked-saffron',
                'price' => 980.00,
                'compare_at_price' => 1150.00,
                'sku' => 'AUR-PRF-002',
                'stock_quantity' => 9,
                'is_featured' => false,
                'rating' => 4.85,
                'reviews_count' => 19,
                'short_description_en' => 'Bourbon vanilla, spicy Kashmiri saffron, leather, and tonka bean.',
                'short_description_ar' => 'فانيليا البوربون، الزعفران الكشميري الحار، نفحات الجلد والتونكا.',
                'description_en' => 'An alluring evening scent opening with warm saffron notes and resolving into rich Madagascar vanilla and smoked leather.',
                'description_ar' => 'عطر مسائي جذاب يفتتح بنفحات الزعفران الدافئة وينتهي بالفانيليا الغنية والجلد المدخن.',
                'images' => [
                    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            // Tech
            [
                'category_slug' => 'tech',
                'name_en' => 'AURA Horizon Smartwatch Titanium OLED',
                'name_ar' => 'ساعة أورا هورايزون الذكية تيتانيوم OLED',
                'slug' => 'aura-horizon-smartwatch-titanium',
                'price' => 1490.00,
                'compare_at_price' => 1750.00,
                'sku' => 'AUR-TCH-001',
                'stock_quantity' => 20,
                'is_featured' => true,
                'rating' => 4.90,
                'reviews_count' => 31,
                'short_description_en' => 'Always-on sapphire OLED display, health sensors, ECG, and 14-day battery.',
                'short_description_ar' => 'شاشة أوليد من زجاج السفير المستمر، مستشعرات صحية وتخطيط القلب وبطارية 14 يوماً.',
                'description_en' => 'Combining Haute Horlogerie aesthetics with futuristic biometric sensors. Monitors heart rate variability, sleep stages, oxygen saturation, and GPS precision.',
                'description_ar' => 'تدمج بين جماليات الساعات الفاخرة وأحدث التقنيات الطبية الذكية. تقيس نبضات القلب، الأكسجين، ومستويات النوم.',
                'images' => [
                    'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'tech',
                'name_en' => 'Quantum Key Minimalist Mechanical Keyboard',
                'name_ar' => 'لوحة مفاتيح أورا كوانتم الميكانيكية المصنوعة من الألومنيوم',
                'slug' => 'quantum-key-minimalist-mechanical-keyboard',
                'price' => 890.00,
                'compare_at_price' => 990.00,
                'sku' => 'AUR-TCH-002',
                'stock_quantity' => 10,
                'is_featured' => false,
                'rating' => 4.96,
                'reviews_count' => 17,
                'short_description_en' => 'CNC machined aircraft aluminum chassis, brass plate, custom lubricated switches.',
                'short_description_ar' => 'هيكل ألومنيوم طائرات مع مفاتيح ميكانيكية مخصصة مجهزة بخاصية كتم الصوت.',
                'description_en' => 'Precision crafted for tactile bliss. Double-shot PBT keycaps, hot-swappable PCB, per-key RGB backlighting, and Bluetooth multi-device pairing.',
                'description_ar' => 'صُنعت بدقة فائقة لتمنحك أفضل شعور بالكتابة. أغطية مفاتيح PBT، إضاءة RGB مخصصة، وإمكانية التوصيل المتعدد.',
                'images' => [
                    'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            // Accessories
            [
                'category_slug' => 'accessories',
                'name_en' => 'Monaco Leather Weekend Duffle Bag',
                'name_ar' => 'حقيبة موناكو السفر الجلدية الفاخرة',
                'slug' => 'monaco-leather-weekend-duffle-bag',
                'price' => 2100.00,
                'compare_at_price' => 2400.00,
                'sku' => 'AUR-ACC-001',
                'stock_quantity' => 5,
                'is_featured' => true,
                'rating' => 4.97,
                'reviews_count' => 22,
                'short_description_en' => 'Hand-vegetable tanned Florentine full-grain calfskin with brushed brass hardware.',
                'short_description_ar' => 'جلد بقر إيطالي فاخر مدبوغ نباتياً يدوياً مع إكسسوارات من النحاس الأصفر.',
                'description_en' => 'Designed for elegant journeys. Spacious main compartment lined with microsuede, dedicated shoe garage, and reinforced padded shoulder strap.',
                'description_ar' => 'صُممت للأسفار الأنيقة. مساحة رئيسية واسعة مبطنة بالمخمل ومكان مخصص للأحذية ومقبض محكم بالجلد الطبيعي.',
                'images' => [
                    'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'accessories',
                'name_en' => 'AURA Heritage Leather Bifold Slim Wallet',
                'name_ar' => 'محفظة أورا هيريتيج الجلدية النحيفة',
                'slug' => 'aura-heritage-leather-bifold-wallet',
                'price' => 420.00,
                'compare_at_price' => 500.00,
                'sku' => 'AUR-ACC-002',
                'stock_quantity' => 25,
                'is_featured' => false,
                'rating' => 4.80,
                'reviews_count' => 15,
                'short_description_en' => 'RFID-blocking internal lining, holds 8 cards and cash, ultra slim profile.',
                'short_description_ar' => 'حماية بطاقات RFID ضد السرقة، تتسع لـ 8 بطاقات مع النقود بتصميم نحيف للغاية.',
                'description_en' => 'Crafted from smooth Tuscan leather that develops a beautiful patina over time. Embedded with RFID protection for electronic security.',
                'description_ar' => 'صنعت من الجلد التوسكاني الأملس الذي يزداد جمالاً مع مرور الزمن. مزودة ببطانة حماية للبطاقات الالكترونية.',
                'images' => [
                    'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop&q=80',
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $catSlug = $pData['category_slug'];
            unset($pData['category_slug']);
            $pData['category_id'] = $categories[$catSlug]->id;
            Product::create($pData);
        }

        // 5. Create Coupons
        Coupon::create([
            'code' => 'AURA10',
            'type' => 'percentage',
            'value' => 10.00,
            'min_order_amount' => 100.00,
            'usage_limit' => 500,
            'used_count' => 24,
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'LUXURY50',
            'type' => 'fixed',
            'value' => 50.00,
            'min_order_amount' => 300.00,
            'usage_limit' => 200,
            'used_count' => 12,
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'WELCOME',
            'type' => 'percentage',
            'value' => 15.00,
            'min_order_amount' => 200.00,
            'usage_limit' => 1000,
            'used_count' => 88,
            'is_active' => true,
        ]);

        // 6. Create Demo Orders for Analytics
        $demoProducts = Product::all();
        
        $statuses = ['delivered', 'delivered', 'shipped', 'processing', 'pending'];
        
        for ($i = 1; $i <= 10; $i++) {
            $randomProduct1 = $demoProducts->random();
            $randomProduct2 = $demoProducts->random();

            $subtotal = $randomProduct1->price + $randomProduct2->price;
            $tax = $subtotal * 0.15;
            $shipping = 35.00;
            $total = $subtotal + $tax + $shipping;

            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $status === 'cancelled' ? 'failed' : 'paid';

            $order = Order::create([
                'order_number' => 'AURA-DEMO-' . (1000 + $i),
                'user_id' => $customer->id,
                'customer_name' => 'Sultan Al-Mansoor',
                'customer_email' => 'customer@aura.com',
                'customer_phone' => '+966 55 123 4567',
                'shipping_address' => [
                    'full_name' => 'Sultan Al-Mansoor',
                    'phone' => '+966 55 123 4567',
                    'address_line_1' => 'King Fahd Road, Olaya District',
                    'city' => 'Riyadh',
                    'state' => 'Riyadh Province',
                    'country' => 'Saudi Arabia',
                ],
                'shipping_method' => 'express',
                'payment_method' => 'card',
                'payment_status' => $paymentStatus,
                'status' => $status,
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => $tax,
                'shipping_fee' => $shipping,
                'total' => $total,
                'tracking_number' => 'TRK-' . (900000 + $i),
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $randomProduct1->id,
                'product_name_en' => $randomProduct1->name_en,
                'product_name_ar' => $randomProduct1->name_ar,
                'price' => $randomProduct1->price,
                'quantity' => 1,
                'total' => $randomProduct1->price,
                'image' => $randomProduct1->main_image,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $randomProduct2->id,
                'product_name_en' => $randomProduct2->name_en,
                'product_name_ar' => $randomProduct2->name_ar,
                'price' => $randomProduct2->price,
                'quantity' => 1,
                'total' => $randomProduct2->price,
                'image' => $randomProduct2->main_image,
            ]);
        }
    }
}
