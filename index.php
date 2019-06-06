<!DOCTYPE html>
<html>

<head>
<style>
header {
    background-color:white;
    color:#1DADEA;
    text-align:center;
    padding:5px;	 
}
nav {
    line-height:30px;
    background-color:lightgrey;
    height:300px;
    width:100px;
    float:left;
    padding:5px;	      
}
section {
    float:center;
    padding:10px;	 	 
}
footer {
    background-color:#1DADEA;
    color:white;
    clear:both;
    text-align:center;
    padding:5px;	 	 
}
#container { 
	background-color:white;
 	padding-top:5px; 
  	padding-bottom:5px; 
  	border-top-style:solid;
  	border-top-color: #D8F5FF;
  	border-top-width: 4px;
}
#map {
        height: 75%;
        width: 100vw;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 75%;
        margin: 0;
        padding: 0;
      }
</style>
</head>

<body>
<?php
	$query = $_POST['query'];
	$spec = $_POST['spec'];
	$topk = 50;
	$page_count =  $_COOKIE['page_count'];
	$result_per_page = 10;

#if(!array_key_exists('test',$_POST))
#{
#	$query = $_POST['query'];
#	$spec = $_POST['spec'];
#	$topk = 10;
#	$page_count =  $_COOKIE['page_count'];
#	$result_per_page = 10;
#}
#else
#{
#	$query = $_COOKIE['query'];
#	$spec = $_COOKIE['spec'];
#	$topk = $_COOKIE['topk'];
#	$result_per_page = 10;
#}

?>
<header>
	<a><img src="http://communicasound.com/wp-content/uploads/2017/02/Twitter-Logo-PNG-1.png" width="100" height="100"></a>
	<h1>Twitter Search</h1>
</header>
<footer>
</footer>

<dev align = 'center'>
<section>
<p>
	<form action="index.php" method="post" >
		<select name="spec">
            <option value =" ">All</option>
            <option value ="text" <?php if($spec=="text"){echo "selected = 'true'";}?> >Text</option>
            <option value ="title" <?php if($spec=="title"){echo "selected = 'true'";}?> >Title</option>
            <option value ="place" <?php if($spec=="place"){echo "selected = 'true'";}?> >Place</option>
            <option value="user_id" <?php if($spec=="user_id"){echo "selected = 'true'";}?> >User ID</option> 
        </select>
		<?php
		echo "<input type='text' name='query' size='50' value = '".$query."''>";
		?>
		<input type="submit" value="Search">
	</form>
</p>
</section>
</dev>
<dev>
<section>
<?php

#header("Content-Type:text/html; charset=utf-8");

function q_func($query, $topk, $spec)
{

	#$output = exec("python Readfile.py {$var1} {$var2}",$out,$res);
	$output = [];
	$code = 0;
	$var_query = '"'.$_POST['query'].'"';
	exec("python Search.py {$var_query} {$topk} {$spec}", $output, $code);
	#exec("python Readfile.py {$query}", $output, $code);
	#$output = shell_exec($command);
	#echo "$output</br>";
	#var_dump($output);
	#print_r(urldecode($out[0]));
	$json_text = '';
	#$array = explode(';;;', $output);
	foreach ($output as $value) {
		#$trans = array('u\''=>'','\''=>'');	
		#$trans = array('u'=>'');
		#echo strtr("<td>$value</td>", $trans);
		#echo "$value";
		$json_text = $json_text.$value;
	}

	#echo "$output";
	#var_dump($output);
	#echo "$json_text";
	#print_r($json_text);
	$data = json_decode($json_text);
	#print_r($data->{'1'}->{'user_id'});
	
	foreach ($data as $key => $value) 
	{
		#var_dump($data->{$key});
		#var_dump($value);
		echo "<div id='container'> ";
		echo "<b>@";
		print_r($value->{'user_id'});
		echo "</b>";
		echo "&nbsp &nbsp &nbsp &nbsp";
		print_r($value->{'time'});
		echo "</br>";
		print_r($value->{'text'});
		foreach ($value as $key2 => $value2) 
		{
			if($value2 != "None")
			{
				#print_r($key2);
				#if($key2 == "hashtags")
				#{
				#	//foreach ($value2 as $key3 => $value3) 
				#	//{
				#		echo "#";
				#		print_r($value2);
				#	//}
				#}
				if($key2 == "place")
				{

					echo "</br>";
					$array = explode(';', $value2);
					print_r($array[0]);
				}
				#else if($key2 == "user_mentions")
				#{
				#	//foreach ($value2 as $key3 => $value3) 
				#	//{
				#		echo "@";
				#		print_r($value2);
				#	//}
				#}
			}
		}
		echo "&nbsp &nbsp &nbsp Score: ";
		print_r($value->{'Score'});
		if($value->{'cords_x'} != "None")
		{
			#echo $value->{'cords_x'};
			#echo $value->{'cords_y'};
			$lat_x = $value->{'cords_x'};
			$long_y = $value->{'cords_y'};
			echo '<a href="http://localhost/maps_helloworld.php?lat='.$lat_x.'&long='.$long_y.'"><button>Location</button></a>';
		}
		echo "</dev>";
	}

	#$obj = json_decode($output[0],true);
	##echo "$obj['text']";
}
#function more_result()
#{
#	echo "$page_count";
#}
#
#if(array_key_exists('test',$_POST))
#{
#	more_result();
#}

if($query != "")
{
	q_func($query, $topk, $spec);
}
else
	echo "<p>Empty Query!</p>";



#$command = "python Readfile.py {$folder} 2>&1";
#$response = [];
#$code = 0;
#exec($command, $response, $code);
#var_dump($code);
##print_r($response);
#foreach ($response as $value) {
#		#$trans = array('u\''=>'','\''=>'');
#		echo "$value";
#}

?>

<footer>
Spring 2019 - CS172 Introduction to Information Retreival @UCRiverside 
</footer>

</body>
</html>
