<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Compliment;
use App\Models\Image;
use App\Models\ComplimentHistory;
use App\Models\ImageHistory;
use App\Models\Comment;
use App\User;

use App\Services\Instagram;

use DB;
use Pusher;

class HomeController extends Controller {

	public function logout(){
		\Auth::logout();
		return redirect('/')->with('success', 'Logout Succesful');
	}

	public function login(Request $request){

		$email = $request->input('email'); 
		$password = $request->input('password');

		$user = DB::table('users')->where('email','=',$email)->get();

		if(sizeof($user) > 0 && \Hash::check($password, $user[0]->password)){
			\Auth::loginUsingId($user[0]->id);
			return redirect()->back();
		}
		return redirect()->back()->withErrors('incorrect');
	}

	public function createUser(Request $request){
		$user = new User;

		$user->name = $request->input('name');
		$user->password = \Hash::make($request->input('password'));
		$user->email = $request->input('email');
		$user->save();

		\Auth::loginUsingId($user->id);

		return redirect()->back();
	}

	public function getRandomCompliment(Request $request){

		$user = \Auth::user();
		$compliment = (new Compliment())->getRandomCompliment();

	  	return view('giveComp',[
			'compliment' => $compliment,
			'name' => $user->name
		]);	
	}

	public function getRandPic(Request $request){

		$user = \Auth::user();
		$instagram = (new Instagram())->getPic(false);

		return view('givePic',[
			'image' => $instagram,
			'name' => $user->name
		]);	
	}

	public function postRandomCompliment(Request $request){
		$validator = ComplimentHistory::validate($request->all());

		if($validator->passes()){
			$complimentHistory = new ComplimentHistory;
			$recieverId = $complimentHistory->postCompliment($request);
			return redirect()->back()->with('success', 'Compliment sent to ' . $recieverId);
		}
		
		return redirect()->back()->withErrors($validator)->withInput();	
	}

	public function postRandPic(Request $request){
		$validator = ImageHistory::validate($request->all());

		if($validator->passes()){
			$imageHistory = new ImageHistory;
			$recieverId = $imageHistory->postImage($request);
			return redirect()->back()->with('success', 'Compliment sent to ' . $recieverId);
		}
		
		return redirect()->back()->withErrors($validator)->withInput();	
	}

	public function getGlobalFeed(){

		$allCompliments = (new ComplimentHistory())->getAllComplimentPosts();
		$allImages = (new ImageHistory())->getAllImagePosts();

		//need to sort 
		$feed = $this->sortFeed($allCompliments, $allImages);

		if(\Auth::check()){
			$user = \Auth::user();
			return view('home',[
				'name' => $user->name,
				'avatar' => $user->avatar,
				'allCompliments' => $allCompliments,
				'allImages' => $allImages,
				'feed' => $feed
			]);
		}
		else{
			return view('home',[
				'feed' => $feed
			]);
		}			
	}

	public function getMySentFeed(){
		if(\Auth::check()){
			$compliments = (new ComplimentHistory())->getComplimentPostByUser();
			$images = (new ImageHistory())->getImagePostByUser();

			$feed = $this->sortFeed($compliments, $images);

			$user = \Auth::user();
			return view('home',[
				'name' => $user->name,
				'avatar' => $user->avatar,
				'feed' => $feed
			]);
		}
		else{
			return view('home',[
				
			]);
		}	
	}	

	public function getMyRecievedFeed(){
		if(\Auth::check()){
			$compliments = (new ComplimentHistory())->getComplimentPostByReciever();
			$images = (new ImageHistory())->getImagePostByReciever();

			$feed = $this->sortFeed($compliments, $images);

			$user = \Auth::user();
			return view('home',[
				'name' => $user->name,
				'avatar' => $user->avatar,
				'feed' => $feed
			]);
		}
		else{
			return view('home',[
				
			]);
		}	
	}

