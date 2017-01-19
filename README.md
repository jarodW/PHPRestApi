# PHPRestApi
A simple rest Api created in php using the slim framwork V3 that supports crud commands.

Requires the user to login and create an account to obtain an api key which must  be  passed in the header.

#Current allowed calls
Calls                           | method         |Parameters            |Description
------------------------------- | ---------------|----------------------|-----------
http://localhost/task/register	|   POST	       |name, email, password |Register User
http://localhost/task/login	    |   POST	       |email, password       |Log in to obtain api key
http://localhost/task/tasks	    |   POST	       |task                  |Create a new task
http://localhost/task/tasks	    |   GET		       |                      |Get all tasks
http://localhost/task/tasks/{id}|   GET	         |                      |Get a single task
http://localhost/task/tasks/{id}|	  PUT	  	     |status, task          |Update the specified task
http://localhost/task/tasks/{id}|  DELETE	       |           	          |Delete the identified task
