<?php

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $num1 = 2;
        $num2 = 5;
        for ($i1 = 0; $i1 < $num1; $i1++) {
            $c1 = new Category;
            $c1->title = $this->faker->realText(30, 1);
            $c1->type = Category::TYPE_COURSE;
            $c1->save();

            for ($i2 = 0; $i2 < $num2; $i2++) {
                $c2 = new Category;
                $c2->parent_id = $c1->id;
                $c2->title = $this->faker->realText(30, 1);
                $c2->save();
            }
        }

        for ($i1 = 0; $i1 < $num1; $i1++) {
            $c1 = new Category;
            $c1->title = $this->faker->realText(30, 1);
            $c1->type = Category::TYPE_POST;
            $c1->save();

            for ($i2 = 0; $i2 < $num2; $i2++) {
                $c2 = new Category;
                $c2->parent_id = $c1->id;
                $c2->title = $this->faker->realText(30, 1);
                $c2->save();
            }
        }
    }
}
