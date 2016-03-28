<?PHP
//============================================================================================
//Bootstrap Glyphicon Markup Tool PHP
//Author: Dan Guinn - danguinn.com
//============================================================================================

	//Define the regular expression pattern that will be used to validate the entry.
	//Here we are accepting only alphanumeric,space, pipe, and bracket symbols.
	$validation_pattern='/[^a-zA-Z0-9| \\[\\] ]+/';
	
	//If the user has entered data ad submit the form,
	//Set the default value for the code textarea from the $_POST value.
	if(isset($_POST["code"])){
		$code=$_POST["code"];
		
		//Validate code entry before writing, to prevent misuse.
		//For the demo, we'll just put the error in the textarea:
		if(preg_match($validation_pattern,$code,$validation_match)){
			$default_code="Your code was invalid. Please enter only text, brackets and spaces: [[asterisk]]";
			$inline_error="<p>Dude, that's not right!</p>";
		}else{
			$default_code=$_POST["code"];
		}
		
	}else{
		//If the default for the textarea is not set, set it with the example shorthand markup.
		$default_code="[[globe|red|100]]";
	}

?>
	<!DOCTYPE HTML>
	<html>
	<head>
	<title>DANGUINN.COM - DEMO Icon Creator (Creating a Glyphicon Shorthand Markup Tool with PHP)</title>
	<!-- Standard stuff here, just loading bootstrap from CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
	<style>
		body{
			margin:100px;
			background-color:#DADADA;
		}
	</style>
	</head>
	<body>
	<div class="container">
	<div class="row">
		<!-- Instructions-->
	
		<h1>DANGUINN.COM - DEMO Icon Creator</h1>
		<h2>(Creating a Glyphicon Shorthand Markup Tool with PHP)</h2>
		<h3>by Dan Guinn</h3>
		<br />
		<p><b>Description:</b> Bootstrap provides a powerful collection of icons available to developers for usage in their sites called <a href="http://getbootstrap.com/components/#glyphicons" target="_blank">Glyphicons</a>.</p> 
		<p>This is great for developers, but what if you wanted to allow a content manager to create them without knowing how to program?</p>
		<p>Well, we can do this if we create a way to enter the instuction with a shorthand code. Our code works like this: [[name||color|size]]</p>
		<br />
		<p><b>Instructions:</b> A shorthand code is already entered for you. So just press "Make an Icon!" and see what happens! </p>
		<p>When you're done, try some other codes, like [[cloud]] or [[pencil]]. To change the size or color, just separate your instructions with the | symbol. [[cloud|blue|50]].</p>
		<p><b>Advanced:</b> Go to the <a href="http://getbootstrap.com/components/#glyphicons" target="_blank">Glyphicons</a> page and get more codes. They will be listed as "glyphicon glyphicon-NAME" so just use the NAME in your code.</p>
		<br />
		<p><small>Looking for an analyst or a big picture programmer/analyst? Check out 
		<a href="http://danguinn.com">danguinn.com</a> for more information about Dan Guinn. Hope you enjoyed the demo!</small></p>
	</div><!--End Row -->
	<div class="row">
	    <div class="col-md-4">
			
			<!-- Menu and form-->
			<p> <a href="http://danguinn.com">Home</a> | <a href="">Reload</a> | <a href="?code">View Code</a> | <a href="http://danguinn.com#portfolio">More Examples</a></p>
			<form method="POST">
			<p><label>Enter Code:</label><br />
			<textarea name="code"><?PHP echo $default_code ?></textarea><br />
			<p><input type="submit" value="Make an Icon!" class="btn btn-primary"></p>
			</form>

			<?PHP
				//DEBUGGING:
				//error_reporting(E_ALL);
				//ini_set('display_startup_errors', 1);
				//ini_set('display_errors', 1);

				//If code is not passed kill the process.
				if(!isset($_POST["code"])){
					//Output ending HTML with line breaks
					$html="</div>\n</div>\n</div>\n</body>\n</html>\n";
					die($html);	
				}
				
				//Display the error if the validation failed
				if(isset($inline_error)){
					echo $inline_error;
				}

				//Use a regular expression to get the code of the text from between the double brackets in the content
				//Any entry with [[CONTENT]] will be captured
				preg_match_all('/\[\[(.*?)\]\]/i',$default_code,$matches);
				//var_dump($matches[1]); //Debug
				
				//Count the matches
				$count=count($matches[1]);
				
				//Loop through the matches array
				for($i=0;$i<$count;$i++){
					
					//Explode the [[content]] by | to get the settings
					//0 will be the name
					//1 will be the color
					//2 will be the size
					
					$instructions=$matches[1][$i];
					$glyphInstArray=explode("|", $instructions);
					
					//Get the name, return nothing if not found
					if(isset($glyphInstArray[0])){
						//Limit name to 20 characters to prohibit abuse
						$name=substr(trim($glyphInstArray[0]), 0, 20);
					}else{
						return $default_code;	
					}
					
					//Get the color
					if(isset($glyphInstArray[1])){
						//Limit color to 15 characters to prohibit abuse
						//If the color is not a valid color code the browser will just default to gray
						$color=substr(trim($glyphInstArray[1]),0,15);
					}else{
						$color="gray";
					}
					
					//Get the size 
					//Check if the entry is numeric, if not ignore and set default
					//Depending on the system requirements, you may or may not want to not want to notify, 
					//especially if utilized in a CMS and the edits are live.
					if(isset($glyphInstArray[2]) && is_numeric($glyphInstArray[2])){
						
						//Limit size to 3 characters to prohibit abuse (max number will be 999, until limited)
						$size=substr(trim($glyphInstArray[2]), 0, 3);
						//limit to 500 to keep the user from creating an icon too big.
						if($size>500){
							$size=500;	
						}
						
					}else{
						$size="50"; //default
					}
					
					//Confirm that the instructions were found in the default code and if so, create the glyph code.
					if (strpos($default_code, "[[".$instructions."]]") !== FALSE) {
						//Create the glyph code, note: margin-right:none; is for the demo only.
						$glyph_code="<i class=\"glyphicon glyphicon-".$name."\" style=\"font-size: ".$size."px; color:".$color.";float:left; margin-right:none; \"></i>";
						
						//Note: Here we could have just output the glyphcode, but if we want to embed the glyph in the 
						//actual content we need to replace it and the send the result to the browser.
						$output=str_replace("[[".$instructions."]]", $glyph_code, $default_code);
					}
					
					
				}
				
				//Send the result to the browser if it is set.
				if(isset($output)){
					echo "<p>&nbsp;</p><br /><p class='text-center'>".$output."</p>";
					
				} 
			?>
				
			
				</div><!--End Column -->
			
	</div><!--End Row -->
</div><!--End Container -->
</body>
</html>
