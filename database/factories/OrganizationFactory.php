<?php   

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganisationFactory extends Factory
{
    protected $model = Organisation::class;

    public function definition()
    {
        return [
            'orgId' => (string) Str::uuid(),
            'name' => $this->faker->company . ' Organisation',
            'description' => $this->faker->paragraph,
        ];
    }
}
