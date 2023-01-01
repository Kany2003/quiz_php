<?php  
//questionAnswerClasses.inc.php
//questionID and answerID are not needed as properties,
//because already used as an index of arrays

//properties are the columns of the questions table  
class question {
	public $question;
	public $questionTypeId;
	public $answerList;//to manage the answers of a question


	public function __construct($question, $questionTypeId) {
		$this->question=$question;
		$this->questionTypeId=$questionTypeId;
		
		
		$this->answerList=array();
		
	}

	public function addAnswer($answerId, $answerText, $flag=0) {
		$this->answerList[$answerId]=new answer($answerText, $flag);
	}

	public function getAnswer($answerId) {
		return $this->answerList[$answerId];
	}
}

//properties are the columns of the answers table  
class answer {
	public $answer;
	public $flag;
	

	public function __construct($answer, $flag=0  ) {
		$this->answer=$answer;
		$this->flag=$flag;
		
	}
}
?>