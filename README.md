# A Simple SMS text and Email Blast

## Installation

1. Clone this GIT Repository.
2. Create and configure the database and Import the sms.sql file.
3. Configure base_url.
4. In the sms_server column in sms_api table, Insert your SMS Gateway address with the required GET parameters.
5. In the api_code column in sms_api table, Insert the value of required GET Parameters.
5. In the email_sender column in email_settings table, Insert your email address.

## Built With

* [CodeIgniter Web Framework](https://codeigniter.com/)
* [Bootstrap 3](https://getbootstrap.com/)

## Demo
* [SMS and Email Blast](http://sms-and-email-blast.systemph.com/)

For this demo I used [iTextMo SMS](https://www.itexmo.com/) as my SMS Gateway, in their [docs](https://www.itexmo.com/Developers/apidocs.php)<br><br>

For this Demo, the data of the table sms_api is:<br>
* api_code: MYAPICODE<br>
* send_request: 'https://www.itexmo.com/php_api/api.php?apicode='<br>
* get_info: 'https://www.itexmo.com/php_api/apicode_info.php?apicode='<br>

## If you are using other SMS Gateway, you can change the following
application/controllers/Sms.php - Line 238
```
$data["MessagesLeft"] = $response["Result "]["MessagesLeft"];
application/controllers/Sms.php - Line 239
```
```
$data["ExpiresOn"] = $response["Result "]["ExpiresOn"];
```