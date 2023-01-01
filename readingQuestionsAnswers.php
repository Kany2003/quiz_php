<?php 
//readingQuestionsAnswers.php 
include("connectDB.inc.php");
include("questionAnswerClasses.inc.php");

try {
//query for question_types
$query="SELECT * FROM question_types";
//call query(), mysqli class method, giving mysqli_result object

$mysqli_result = $mysqli->query($query);


} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}

$questionTypeList=array();
//fetch_assoc() method from mysqli_result object
while($var = $mysqli_result->fetch_assoc() ){
$questionTypeList[$var["questionTypeId"]]=$var["questionType"];

}


try {
//query on questions and answers in relationship, with JOIN clause
$query="SELECT * FROM questions LEFT JOIN questions_answers ON questions.questionID=questions_answers.questionID;";

//call query(), mysqli class method, giving mysqli_result object
$mysqli_result = $mysqli->query($query);


} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}


$questionList=array();

//while loop with fetch_assoc() method from mysqli_result object
while( $var = $mysqli_result->fetch_assoc() ){
//new instance of questions class
	if(!array_key_exists($var["questionId"]  , $questionList)){
		$questionList[$var["questionId"]]=new question($var["questionText"], $var["questionTypeId"]);
	} 
	//new instance of answer class
	$questionList[$var["questionId"]]->addAnswer($var["answerId"], $var["answerText"]);

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
<style>
	body {font-family: Verdana;font-size:14pt;}
	table {table-layout: auto;width: 1000px;border-collapse: collapse;border:1px black solid;}
	th, td {border:1px black solid;}
	a {white-space: nowrap;text-decoration: none;color:inherit;}
</style>
</head>
<body>
<nav>
<a href="questionEdit.php?questionID=0">Create a MCQ</a>		
</nav>
<h1>List of questions</h1>	
<table>
<thead><tr><th>Question</th><th>Question Type</th><th>View</th><th>Edit</th></tr></thead>
<tbody>
<?php 
//fill the HTML table with columns indicated from the thead 


foreach ($questionList as $keyQuestionID => $valueQuestion) {
	
echo "<tr>";
echo "<td>".$valueQuestion->question."</td>";
echo "<td>".$questionTypeList[$valueQuestion->questionTypeId]."</td>";
echo "<td><a href='viewQuestion.php?questionID=".$keyQuestionID."'>View</a></td>";
echo "<td><a href='questionEdit.php?questionID=".$keyQuestionID."'>Edit</a></td>";
echo "</tr>";

}

?>	
</tbody>
</table>
</body>
</html>