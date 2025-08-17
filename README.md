# Password Leak Checker

This plugin is a fork of Catalyst IT's tool_passwordvalidator plugin, with all logic stripped except for the password leak checking functionality.

It is designed to work with the Moodle core password policy system, allowing administrators to enforce password security by checking against known leaked passwords.

There is an additional feature to lock out users who attempt to log in with a leaked password, which can be enabled in the plugin settings.

The lockout feature does the following:

* removes the (leaked) password stored by Moodle, preventing further logins
* destroys all existing sessions belonging to user over different devices/browsers
* redirects the user to the forgot password page with an error dialogue describing why. This forces Moodle to generate a new temporary password to be mailed out to the user, instead of potentially allowing someone with stolen credentials to change the users password.

<br>

Plugin configuration is done via the Moodle admin interface, under Site administration > Plugins > Admin Tools > Password leak checker.
  
<br><br>
Credit to Catalyst IT for the original plugin and idea, without which this plugin would not exist.
