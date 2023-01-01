<?php
include("connectDB.inc.php");
include("questionAnswerClasses.inc.php");

if(array_key_exists('questionID',$_GET) and is_numeric($_GET['questionID'])){

	$questionID =intval($_GET['questionID']);

} else {
	exit();
}


//cahnge question
if(isset($_POST['question']) and $_POST['question'] != NULL){
	$questionText = $_POST['question'];
	$query = "UPDATE questions SET questionText = ? WHERE questionId = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("si", $questionText, $questionID); //string integer integer
	$success = $stmt->execute();
}

//change type
if(isset($_POST['questionType']) and $_POST['questionType'] != NULL){
	$questionTypeID = $_POST['questionType'];
	$query = "UPDATE questions SET questionTypeId = ? WHERE questionId = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ii", $questionTypeID, $questionID); //string integer integer
	$success = $stmt->execute();
}

$s=1;	//counter for the number of answers
while(isset($_POST['answer' . $s]))
{
	$answerID;
	if(isset($_POST['answer_'.$s.'_ID'])){
		$answerID = $_POST['answer_'.$s.'_ID'];
	}
	
	//DELETE
	if(isset($_POST['delete'.$s]) and $_POST['delete'.$s] == 'on')
	{
		echo"delete";
		$query = "DELETE FROM questions_answers WHERE answerId = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("i", $answerID);
		$stmt->execute();
		$s++;
		continue;
	}

	//Nothing is chosen or changed
	if($_POST['answer' . $s] == NULL and isset($_POST['answer_'.$s.'_ID'])){
		$s++;
		continue;
	}

	//UPDATE
	$answer = $_POST['answer' . $s];
	$flag = $_POST['grade' . $s];
	$questionID = $_GET['questionID'];
	if (isset($_POST['answer_' . $s . '_ID'])) {


		$query = "UPDATE questions_answers SET answerText = ?, flag = ? WHERE answerId = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sii", $answer, $flag, $answerID); //string integer integer
		$success = $stmt->execute();
	}
	//INSERT
	else if($_POST['answer' . $s] != NULL){
		$query = "INSERT INTO questions_answers (answerText, flag, questionId) VALUES (?, ?, ?)";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sii", $answer, $flag, $questionID); //string integer integer
		$stmt->execute();
	}
	$s++;
}

echo"Database updated";
//go back to the question
header("Location: questionEdit.php?questionID=" . $questionID);
?>