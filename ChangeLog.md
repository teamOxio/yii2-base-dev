##Version 0.6.3 - by Prabhjyot Singh
```
Bug fixed in ProtectedController while redirecting
Bug fixed in setting returnUrl in BaseController
```
##Version 0.6.2 - by Prabhjyot Singh
```
Remove unnecessary functions from API AuthController
```
##Version 0.6.1 - by Prabhjyot Singh
```
Added gitignore
```
##Version 0.6.0 - by Prabhjyot Singh
```
Added BaseApiController, BaseActiveController
Added JWT authentication library
Implemented Users::findIdentityByAccessToken and postLogin to support JWT
Added urlManager block for REST routes
Added EntityMapper and ApiUserModel Entity
Added AuthController for API login
Increased hash column length to 800 in user_sessions table
```
##Version 0.5.3 - by Prabhjyot Singh
```
Added ProtectedController
Added UserController which is a ProtectedController
Added Two Factor sample test in UserController user/two-fa
```
##Version 0.5.2 - by Rohit Oberoi
```
YII_ENV changed on web/index.php 
```
##Version 0.5.1 - by Rohit Oberoi
```
Yii2 queue version updated in composer.json
Auto change YII_ENV & YII_DEBUG in web/index.php
DB credentials auto-set according to YII_ENV
Migrations added default table options
BackgroundTask table updated
BaseActiveRecord updated
New common file added for same web and command configurations in config/common.php 
```

##Version 0.5 - by Prabhjyot Singh
``
Added sample workers in workers directory
Changed useragent length to 800 chars from 100/200
Added ChangeLog
``
