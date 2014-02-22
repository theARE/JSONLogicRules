<?php
/*	This example is a work in progress. It is based around the idea of multiple question sets
 * 	forming a user's journey through the application. A "Logicset" of rules can be assigned
 * 	after each question set to determin where the user is directed next, or what other actions
 * 	are performed (such as emailing the user or administrator)
 */
 
$file = file_get_contents('./php_example.txt', true);
$rules = json_decode($file, true);
$rule_count=0;
$build_rules=array();
foreach(($rules['rules']) as $key=>$rule)
{	$build_rule=array();
	$actions_count=0;
	$element_count=0;
	$build_rule['type']="if";
	foreach(($rules['rules'][$rule_count]['if']) as $key=>$rule){
		switch( key($rule))
		{	case "id":
				$build_rule=array_push_assoc($build_rule, 'id', $rule['id']);
				break;
			case "with":
				$build_rule=array_push_assoc($build_rule, 'with', $rule['with']);
				break;	
			case "action":
				$build_rule["actions"][$actions_count]=$rule['action'];
				$actions_count++;
				break;
			default:
				$build_rule["elements"][$element_count]=$rule;
				$element_count++;
				break;
		}
	}
	array_push($build_rules, $build_rule);
	$rule_count++;	
}

foreach ($build_rules as $build_rule)
{	if(checkAnswer($build_rule['type'], $build_rule['elements'], $build_rule['with'])==true)
	{	$actions_other=array();
		$actions_redirect = array();
		// we do the following to make sure that a redirect is the last action performed
		foreach($build_rule['actions'] as $action)
		{	if(key($action)=="redirect")
			{	array_push($actions_redirect, $action);
			}
			else
			{	array_push($actions_other, $action);
			}
		}
		$orderd_actions = array_merge($actions_other, $actions_redirect);
		foreach($orderd_actions as $orderd_action)
		{	switch(key($orderd_action))
			{	case "email":
					actionEmail($orderd_action['email']);
					break;
				case "queue":
					actionForwardQS($orderd_action['queue']);
					break;
				case "redirect":
					$parts=explode("-",$orderd_action['redirect']);
					if($parts[0]=="questionset")
					{	actionForwardQS($parts[1]);
					}
					if($parts[0]=="page")
					{	actionForwardPage($parts[1]);
					}
					break;
			}
		}
	}
}

function checkAnswer($type, $question_vals, $questionset_id){
	$test_string="";
	$test_string.=$type.' ';
	foreach ($question_vals as $question_val)
	{	if(key($question_val)=="join")
		{	$test_string.=' '.$question_val['join'].' ';
		}
		else
		{	$sub_key=key($question_val[key($question_val)][0]);
			$test_string.=$questionset_id.'.'.key($question_val) .' '. $sub_key .' '. $question_val[key($question_val)][0][$sub_key];
		}
	}
	//	do some sql with this
	//	then return true or false
	if(strpos($test_string, "#one"))
	{	return true; //for now
	}
	else
	{	return false; //for now
	}	
}

function actionEmail($address){
	echo "doing email - $address ";
}

function actionForwardQS($id){
	echo "doing forward QS -$id ";
}

function actionForwardPage($id){
	echo "doing forward Page $id ";
}

function addToQueue($submission_id, $queue_id){
	echo "adding to queue";
}

function array_push_assoc($array, $key, $value){
	$array[$key] = $value;
	return $array;
}
?> 
