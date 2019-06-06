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
    height:500px;
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
</style>
</head>

<body>

<?php 
setcookie('page_count','1');
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
            <option value ="text">Text</option>
            <option value ="title">Title</option>
            <option value ="place">Place</option>
            <option value="user_id">User ID</option>  
        </select>
		<input type="text" name="query" size="50" placeholder = "Enter search query">
		<input type="submit" value="Search">
	</form>
</p>
</section>
</dev>


<footer>
Spring 2019 - CS172 Introduction to Information Retreival @UCRiverside 
</footer>

</body>
</html>