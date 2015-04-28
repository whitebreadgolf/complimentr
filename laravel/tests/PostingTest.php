<?php 

class PostingTest extends TestCase {

  public function testValidateReturnsFalseIfPostIsMissingUrl()
  {
    $validation = \App\Models\ImageHistory::validate([]);
    $this->assertEquals($validation->passes(), false);
  }

  public function testValidateReturnsTrueIfPostHasUrl()
  {
    $validation = \App\Models\ImageHistory::validate([
    	'imageUrl' => "imageURL",
      	'message' => 'test message'
    ]);
    $this->assertEquals($validation->passes(), true);
  }

  public function testValidateReturnsFalseIfPostHasIncorrectId()
  {
    $validation = \App\Models\ComplimentHistory::validate([
    	'compliment_id' => "not an int",
      	'message' => 'test message'
    ]);
    $this->assertEquals($validation->passes(), false);
  }

  public function testValidateReturnsTrueIfPostHasCorrectId()
  {
    $validation = \App\Models\ComplimentHistory::validate([
    	'compliment_id' => 0,
      	'message' => 'test message'
    ]);
    $this->assertEquals($validation->passes(), true);
  }
}

 ?>