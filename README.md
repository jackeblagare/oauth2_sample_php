# Sample OAuth2 Implementation in PHP

### Introduction
This project contains two applications: OAuth2 server (Yii2-based implementation using thephpleague/oauth2-server) and OAuth2
client (PHP). This is just a simple implementation to give a quick overview of what happens in the OAuth2 process flow. It doesn't
include the use of scopes.

This demonstrates the **authorization code** type of grant wherein the process flow is indicated below:

1. The client application tells the OAuth2 server that it wants to access data in behalf of the user.
2. The OAuth2 server redirects to an authorization page (before this should be a login mechanism) that asks for confirmation from the user.
3. The OAuth2 server gives the client an authorization code.
4. The client exchanges the authorization code for an access token. The authorization code may only be used once.
5. The OAuth2 server gives the client an access token.

### System Requirements

* PHP 5.4+
* PostgreSQL 9.2+
* Apache Server
* 
### Installation
1. Copy the *oauth2* directory to the root of your web server.
2. Create a PostgreSQL database.
3. Restore the SQL database dump file found at *oauth2/sample_postgresql_database.sql* to your database.
4. Go to oauth2/config/db.php and change the database configuration settings.
5. Copy the *oauth2_php_client.php* to the root of your web server.

### Usage
1. Access http://localhost/oauth2_php_client.php in your web browser. This will open up a sample client that will access the OAuth 2 server.
2. Click on Request Access.
3. If successful, you will receive an access token.

### Important Files
+ oauth2server/modules/v1/controllers/Authenticate.php - contains code for getting the authorization code as well as for retrieving the access token.
+ oauth2server/modules/v1/controllers/Authorize.php - contains code for displaying the confirmation on whether the user is allowing the client to access his data.
+ oauth2server/models - contains the implementations specific to the database engine. These are classes that you must implement yourself even though you are using a library for handling the OAuth2 process flow.
