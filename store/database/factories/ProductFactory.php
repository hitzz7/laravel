// File: database/factories/ProductFactory.php
<?php
use Faker\Generator as Faker;
use App\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        // You can add more attributes as needed
    ];
});
