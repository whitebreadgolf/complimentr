<?php namespace App\Models;

use DB;
use Validator;

class Compliment{

	public function getRandomCompliment(){
		$query = DB::table('compliments')->get();

		$max = sizeof($query);
		$rand = rand(0, $max);

		return $query[$rand];
	}
}

 ?>