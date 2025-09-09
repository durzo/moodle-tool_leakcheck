# Password Leak Checker

This plugin integrates with the Have I Been Pwned (HIBP) API to check if user passwords have been exposed in known data breaches.

If a password is found to be compromised, the plugin enforces security measures to protect the user's account:

* The compromised password is removed from the user's account, preventing further logins with that password.
* All active sessions for the user are terminated across all devices and browsers.
* The user is redirected to the password reset page with a notification, prompting them to set a new password.

The plugin uses a custom core patch (in the patches/ directory) to hook into the login process and enforce these security measures, 
as the current password policy check logic in Moodle is flawed - see MDL-82719 and MDL-86329 for more details.

Plugin configuration is done via the Moodle admin interface, under Site administration > Plugins > Admin Tools > Password leak checker.

Note that we can only check passwords belonging to auth plugins that store passwords in the database, 
e.g Manual, DB, and any auth plugin that defines is_internal() as true.

This means any external auth plugins, such as LDAP, SAML2, OAUTH2, OIDC, etc, cannot be checked and users of these auth plugins will not be affected by this plugin.
  
<br><br>
Credit to Catalyst IT for the original tool_passwordvalidator plugin and idea, without which this plugin would not exist.