	public function getImageComments(Request $request){
		$postId = $request->input('post_id');

		$imagePost = (new ImageHistory)->getImagePostById($postId);

		$comments = (new Comment)->getImageCommentsByPostId($postId);

		if(\Auth::check()){
			$user = \Auth::user();
			return view('comment',[
				'comments' => $comments,
				'item' => $imagePost[0],
				'type' => 'image',
				'name' => $user->name,
				'avatar' => $user->avatar
			]);
		}
		return view('comment',[
			'comments' => $comments,
			'item' => $imagePost[0],
			'type' => 'image'
		]);	
	}	

	public function getComplimentComments(Request $request){
		$postId = $request->input('post_id');

		$complimentPost = (new ComplimentHistory)->getComplimentPostById($postId);

		$comments = (new Comment)->getComplimentCommentsByPostId($postId);

		if(\Auth::check()){
			$user = \Auth::user();
			return view('comment',[
				'comments' => $comments,
				'item' => $complimentPost[0],
				'type' => 'compliment',
				'name' => $user->name,
				'avatar' => $user->avatar
			]);
		}
		return view('comment',[
			'comments' => $comments,
			'item' => $complimentPost[0],
			'type' => 'compliment'
		]);
	}

	public function postImageComments(Request $request){

		$validator = Comment::validate($request->all());

		if($validator->passes()){
			$com = new Comment;
			$comment = $com->postImageComment($request);

			$app_key = \Config::get('pusher.app_key');
			$app_id = \Config::get('pusher.app_id');
			$app_secret = \Config::get('pusher.app_secret');

			$pusher = new Pusher($app_key, $app_secret, $app_id);

			$pusher->trigger('comment_channel', 'newcomment',[
				'comment' => json_encode($comment)
			]);

			return redirect()->back()->with('success', 'posted comment');
		}
		
		return redirect()->back()->withErrors($validator)->withInput();	
	}	

	public function postComplimentComments(Request $request){
		$validator = Comment::validate($request->all());

		if($validator->passes()){
			$com = new Comment;
			$comment = $com->postComplimentComment($request);

			$app_key = \Config::get('pusher.app_key');
			$app_id = \Config::get('pusher.app_id');
			$app_secret = \Config::get('pusher.app_secret');

			$pusher = new Pusher($app_key, $app_secret, $app_id);

			$pusher->trigger('comment_channel', 'newcomment',[
				'comment' => json_encode($comment)
			]);

			return redirect()->back()->with('success', 'posted comment');
		}
		
		return redirect()->back()->withErrors($validator)->withInput();	
	}	

	public function sortFeed($allCompliments, $allImages){
		$feed = array();

		$maxComp = sizeof($allCompliments);
		$maxImage = sizeof($allImages);
		$compIt = 0;
		$imageIt = 0;
		$flag = false;

		while($compIt != $maxComp || $imageIt != $maxImage){

			if($compIt == $maxComp){
				$allImages[$imageIt] = (object) array_merge( (array)$allImages[$imageIt], array('type' => 'image') );
				array_push($feed, $allImages[$imageIt]);
				$imageIt++;
			}
			else if($imageIt == $maxImage){
				$allCompliments[$compIt] = (object) array_merge( (array)$allCompliments[$compIt], array('type' => 'comp') );
				array_push($feed, $allCompliments[$compIt]);
				$compIt++;
			}
			else{
				if($flag){
					$allCompliments[$compIt] = (object) array_merge( (array)$allCompliments[$compIt], array('type' => 'comp') );
					array_push($feed, $allCompliments[$compIt]);
					$compIt++;
					$flag = false;
				}
				else if($allCompliments[$compIt]->time_created > $allImages[$imageIt]->time_created){
					$allCompliments[$compIt] = (object) array_merge( (array)$allCompliments[$compIt], array('type' => 'comp') );
					array_push($feed, $allCompliments[$compIt]);
					$compIt++;

					$flag = true;
				}
				else{
					$allImages[$imageIt] = (object) array_merge( (array)$allImages[$imageIt], array('type' => 'image') );
					array_push($feed, $allImages[$imageIt]);
					$imageIt++;
				}
			}
		}

		return $feed;
	}
}

