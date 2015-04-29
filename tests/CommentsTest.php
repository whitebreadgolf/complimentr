<?php 

class CommentsTest extends TestCase {

  public function testValidateReturnsFalseIfCommentMissing()
  {
    $validation = \App\Models\Comment::validate([]);
    $this->assertEquals($validation->passes(), false);
  }

  public function testValidateReturnsTrueIfCommentIsPresent()
  {
    $validation = \App\Models\Comment::validate([
      'comment' => 'test commment'
    ]);
    $this->assertEquals($validation->passes(), true);
  }
}

 ?>