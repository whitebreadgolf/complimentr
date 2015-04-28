<?php namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Instagram{

	public $allData;
	public $imageUrls;
	public $nextUrl;

	public function getPic($testing){
		//random number of queries
		$randQuery = rand(0,10);
		if($testing){
			$randQuery = 0;
		}

		//to refrence cache
		$pageNum = 0;


		//start with initial query
		if(Cache::has("insta-$pageNum")){
			$this->allData = Cache::get("insta-$pageNum");
			$this->imageUrls = $this->allData->data;

			//get next url
			$pagination = $this->allData->pagination;
			$this->nextUrl = $pagination->next_url;
		}
		else{
			$url = "https://api.instagram.com/v1/tags/cuteanimals/media/recent?client_id=775b3058f70a4fc59638cc12282517ea";
			$contents = json_decode (file_get_contents($url));
			$this->allData = $contents;
			$this->imageUrls = $contents->data;

			//getting next page
			$pagination = $contents->pagination;
			$this->nextUrl = $pagination->next_url;

			Cache::put("insta-$pageNum", $this->allData, 10);
		}


		//iterate to a random page
		while(true){
			//condition for exit
			$pageNum++;
			$randQuery--;
			if($randQuery <= 0){
				break;
			}

			if(Cache::has("insta-$pageNum")){
				$this->allData = Cache::get("insta-$pageNum");
				$this->imageUrls = $this->allData->data;

				//get next url
				$pagination = $this->allData->pagination;
				$this->nextUrl = $pagination->next_url;
			}
			else{
				$contents =json_decode( file_get_contents($this->nextUrl));
				$this->allData = $contents;
				$this->imageUrls = $contents->data;

				//getting next page

				$pagination = $contents->pagination;
				$this->nextUrl = $pagination->next_url;

				Cache::put("insta-$pageNum", $this->allData, 10);
			}

		}
		
		
		//choose a random image to return in view
		$images = array();
		foreach ($this->imageUrls as $data) {
			$images[] = $data->images->standard_resolution->url;
		}

		$max = sizeof($images);
		$rand = rand(0, $max-1);

		return  $images[$rand];
	}

}

 ?>