<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ProjectsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Project::create([
				'title' => 'Sample Project',
				'description' => 'Test project',
				'owner_id' => '1',
				'status_id' => '1'
			]);
		}
	}

}