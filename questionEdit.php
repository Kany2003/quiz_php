<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//viewQuestion.php 
include("connectDB.inc.php");
include("questionAnswerClasses.inc.php");


//checking if the URL variable exists
if( isset($_GET["questionID"]) ){
	$questionID=$_GET["questionID"];
} else {
	exit();
}
//query for question types, see readingQuestionsAnswers.php
$query="SELECT * FROM question_types";

//call query(), mysqli class method, giving mysqli_result object
$mysqli_result = $mysqli->query($query);


$questionTypeList=array();
//fetch_assoc() method from mysqli_result object
while($var = $mysqli_result->fetch_assoc() ){
$questionTypeList[$var["questionTypeId"]]=$var["questionType"];

}



//query with question mark ? on questions / answers in relationship for a given questionID
$query="SELECT questions.questionId, questionText, questions.questionTypeId, questionType, answerId, answerText, flag FROM questions LEFT JOIN questions_answers ON questions.questionID=questions_answers.questionID LEFT JOIN question_types ON questions.questionTypeId=question_types.questionTypeId WHERE questions.questionID=?";

//prepare() method from mysqli class, giving a mysqli_stmt object => $stmt
$stmt = $mysqli->prepare($query);

//bind_param() method from mysqli_stmt object
//assign a value to each parameter marker, with type : i, s, ..
$stmt->bind_param("i", $questionID);

//execute() method from mysqli_stmt object
$stmt->execute();


//bind_result() method from mysqli_stmt object
//with the list of the colums used in the SELECT query, which will become variables
$stmt->bind_result($questionID, $questionText, $questionTypeID,$questionTypeText,$answerId, $answerText, $flag);



$questionList=array();

//while loop with fetch() method from mysqli_result object
while($stmt->fetch() ){
	if (!array_key_exists($questionID, $questionList)) {
		$questionList[$questionID] = new question($questionText, $questionTypeID);
	}
$questionList[$questionID]->addAnswer($answerId, $answerText, $flag);

}

//for debug only
echo "<pre>";
// print_r($questionList);
echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
    <body>
        <?php
    foreach ($questionList as $keyQuestionID => $valueQuestion) {
        echo "<div style=\"width: 38rem;\">";    

            echo "<form action=\"edit.php?questionID=$questionID\" method=\"post\">
            
                <p><b>".$questionText."</b></p>
                <input type=\"text\" name=\"question\"  id=\"question\" placeholder=\"New Question\"></p>

                <input type=\"hidden\" name=\"questionID\" value=$questionID />

                <p>Question Type: ".$questionTypeText." </p>
                <select name=\"questionType\" id=\"questionType\">";
                foreach ($questionTypeList as $keyQuestionTypeID => $valueQuestionType) {
                    echo "<option value=\"$keyQuestionTypeID\">$valueQuestionType</option>";
                }
                echo "</select>";

                $s=1;
                foreach ($valueQuestion->answerList as $keyAnswerID => $valueAnswer) {
        
                    echo "<p> 
                    <p>Answer: ".$questionList[$questionID]->answerList[$keyAnswerID]->answer." </p>
                    <p>
                    <input id=$s  name=\"answer$s\" placeholder=\"New Answer\">
                    <input type=\"text\" id=$keyAnswerID  name=\"grade$s\" placeholder=\"New Point\">

                    <input type=\"checkbox\" name=\"delete$s\"> 

                    <input type=\"hidden\" name=\"answer_".$s."_ID\" value=$keyAnswerID />  

                    </p>";
                    //I added a hidden input with the answerID, so I can use it in the edit.php file

                    $s++;
                }
    
                
                //add new answer
                echo "<p>
                Add new answer(optional):
                <input id=$s  name=\"answer$s\" placeholder=\"New Answer\">
                <input type=\"text\" id=$keyAnswerID  name=\"grade$s\" placeholder=\"New Point\">
                </p>";
                
                echo "<input type=\"submit\" value=\"Save\" id=\"submit\" >";
            echo " ";
                echo "<a href=\"readingQuestionsAnswers.php\">Cancel</a>";
            echo "</form>";

    echo "</div>";
    }
?>	



</body>
</html>

