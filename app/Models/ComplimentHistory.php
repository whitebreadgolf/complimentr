<?php namespace App\Models;

use Illuminate\Http\Request;
use DB;
use Validator;

use App\User;

class ComplimentHistory{

	public function getAllComplimentPosts(){
		$query = DB::table('compliment_history')
		->join('compliments','compliments.id','=','compliment_history.compliment_id')
		->join('users','users.id','=','compliment_history.user_id')
		->join('recievers','recievers.id','=','compliment_history.reciever_id')
		->orderBy('time_created', 'desc')
		->select('compliment_history.id', 'time_created', 'name', 'reciever', 'compliment', 'num_comments', 'message');

		return $query->get();
	}

	public function getComplimentPostByUser(){
		$user = \Auth::user();

		$query = DB::table('compliment_history')
		->join('compliments','compliments.id','=','compliment_history.compliment_id')
		->join('users','users.id','=','compliment_history.user_id')
		->join('recievers','recievers.id','=','compliment_history.reciever_id')
		->where('user_id','=', $user->id)
		->orderBy('time_created', 'desc')
		->select('compliment_history.id', 'time_created', 'name', 'reciever', 'compliment', 'num_comments', 'message');

		return $query->get();
	}	

	public function getComplimentPostByReciever(){
		$user = \Auth::user();

		$query = DB::table('compliment_history')
		->join('compliments','compliments.id','=','compliment_history.compliment_id')
		->join('users','users.id','=','compliment_history.user_id')
		->join('recievers','recievers.id','=','compliment_history.reciever_id')
		->where('reciever','=', $user->name)
		->orderBy('time_created', 'desc')
		->select('compliment_history.id', 'time_created', 'name', 'reciever', 'compliment', 'num_comments', 'message');

		return $query->get();
	}	

	public function getComplimentPostById($id){
		$query = DB::table('compliment_history')
		->join('compliments','compliments.id','=','compliment_history.compliment_id')
		->join('users','users.id','=','compliment_history.user_id')
		->join('recievers','recievers.id','=','compliment_history.reciever_id')
		->where('compliment_history.id','=', $id)
		->select('compliment_history.id', 'time_created', 'name', 'reciever', 'compliment', 'num_comments', 'message');

		return $query->get();
	}

	public function incramentCommentForId($id){
		//previous number of comments
		$numComments = DB::table('compliment_history')->where('id', $id)->pluck('num_comments');
		$numComments++;

		DB::table('compliment_history')
        ->where('id', $id)
        ->update(array('num_comments' => $numComments));
	}

	public function postCompliment(Request $request){

		//get random user to send to
		$allUsers = User::all();
		$max = sizeof($allUsers);
		$rand = rand(0, $max-1);

		//rand user
		$randUser = $allUsers[$rand];
		$userId = \Auth::user()->id;
		$complimentId = $request->input('compliment_id');
		$message = $request->input('message');
		$date = new \DateTime;


		//put reciever into reciever table if it isn't there
		$recieverId = DB::table('recievers')->where('reciever','=', $randUser->name)->pluck('id');

		if(!$recieverId){
			$recieverId = DB::table('recievers')->insertGetId(array(
    			'reciever' => $randUser->name 
    		));
		}
		

		$id = DB::table('compliment_history')->insertGetId(array(
    		'user_id' => $userId, 
    		'reciever_id' => $recieverId,
    		'compliment_id' => $complimentId,
    		'num_comments' => 0,
    		'message' => $message,
    		'time_created' => $date
    	));

    	return $randUser->name;
	}

	public static function validate($input)
	{
		return Validator::make($input, [
			'compliment_id' => 'required|integer',
			'message' => 'required|min:1|max:255'
		]);
	}
}
