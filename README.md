# airwhale
This application is for the reporting of Non-Emergency problems to various town office departments. 

The user is asked to sign up for an account by entering a username and a password and an emial. An email will be sent to the user with a confirmation link. Logging in is neccesary to use the application, so as to prevent spamming, and abuse. After logging in the user is located via their browser. If the user has disabled location, or if they are not at the location of the issue, they can click on the map to locate thier issue. 

The user is associated with the problem for contact and status updates. They are prompted to enter a description of the problem, and to select a type of prolem to report. 

Currently the aplication supports 5 problem types: 
* Potholes
* Street Light Issues
* Fire Hydrant Issues
* Grafitti or vandalism
* Other

The option to upload a photo of the problem, as well as a contact number are presented to the user. Then the problem is added to the map, and a new prompt is presented. 
The adminstrative user can also access a list of the problems that have been submitted to review, update status, and delte problems. 

We are using [MAMP](https://www.mamp.info/en/) for our database (MySql) and webserver. In order to make the solution portable and easily installable by a lay person. [PhpMailer](https://github.com/PHPMailer/PHPMailer) is is used for sending email validation of users along with a gmail account to serve as the messenger. We use the google maps javasctipt API to serve the map, display problems, and to display our KML layers for styling the map. 

### To Use The Application: 
  You will need to download and install MAMP (or a smimmilar package, MySql, Apache, Php 7.0). You can use the `auth.sql` file to create the database. Create a user for the database called testUser (or create your own user, and update `phpmsqlinfo.php` and `dbinfo.php` with the new information.)
* Make sure that MAMP's webserver tab is pointed to the folder auth inside the project folder. (or move those files to the `~htdocs` directory. 
* you may need to change the default ports in MAMP also depending on what your configuraton is. 

