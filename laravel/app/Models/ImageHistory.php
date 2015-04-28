<?php namespace App\Models;

use Illuminate\Http\Request;
use DB;
use Validator;

use App\User;

class ImageHistory{

	public function getAllImagePosts(){
		$query = DB::table('image_history')
		->join('images','images.id','=','image_history.image_id')
		->join('users','users.id','=','image_history.user_id')
		->join('recievers','recievers.id','=','image_history.reciever_id')
		->orderBy('time_created', 'desc')
		->select('image_history.id', 'time_created', 'name', 'reciever', 'image', 'num_comments', 'message');

		return $query->get();
	}

	public function getImagePostByUser(){
		$user = \Auth::user();

		$query = DB::table('image_history')
		->join('images','images.id','=','image_history.image_id')
		->join('users','users.id','=','image_history.user_id')
		->join('recievers','recievers.id','=','image_history.reciever_id')
		->where('user_id','=', $user->id)
		->orderBy('time_created', 'desc')
		->select('image_history.id', 'time_created', 'name', 'reciever', 'image', 'num_comments', 'message');

		return $query->get();
	}

	public function getImagePostByReciever(){
		$user = \Auth::user();

		$query = DB::table('image_history')
		->join('images','images.id','=','image_history.image_id')
		->join('users','users.id','=','image_history.user_id')
		->join('recievers','recievers.id','=','image_history.reciever_id')
		->where('reciever','=', $user->name)
		->orderBy('time_created', 'desc')
		->select('image_history.id', 'time_created', 'name', 'reciever', 'image', 'num_comments', 'message');
		
		return $query->get();
	}

	public function getImagePostById($id){
		$query = DB::table('image_history')
		->join('images','images.id','=','image_history.image_id')
		->join('users','users.id','=','image_history.user_id')
		->join('recievers','recievers.id','=','image_history.reciever_id')
		->where('image_history.id','=', $id)
		->select('image_history.id', 'time_created', 'name', 'reciever', 'image', 'num_comments', 'message');

		return $query->get();
	}

	public function incramentCommentForId($id){
		//previous number of comments
		$numComments = DB::table('image_history')->where('id', $id)->pluck('num_comments');
		$numComments++;

		DB::table('image_history')
        ->where('id', $id)
        ->update(array('num_comments' => $numComments));
	}

	public function postImage(Request $request){
		//get random user to send to
		$allUsers = User::all();
		$max = sizeof($allUsers);
		$rand = rand(0, $max-1);

		//rand user
		$randUser = $allUsers[$rand];
		$userId = \Auth::user()->id;
		$imageUrl = $request->input('imageUrl');
		$message = $request->input('message');
		$date = new \DateTime;

		//put image into image table
		$imageId = DB::table('images')->insertGetId(array(
    		'image' => $imageUrl 
    	));

		//put reciever into reciever table
		$recieverId = DB::table('recievers')->where('reciever','=', $randUser->name)->pluck('id');

		if(!$recieverId){
			$recieverId = DB::table('recievers')->insertGetId(array(
    			'reciever' => $randUser->name 
    		));
		}
		

		$id = DB::table('image_history')->insertGetId(array(
    		'user_id' => $userId, 
    		'reciever_id' => $recieverId,
    		'image_id' => $imageId,
    		'num_comments' => 0,
    		'message' => $message,
    		'time_created' => $date
    	));

    	return $randUser->name;
	}

	public static function validate($input)
	{
		return Validator::make($input, [
			'imageUrl' => 'required',
			'message' => 'required|min:1|max:255'
		]);
	}
}
