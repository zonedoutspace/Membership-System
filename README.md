# Membership-System
A simple PHP system designed to allow users to register, login, logout, and have access to certain pages depending on level.

# Why I made this?
This was simply built as a excersize to help me get use to using Namespaces, Classes, Autoloader, and PDO statements. 

# How To
1. Be sure to open src/membership/Config.php in a text editor and edit lines 14-17 to match your SQL database.
2. I have included a backup of the SQL database I used to get your started. It is backup.SQL
3. Upload all the files (excluding backup.sql) to a directory on your web server. 

Include the following lines of code in each page that you want to only be visible to registered users who are signed in.

```
<?php
    session_start();
    include_once "vendor/autoload.php";
    $page = new membership\Page(1);
    if ($page->isValid() == true){
        
        //Your Code Here
        
    }
```
<hr>

When the Page class is instantiated we pass in a value of 1.  By default, all registered users are assigned a value of 1.  By later assigning some members a value of 2 or higher, you now have the ability to create pages that are only visable by "Moderators" or "Admins" and the like.
The Page class, when instaniated, first calls the Display method. Display will verify you are logged in, and if you are not, will display a login box. 
If you are logged in, it will then verify you have the proper use level (in this case 1) to view the page. 

There are a few more functions built in.  For example, while attempting to Login. 
When you attempt to Login the method Login from the User class is instantiated. If you fail to Login, it will call the failedLogin method.
failedLogin will update the SQL database with your IP address and the timestamp of the last attempt. If your last attempt was less then 5 minutes ago, it will increase your number of failed attempts by one, otherwise it will set your number of failed attempts back to 1.  Once your failed attemps reach 5, the ipBanCheck method will prevent you from making another further Login attemps for 5 full minutes. 

Also, when you Login it will keep track of your last Login and time. This is for future functionatlity I have yet to program such as a "Users Online" box. 

I will be making updates to this at a later time to include a "Forgot Password" option, a PM system, Users Online, Profiles, Templates, and an Admin panel.

