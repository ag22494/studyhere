<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();


		$this->call('UserTableSeeder');
			$this->call('UniversityTableSeeder');
				$this->call('CoursesTableSeeder');
				$this->call('SubjectTableSeeder');
			$this->call('TopicTableSeeder');
				$this->call('SubTopicTableSeeder');


	}

}
