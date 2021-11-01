# Simple File Manager
Allow a single user to upload files securely to a single directory on your server.  Uses PHP + Bootstrap + JQuery

## Setup
Clone or download this repository to the environment you intend to operate it in.  Search all files for any instance of `[[[`.  Anything inside three layers of square brackets must be replaced.

`[[[HOMELINK]]]`  Replace every instance of this string with a link to the index.php file
`[[[MANAGELINK]]]`  Replace every instance of this string with a link to manage.php
`[[[mypass]]]`  Choose a password, and replace every instance of this string with the output of ```password_hash("PUT YOUR PASSWORD HERE", PASSWORD_DEFAULT);```
`[[[PUBLICLINK]]]`  Assuming you want the uploaded file to be publicly accessible, replace every instance of this string with the base URL for the upload directory (ex. http://192.168.1.1/)

Finally, it is assumed that uploaded files should go to the default HTML directory for Apache.  This is `/var/www/html`.  If you want a different directory, update delete.php and upload.php accordingly.

## Warnings
It is assumed that an authenticated user should have no restrictions on file type, file contents or file size.  If this is not the case, add code to upload.php.
Every effort has been made to prevent directory traversal, however, file uploads using identical names will result in existing files being overwritten.
No database is used, so passwords must be hashed and hard coded.  This is not considered a best practice.

*Not intended for use in a production environment.  Audit all code manually before installation.  The security of this code should be reasonably strong, given that the people with access are trusted.*
