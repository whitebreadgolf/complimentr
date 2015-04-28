<?php namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use Validator;

Class Comment extends Model{

	public function postImageComment(Request $request){
		$name = "Anonymous";
		if(\Auth::check()){
			$user = \Auth::user();
			$name = $user->name;
		}

		$type_id = 2;
		$comment = $request->input('comment');
		$postId = $request->input('post_id');
		$date = new \DateTime;

		//update comment number in post
		$img = new ImageHistory;
		$img->incramentCommentForId($postId);

		$id = DB::table('comments')->insertGetId(array(		
    		'name' => $name,
    		'post_id' => $postId,
    		'type_id' => $type_id, 
    		'comment' => $comment,
    		'time_created' => $date
    	));

    	return array('name' => $name, 'comment' => $comment, 'time_created' => $date->format('Y-m-d H:i:s'));
	}

	public function postComplimentComment(Request $request){
		$name = "Anonymous";
		if(\Auth::check()){
			$user = \Auth::user();
			$name = $user->name;
		}

		$type_id = 1;
		$comment = $request->input('comment');
		$postId = $request->input('post_id');
		$date = new \DateTime;

		//update comment number in post
		$comp = new ComplimentHistory;
		$comp->incramentCommentForId($postId);

		$id = DB::table('comments')->insertGetId(array(		
    		'name' => $name,
    		'post_id' => $postId,
    		'type_id' => $type_id, 
    		'comment' => $comment,
    		'time_created' => $date
    	));

    	return array('name' => $name, 'comment' => $comment, 'time_created' => $date->format('Y-m-d H:i:s'));
	}

	public function getImageCommentsByPostId($postId){
		$query = DB::table('comments')
		->join('types', 'types.id', '=', 'comments.type_id')
		->where('type', '=', 'image')
		->where('post_id', '=', $postId);

		return $query->get();
	}

	public function getComplimentCommentsByPostId($postId){
		$query = DB::table('comments')
		->join('types', 'types.id', '=', 'comments.type_id')
		->where('type', '=', 'compliment')
		->where('post_id', '=', $postId);

		return $query->get();
	}

	public static function validate($input)
	{
		return Validator::make($input, [
			'comment' => 'required|min:1|max:255'
		]);
	}
} 

 ?>