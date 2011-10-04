# Fire Log Spark

## Now with pagination to speed up page loading when log files are large.

Fire Log is a spark that lets you browse all the log files in your application/logs directory. 

-  You can filter what types of items you would like to view DEBUG, ERROR, INFO
-  You can delete logs files from Fire Log to clear out your logs
-  Fire log uses a view that you can easily customize
-  You can change if tags are stripped in logs or not from the config
-  Language file for all text used

![Fire Log](http://dl.dropbox.com/u/9683877/spark_imgs/fire_log_0.5.png "Fire Log Example")

## Usage

Really Simple...
    
	// in your controller
	function logs(){
		/*
		highly advised that you use authentification 
		before running this controller to keep the world out of your logs!!!
		you can use whatever method you like does not have to be logs
		*/
		$this->load->spark( 'fire_log/x.x');
		// thats it, ill take if from there
	}

- [Log Issues or Suggestions](https://github.com/dperrymorrow/Fire-Log/issues)
- [Follow me on Twitter](http://twitter.com/dperrymorrow)
