How to send HTTP/2 based Push Notifications in iOS?

PHP - Laravel iOS Push Notification updated / upgraded to HTTP-2

Now Apple supports push notifications based on the HTTP/2 network protocol. Here is a simple class you use in core PHP or as Laravel Helper in any version as, when and wherever you want.

Note required changes before use:

$apple_cert = config_path('certificates/CertificateAPNUser.pem'); //pem cetificate save in config/certificates/file-name.pem

$http2_server = 'https://api.push.apple.com'; //use for production/live .pem

$http2_server = 'https://api.development.push.apple.com'; //use this for development or testing .pem

Any further enhancements are warmly welcomed.