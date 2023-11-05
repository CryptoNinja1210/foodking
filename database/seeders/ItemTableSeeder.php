<?php

namespace Database\Seeders;

use App\Enums\Ask;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Str;
use App\Enums\ItemType;
use App\Enums\Status;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public array $items = [
        [
            'name'        => 'Chicken Dumplings',
            'category'    => 1,
            'tax_id'      => 2,
            'price'       => '2.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'With a side of fried rice or supreme soy noodles, and steamed chinese greens with oyster sauce'
        ],
        [
            'name'        => 'Egg Roll',
            'category'    => 1,
            'tax_id'      => 2,
            'price'       => '1.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'Egg roll wrappers filled with a mixture of vegetables and beef or chicken, and fried to a crispy golden brown.'
        ],
        [
            'name'        => 'Fried Cheese wonton',
            'category'    => 1,
            'tax_id'      => 2,
            'price'       => '2.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'Crispy fried cream cheese wontons are filled with cream cheese, lemon and garlic pepper, paprika & fried to golden perfection!'
        ],
        [
            'name'        => 'Vegetable Dumplings',
            'category'    => 1,
            'tax_id'      => 2,
            'price'       => '2.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains milk and products thereof (including lactose).',
            'description' => 'With a side of fried rice or supreme soy noodles, and steamed chinese greens with oyster sauce'
        ],
        [
            'name'        => 'Vegetable Roll',
            'category'    => 1,
            'tax_id'      => 2,
            'price'       => '1.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains milk and products thereof (including lactose).',
            'description' => 'Pastry sheet filled with a mixture of vegetables and fried to a crispy golden brown.'
        ],
        [
            'name'        => 'American BBQ Double',
            'category'    => 2,
            'tax_id'      => 1,
            'price'       => '5.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose4. iii). Wheat.',
            'description' => 'Two flame-grilled whopper patty, topped with american cheese, fresh slices of tomato and crisp lettuce, and finished with a zesty BBQ sauce and golden crispy onions.'
        ],
        [
            'name'        => 'American BBQ Single',
            'category'    => 2,
            'tax_id'      => 1,
            'price'       => '4.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose4. iii). Wheat.',
            'description' => 'A flame-grilled whopper patty, topped with American cheese, fresh slices of tomato and crisp lettuce, and finished with a zesty BBQ sauce and golden crispy onions.'
        ],
        [
            'name'        => 'Bacon Double Cheeseburger',
            'category'    => 2,
            'tax_id'      => 2,
            'price'       => '3.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose4. iii). Wheat.',
            'description' => 'Two signature flame-grilled beef patties topped with smoked bacon and two layers of melted American cheese on a toasted sesame seed bun.'
        ],
        [
            'name'        => 'Cheeseburger',
            'category'    => 2,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose4. iii). Wheat.',
            'description' => 'Two layers of melted american cheese on a toasted sesame seed bun.'
        ],
        [
            'name'        => 'Peppercorn Anger',
            'category'    => 2,
            'tax_id'      => 1,
            'price'       => '2.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose4. iii). Wheat.',
            'description' => 'Beef flame-grilled to perfection, topped with sizzling strips of Bacon, sweet caramelised onions, fresh rocket and finished with our secret peppercorn mayo sauce. It could only be our gourmet kings.'
        ],
        [
            'name'        => 'Whopper',
            'category'    => 2,
            'tax_id'      => 1,
            'price'       => '2.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose4. iii). Wheat.',
            'description' => 'A flame-grilled beef patty, topped with tomatoes, fresh cut lettuce, mayo, pickles, a swirl of ketchup, and sliced onions on a soft sesame seed bun.'
        ],
        [
            'name'        => 'Plant Based Bacon',
            'category'    => 3,
            'tax_id'      => 2,
            'price'       => '3.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains nuts and products thereof. ii). Contains milk and products thereof (including lactose). iii). cashews.',
            'description' => 'A flame-grilled plant-based patty in our classic bun, layered with slices of smooth vegan cheese and strips of vegan bakon, topped with egg-free mayo and ketchup. It\'s big, plant-based flavor.'
        ],
        [
            'name'        => 'Plant Based Whopper',
            'category'    => 3,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains nuts and products thereof. ii). Contains milk and products thereof (including lactose). iii). cashews.',
            'description' => 'A flame-grilled plant-based burger, topped with tomatoes, fresh cut lettuce, vegan mayo, pickles, a swirl of ketchup, and sliced onions on a soft sesame seed bun.'
        ],
        [
            'name'        => 'Vegan Hum-burger with Cheese',
            'category'    => 3,
            'tax_id'      => 1,
            'price'       => '2.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains nuts and products thereof. ii). Contains milk and products thereof (including lactose). iii). cashews.',
            'description' => 'A crispy vegan patty topped vegan cheese, vegan bakon, iceberg lettuce, vegan mayo and crowned with a toasted sesame seed bun.'
        ],
        [
            'name'        => 'Vegan Royale',
            'category'    => 3,
            'tax_id'      => 2,
            'price'       => '3.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains nuts and products thereof. ii). Contains milk and products thereof (including lactose). iii). cashews.',
            'description' => 'A crispy vegan patty topped with iceberg lettuce, vegan mayo and crowned with a toasted sesame seed bun.'
        ],
        [
            'name'        => 'BBQ Chicken',
            'category'    => 4,
            'tax_id'      => 1,
            'price'       => '4.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'Sweet and tangy BBQ Chicken Sandwiches made with juicy slow cooker BBQ chicken and crisp coleslaw on toasted brioche buns.'
        ],
        [
            'name'        => 'BBQ Pulled Pork',
            'category'    => 4,
            'tax_id'      => 1,
            'price'       => '4.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'Sweet and tangy BBQ Pork sandwiches made with juicy slow cooker BBQ pork and crisp coleslaw on toasted brioche buns.'
        ],
        [
            'name'        => 'Chicken Mushroom',
            'category'    => 4,
            'tax_id'      => 2,
            'price'       => '3.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'Cheese sandwich with a chicken, mushroom and capsicum stuffing, grilled with butter to make it crispy.'
        ],
        [
            'name'        => 'Plain Grilled Chicken',
            'category'    => 4,
            'tax_id'      => 1,
            'price'       => '4.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'This grilled chicken sandwich is simple to make with flavorsome marinated chicken, lettuce, tomato, and mayo. Crispy golden pan-fried bread makes it extra special!'
        ],
        [
            'name'        => 'Steak Sandwich',
            'category'    => 4,
            'tax_id'      => 1,
            'price'       => '3.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'A juicy steak sandwich, piled high with tender slices of steak, tomato, lettuce, caramelized onion, garlic aioli and mustard.'
        ],
        [
            'name'        => 'Hentai Chicken',
            'category'    => 5,
            'tax_id'      => 1,
            'price'       => '4.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'Creamy, tomato and herbed chicken with a sprinkle of fiery heat.'
        ],
        [
            'name'        => 'Kung Pao Chicken',
            'category'    => 5,
            'tax_id'      => 1,
            'price'       => '4.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'kung pao chicken is a highly addictive stir-fried chicken with the perfect combination of salty, sweet and spicy flavour!'
        ],
        [
            'name'        => 'Sesame Chicken',
            'category'    => 5,
            'tax_id'      => 2,
            'price'       => '3.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'This sesame chicken is crispy chicken pieces tossed in a sweet and savory honey sesame sauce.'
        ],
        [
            'name'        => 'Sweet & Sour Chicken',
            'category'    => 5,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'Sweet and sour chicken with crispy chicken, pineapple and bell peppers just like your favorite takeout place without the food coloring.'
        ],
        [
            'name'        => 'Yemete Kudasai Chicken',
            'category'    => 5,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Contains eggs and products thereof. iii). Wheat.',
            'description' => 'Chicken breasts are simply baked to perfection, topped with fresh mozzarella and juicy, garlicky fresh chopped tomatoes.'
        ],
        [
            'name'        => 'Beef with Broccoli',
            'category'    => 6,
            'tax_id'      => 2,
            'price'       => '4.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). With nitrite curing salt. ii). With nitrate. iii). With nitrite curing salt and nitrate.',
            'description' => 'Crisp green broccoli and sweet onions, with beef, glistens with the best-tasting brown sauce.'
        ],
        [
            'name'        => 'Beef with Mix Vegetables',
            'category'    => 6,
            'tax_id'      => 1,
            'price'       => '3.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). With nitrite curing salt. ii). With nitrate. iii). With nitrite curing salt and nitrate.',
            'description' => 'Tender slices of steak with crisp snow peas, carrots, and broccoli, sesame beef stir fry is a quick and nutritious meal, with a pleasantly spicy ginger sesame sauce.'
        ],
        [
            'name'        => 'Pepper Steak with Onions',
            'category'    => 6,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). With nitrite curing salt. ii). With nitrate. iii). With nitrite curing salt and nitrate.',
            'description' => 'Pepper Steak is tender and juicy slices of Steak mixed with peppers and lots of onion in a silky and flavorful sauce.'
        ],
        [
            'name'        => 'Szechuan Beef',
            'category'    => 6,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). With nitrite curing salt. ii). With nitrate. iii). With nitrite curing salt and nitrate.',
            'description' => 'Beef enveloped in dynamic spicy sauce made from layers of chilis, garlic, ginger and of course Szechuan peppercorns with just a touch of sweetness.'
        ],
        [
            'name'        => 'Kung Pao Squid',
            'category'    => 7,
            'tax_id'      => 2,
            'price'       => '5.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains fish and products thereof. ii). Contains sulphur dioxide and sulphites. iii). Contains soybeans and products thereof. iv). Contains milk and products thereof (including lactose). v). Contains cereals and products thereof containing gluten. vi). wheat. vii). Contains eggs and products thereof. viii). Contains sesame seeds and products thereof.',
            'description' => 'Kung Pao squid is studded with crunchy roasted peanuts, spicy chilies, and tongue numbing sichuan peppercorns in a slightly sweet and savory sauce.'
        ],
        [
            'name'        => 'Salmon with Mix Vegetables',
            'category'    => 7,
            'tax_id'      => 1,
            'price'       => '3.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains fish and products thereof. ii). Contains sulphur dioxide and sulphites. iii). Contains soybeans and products thereof. iv). Contains milk and products thereof (including lactose). v). Contains cereals and products thereof containing gluten. vi). wheat. vii). Contains eggs and products thereof. viii). Contains sesame seeds and products thereof.',
            'description' => 'Tender slices of salmon with crisp snow peas, carrots, and broccoli, salmon stir fry is a quick and nutritious meal, with a pleasantly spicy ginger sesame sauce.'
        ],
        [
            'name'        => 'Shrimp with Broccoli',
            'category'    => 7,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains crustaceans and products thereof. ii). Contains sesame seeds and products thereof.',
            'description' => 'Crisp green broccoli and sweet onions, with shrimp, glistens with the best-tasting brown sauce.'
        ],
        [
            'name'        => 'Szechuan Shrimp',
            'category'    => 7,
            'tax_id'      => 2,
            'price'       => '4.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains crustaceans and products thereof. ii). Contains sesame seeds and products thereof.',
            'description' => 'Spicy, tangy Szechuan sauce packs a ton of strong flavors on top of tender baby shrimp.'
        ],
        [
            'name'        => 'Classic Caesar Salad',
            'category'    => 8,
            'tax_id'      => 1,
            'price'       => '3.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains nuts and products thereof. ii). Contains milk and products thereof (including lactose). iii). cashews.',
            'description' => 'Classic Caesar Salad recipe is a crisp, crunchy, homemade version tossed with a traditional Caesar salad dressing and garlic croutons.'
        ],
        [
            'name'        => 'Fresh Tuna Salad',
            'category'    => 8,
            'tax_id'      => 1,
            'price'       => '4.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains fish and products thereof. ii). Contains sulphur dioxide and sulphites. iii). Contains soybeans and products thereof. iv). Contains milk and products thereof (including lactose). v). Contains cereals and products thereof containing gluten. vi). wheat. vii). Contains eggs and products thereof. viii). Contains sesame seeds and products thereof.',
            'description' => 'Fresh tuna, crisp celery, red onion, radishes, and fresh herbs, tossed in a creamy lemony dressing.'
        ],
        [
            'name'        => 'Mix Vegetables Salad',
            'category'    => 8,
            'tax_id'      => 2,
            'price'       => '2.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains nuts and products thereof. ii). Contains milk and products thereof (including lactose). iii). cashews.',
            'description' => 'A bow full of cabbage, tomatoes and carrots tossed with a delicious dressing of yogurt, honey, salt and pepper.'
        ],
        [
            'name'        => 'Poached Pear Salad',
            'category'    => 8,
            'tax_id'      => 1,
            'price'       => '3.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains nuts and products thereof. ii). Contains milk and products thereof (including lactose). iii). cashews.',
            'description' => 'Poached pear and goat cheese salad made with mixed greens, poached pears and herbed goat cheese with pear shallot vinaigrette.'
        ],
        [
            'name'        => 'Roasted Salmon Salad',
            'category'    => 8,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains fish and products thereof. ii). Contains sulphur dioxide and sulphites. iii). Contains soybeans and products thereof. iv). Contains milk and products thereof (including lactose). v). Contains cereals and products thereof containing gluten. vi). wheat. vii). Contains eggs and products thereof. viii). Contains sesame seeds and products thereof.',
            'description' => 'Flaky baked salmon, crisp celery, red onion, radishes, and fresh herbs, tossed in a creamy lemony dressing.'
        ],
        [
            'name'        => 'Chicken Noodles Soup',
            'category'    => 9,
            'tax_id'      => 2,
            'price'       => '3.0',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose). iii). Contains celery and products thereof. iv). Contains eggs and products thereof.',
            'description' => 'This chicken noodle soup is like a warm hug from the inside out. Loaded with tender chicken, hearty vegetables, and comforting noodles, it\'s the ultimate comfort food on a chilly day.'
        ],
        [
            'name'        => 'Egg Drop Soup',
            'category'    => 9,
            'tax_id'      => 1,
            'price'       => '2.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose). iii). Contains celery and products thereof. iv). Contains eggs and products thereof.',
            'description' => 'Egg Drop Soup is a warm, thickened broth, with rich flavors of chicken and beautiful egg ribbons throughout.'
        ],
        [
            'name'        => 'Hot & Sour Soup',
            'category'    => 9,
            'tax_id'      => 1,
            'price'       => '2.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose). iii). Contains celery and products thereof. iv). Contains eggs and products thereof.',
            'description' => 'Filled with mushrooms, tofu and silky egg ribbons, hot and sour soup is thickened with corn flour/cornstarch so the broth is beautifully glossy.'
        ],
        [
            'name'        => 'Wonton Soup',
            'category'    => 9,
            'tax_id'      => 2,
            'price'       => '2.5',
            'type'        => ItemType::NON_VEG,
            'featured'    => Ask::YES,
            'caution'     => 'Substances or products causing allergies - i). Contains cereals and products thereof containing gluten. ii). Contains milk and products thereof (including lactose). iii). Contains celery and products thereof. iv). Contains eggs and products thereof.',
            'description' => 'Wonton soup is a simple, light, Chinese classic with pork-filled dumplings in seasoned chicken broth. Whether in soup or fried, wontons always add delicious, hearty flavor to any dish!'
        ],
        [
            'name'        => 'Baked Potato',
            'category'    => 10,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'The outside is brown and crisp, coated in a crust of sea salt.'
        ],
        [
            'name'        => 'French Fries',
            'category'    => 10,
            'tax_id'      => 1,
            'price'       => '1.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'Serve with mayo and green chili sauce.'
        ],
        [
            'name'        => 'Homemade Mashed Potato',
            'category'    => 10,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'Made with idaho potatoes, butter, and optional garlic.'
        ],
        [
            'name'        => 'Onion Rings',
            'category'    => 10,
            'tax_id'      => 2,
            'price'       => '1.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'Serve with mayo and green chili sauce.'
        ],
        [
            'name'        => 'Potato Pancakes',
            'category'    => 10,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains cereals and products thereof containing gluten. ii). Wheat.',
            'description' => 'Shallow-fried pancakes of grated potato, flour or matzo meal, and a binder such as egg or applesauce.'
        ],
        [
            'name'        => 'Cappuccino',
            'category'    => 11,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains milk and products thereof (including lactose).',
            'description' => 'Dark, rich espresso lies in wait under a smoothed and stretched layer of thick milk foam.'
        ],
        [
            'name'        => 'Chai Latte',
            'category'    => 11,
            'tax_id'      => 1,
            'price'       => '1.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains milk and products thereof (including lactose).',
            'description' => 'Black tea infused with cinnamon, clove and other warming spices is combined with steamed milk and topped with foam for the perfect balance of sweet and spicy.'
        ],
        [
            'name'        => 'Espresso',
            'category'    => 11,
            'tax_id'      => 2,
            'price'       => '1.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => '',
            'description' => 'Smooth signature espresso roast with rich flavor and caramel sweetness.'
        ],
        [
            'name'        => 'Homemade Lemonade',
            'category'    => 11,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => '',
            'description' => 'Perfectly sweet and makes the best refreshing summer drink.'
        ],
        [
            'name'        => 'Iced Coffee',
            'category'    => 11,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => 'LMIV - Allergen - i). Contains milk and products thereof (including lactose).',
            'description' => 'Cold foam contrasts with dark, smooth cold brew, yielding an inviting aroma with lush infused cold foam.'
        ],
        [
            'name'        => 'Mojito',
            'category'    => 11,
            'tax_id'      => 1,
            'price'       => '2.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => '',
            'description' => 'Mixed drink of mint, lime, sugar, and club soda.'
        ],
        [
            'name'        => 'Soda (Bottle)',
            'category'    => 11,
            'tax_id'      => 1,
            'price'       => '1.0',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => '',
            'description' => '0.5 ltr of soda bottle.'
        ],
        [
            'name'        => 'Soda (Can)',
            'category'    => 11,
            'tax_id'      => 1,
            'price'       => '1.5',
            'type'        => ItemType::VEG,
            'featured'    => Ask::YES,
            'caution'     => '',
            'description' => '0.5 ltr of soda can.'
        ],
    ];

    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->items as $item) {
                $itemObject = Item::create([
                    'name'             => $item['name'],
                    'slug'             => Str::slug($item['name']),
                    'item_category_id' => $item['category'],
                    'price'            => $item['price'],
                    'status'           => Status::ACTIVE,
                    'tax_id'           => $item['tax_id'],
                    'item_type'        => $item['type'],
                    'order'            => 1,
                    'is_featured'      => $item['featured'],
                    'caution'          => $item['caution'],
                    'description'      => $item['description']
                ]);
                if (file_exists(
                    public_path('/images/seeder/item/' . strtolower(str_replace(' ', '_', $item['name'])) . '.png')
                )) {
                    $itemObject->addMedia(
                        public_path('/images/seeder/item/' . strtolower(str_replace(' ', '_', $item['name'])) . '.png')
                    )->preservingOriginal()->toMediaCollection('item');
                }
            }
        }
    }
}
