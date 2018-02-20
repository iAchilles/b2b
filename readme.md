**B2B Recipe API**
------------------

REST API на базе микрофреймворка Lumen

Зависимости
-------------

 - PHP >= 7.0
 - Postgresql
 - Imagick (Для Debian пакет устанавливается следующим образом: sudo apt-get install php7.0-imagick)

Установка
-------------

- Склонировать репозиторий
- Перейти в корневую директорию репозитория
- Выполнить команду `composer install`
- Переименовать файл `env.example` в `.env`
- Определить необходимые настройки окружения в файле `.env` (следует изменить настройки соединения с БД)
- Выполнить команду `php artisan migrate`
- Выполнить команду `php artisan doctrine:generate:proxies`

На этом установка завершена.


Настройка web сервера
------------------

**Nginx**

В данном примере представлен вариант настройки виртуального хоста Nginx. 

```
server {
    server_tokens off;
    add_header X-Frame-Options SAMEORIGIN;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";

    charset utf-8;
    client_max_body_size 50M;
    
    listen 127.0.0.1:80;
    server_name localhost;
    root        /home/user/www/laravel/b2b/public;
    index       index.php;

    location / {
       try_files $uri $uri/ /index.php?$query_string;
    }


    location ~/\.(eot|otf|ttf|woff)$ {
        add_header Access-Control-Allow-Origin *;
    }


    location ~ \.php$ {
        set $fsn "/index.php";
        if (-f $document_root$fastcgi_script_name){
            set $fsn $fastcgi_script_name;
        }
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fsn;
        fastcgi_param  PATH_INFO        $fastcgi_path_info;
        fastcgi_param  PATH_TRANSLATED  $document_root$fsn;
        try_files $uri =404;
    }
        
    location ~ /\.(ht|svn|git) {
                deny all;
    }
}
```


API
----

Клиент ДОЛЖЕН отправлять запросы к API используя Content-Type 'application/json'
- `Content-Type: application/json`

Клиент ДОЛЖЕН подписывать запросы к API, которое требует аутентификации, токеном полученным после регистрации или 
входа. Запрос подписывается путем добавления следующего заголовка:
- `Authorization: Bearer token`

**Обработка ошибок**
API предусматривает два типа ошибок, которые сервер может вернуть в ответ на запрос клиента:
- server
- validation

Структура ответа для ошибок типа `server`:
```json
{
	"status": "error",
	"type": "server",
	"code": 401,
	"message": "Unauthorized"
}
```

Структура ответа для ошибок типа `validation`:
```json
{
	"status": "error",
	"type": "validation",
	"code": 422,
	"fields": {
		"username": [
			"The username field is required."
		]
	}
}
```
Запросы к API следует выполнять (в зависимости от настройки вашего виртуального хоста) на:
http://host/api/{uri}

| URI | Метод | Описание | Требует аутентификации |
| --- | ----- | -------- | ------------ |
| signin | POST  | Аутентификация в системе. Используется для получения токена, который будет необходим для выполнения запросов API требующих аутентификации | Нет |

Запрос:

```
curl -XPOST -H "Content-type: application/json" -d '{
   "username": "irvin",
   "password": "123456"
}' 'http://b2b.loc/api/signin'
```

Ответ:

```json
{
  "status":"ok",
  "data":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYmYiOjE1MTkxMzU5NDgsImV4cCI6MTUxOTEzNzc0OCwidWlkIjoiYzI3YTAzNTYtZGQ4Ni00ODEyLTgzYWUtN2NhNDY1Y2I1ZDg0In0.NYQSrF2ez8wappLys0LPfLgUKArnjIvSzLGRK-ZM1WY"}
```


| URI | Метод | Описание | Требует аутентификации |
| --- | ----- | -------- | ------------ |
| signup | POST  |  Регистрация пользователя в системе. Возвращает токен для выполнения запросов API требующих аутентификации | Нет |

Запрос:

```
curl -XPOST -H "Content-type: application/json" -d '{
   "username": "Jonathan",
   "password": "123456"
}' 'http://b2b.loc/api/signup'
```

Ответ:

```json
{
  "status":"ok",
  "data":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYmYiOjE1MTkxMzU5NDgsImV4cCI6MTUxOTEzNzc0OCwidWlkIjoiYzI3YTAzNTYtZGQ4Ni00ODEyLTgzYWUtN2NhNDY1Y2I1ZDg0In0.NYQSrF2ez8wappLys0LPfLgUKArnjIvSzLGRK-ZM1WY"}
```

| URI | Метод | Описание | Требует аутентификации |
| --- | ----- | -------- | ------------ |
| recipes | GET  |  Получение списка рецептов | Нет |

Будет возвращена коллекция рецептов. 
Атрибуты, которые клиент МОЖЕТ указать в теле запроса:
- limit (int,количество элементов в коллекции)
- offset (int, используется в паре с атрибутом limit для организации пагинации)
- count (bool, в ответе будет присутствовать информация об общем количестве рецептов в системе)
- criteria (критериий выборки)
- related (выборка связанных сущностей)

Запрос (без дополнительных атрибутов):

```
curl -XGET -H "Content-type: application/json" 'http://b2b.loc/api/recipes'
```

Ответ:

```json
{
	"status": "ok",
	"data": {
		"collection": [
			{
				"id": "d6eb6b22-a6d2-4b53-b63c-219b39ac6290",
				"title": "kl",
				"description": "What's up?",
				"createdAt": "2018-02-20T01:44:41-03:00"
			},
			{
				"id": "20ecd34a-e0c7-4fca-8930-3248f1a4898e",
				"title": "Hi",
				"description": "What's up?",
				"createdAt": "2018-02-20T10:19:33-03:00"
			}
		]
	}
}
```

Запрос (с дополнительными атрибутами) :

```
curl -XGET -H "Content-type: application/json" -d '{
	"related": [ "user", "file" ],
	"limit": 1,
	"offset": 0,
	"count": true,
	"criteria": {
		"title": "Hi"
	}
}' 'http://b2b.loc/api/recipes'
```

Ответ:

```json
{
	"status": "ok",
	"data": {
		"collection": [
			{
				"id": "20ecd34a-e0c7-4fca-8930-3248f1a4898e",
				"title": "Hi",
				"description": "What's up?",
				"createdAt": "2018-02-20T10:19:33-03:00",
				"user": {
					"id": "c27a0356-dd86-4812-83ae-7ca465cb5d84",
					"username": "irvin",
					"password": "$2y$10$9SNCyVrgV6subSKP5XEWoOr1h0fUN8HpwzGhCWI2wFP\/41kCekwRS",
					"createdAt": "2018-02-19T20:09:32-03:00"
				},
				"file": {
					"id": "da9a8615-95dc-4797-96ca-b5abdf20fe67",
					"file": "87935616-f822-4d58-bd3b-cf314472d3f0.jpg",
					"temporary": false,
					"createdAt": "2018-02-20T01:51:24-03:00"
				}
			}
		],
		"counter": {
			"summary": 1
		}
	}
}
```

**Загрузка изображений**

API поддерживает загрузку изображений представленных в виде Base64-encoded строки. Клиентское приложение
ДОЛЖНО позаботиться о конвертировании выбранного пользователем изображение в Base64. 
Например так:

```html
<input type="file" onchange="convert()">
```

```js
function convert() {
  var file    = document.querySelector('input[type=file]').files[0];
  var reader  = new FileReader();
  var encoded = ''; 

  reader.addEventListener("load", function () {
    encoded = reader.result;
  }, false);

  if (file) {
    reader.readAsDataURL(file);
  }
}
```

| URI | Метод | Описание | Требует аутентификации |
| --- | ----- | -------- | ------------ |
| images | POST  |  Загрузка изображения | Да |

Запрос:
 
```
curl -XPOST -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYmYiOjE1MTkxMzgzODUsImV4cCI6MTUxOTE0MDE4NSwidWlkIjoiYzI3YTAzNTYtZGQ4Ni00ODEyLTgzYWUtN2NhNDY1Y2I1ZDg0In0.12ykjUdWpuT8N7MjgEAevhmnJDmrFXCvexgxE2RsFvc' -H "Content-type: application/json" -d '{
	"image": "data:image/png;base64,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+2jf6bXVXrV4Wq0d1u5aZn6rPeq39n7bf/xWe9RvtSZbrXXot/dv9/7Tavyv2v7Z6fNPO8Z3nytidbILF1kues2fcoucf/8GJa/52t3HXnsWL3eFhXR1f39SRERE5CW3z0TMosfporxVAzwq2RoWBQou4FXIu2GS4WA74xQRERF56ez70qQdj9FYrVBPwVYpyuFRl42Cy1C8SCmQ4LAF1IvcvnqDrBskaMc4PDlGb/0uV5duUQrY2JZLPdHG3oiIiIh0kH3frB8MOYRrWSq1AnnrOyT6+vCyJdx7ORqxODZQ3bhBwTnJmTMzTDlFVjMltj5dozF2mtkzpzgSarSxKyIiIiKdZf8361tRHPsm2awLiWPYoW6ibpovsi7hZIggHrWtGpXta1zJAg2PYPT/+I1r0Re1AAgnwgRqbeqJiIiISIdp4esrbGKxbTJrNXriNgR7cLqL3M4GcKIWEMSORegZmuDkyZNMjvTjOG/yRqhOvlQHPCq5CloTExEREWlq6esrQk4UMuDs3Jgf+84htou9xOzm65Hhw0SWF5lfg4YV59gJm97DI2wsXWb+po1th7AiB90FERERkc7UUiIWjE0xN/foZ6v/DB/2P9bA6mVsepaxx3/J6mdy9vFGIiIiIgL6Zn0RERERY1RrUkRERMQQrYiJiIiIGKJak21o3+m18NpdS9Fvtc9etVppfovfb7U7/Xb8ttrfVtu3Wpu11fjb/f6t1v571Y6XTp/PX4X5UytiIiIiIoYoERMRERExRImYiIiIiCFKxEREREQMUSImIiIiYogSMRERERFDlIiJiIiIGKJETERERMQQJWIiIiIihqjWpIiIiIghWhETERERMUS1Jjuwvd9qL7a7Nlkn1g7zczyd3l+/1Zr0W/t213Zs9/Zvd21Nvx2Pfoun3ePV7v2zE2tfakVMRERExJB9r4h5pRt8dPkLusJdO78ZYnDyJKnIozbu+gLXrBOcdKyDjlNERETkpdPSpcmu+DFOTycI7vG6PTjDyQMISkRERORV8DvfI1a/u8hi3qJRDvFXf17k80MnmNaKmIiIiMhXaikR2y5c5/J889JkIDLCyal+AtSp1Yc5e8ahsb7QliBFREREXka/86XJOkEiTgwLcA82NhEREZGX2sH812TAl9+CISIiIuJr+voKEREREUP2vZQVjB5ldvrp563+GWZ2HtuDM+zSRERERER2oVqTIiIiIobo0qSIiIiIIb68y161vZ7d3m+1/PxWq+5VqE3m5/Z+255+2x/aXWuv3dun3fNDq9un3fH4bX/22/7fajzRU9/eV/vSx796rvdvd61S1ZoUEREReYkoERMRERExRImYiIiIiCFKxEREREQMUSImIiIiYogSMRERERFDlIiJiIiIGKJETERERMQQJWIiIiIihqjWpIiIiIghWhETERERMUSJmIiIiIgh/w/bNYZYpJslXAAAAABJRU5ErkJggg=="
}' 'http://b2b.loc/api/images'
```

Ответ:

```json
{
  "status":"ok",
  "data": "42ecc5aa-c554-451e-b352-771de6e9555e"}
```

В ответе представлен идентификатор загруженного файла, который следует указать при создании рецепта.


| URI | Метод | Описание | Требует аутентификации |
| --- | ----- | -------- | ------------ |
| recipes | POST  |  Создание рецепта | Да |


Запрос:

```
curl -XPOST -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYmYiOjE1MTkxMzgzODUsImV4cCI6MTUxOTE0MDE4NSwidWlkIjoiYzI3YTAzNTYtZGQ4Ni00ODEyLTgzYWUtN2NhNDY1Y2I1ZDg0In0.12ykjUdWpuT8N7MjgEAevhmnJDmrFXCvexgxE2RsFvc' -H "Content-type: application/json" -d '{
	"title": "new",
	"description":"new",
	"fileId": "42ecc5aa-c554-451e-b352-771de6e9555e"
}' 'http://b2b.loc/api/recipes'
```

Ответ:

```json
{
"status":"ok",
 "data":{
    "id":"2d38166d-2a9e-4df7-9aa3-c3b8decc63ab", 
    "title":"new",
    "description":"new",
    "createdAt":"2018-02-20T15:19:57+00:00"}
}
```

| URI | Метод | Описание | Требует аутентификации |
| --- | ----- | -------- | ------------ |
| recipes/{recipe_id} | PATCH  |  Редактирование рецепта | Да |


Запрос:

```
curl -XPATCH -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYmYiOjE1MTkxNDAzNTUsImV4cCI6MTUxOTE0MjE1NSwidWlkIjoiYzI3YTAzNTYtZGQ4Ni00ODEyLTgzYWUtN2NhNDY1Y2I1ZDg0In0.GIVHwQLbc83hFH1QqtUrvlMeFl9eOzftc1dhmzTVvow' -H "Content-type: application/json" -d '{
	"title": "new1"
}' 'http://b2b.loc/api/recipes/2d38166d-2a9e-4df7-9aa3-c3b8decc63ab'
```

Ответ:

```json
{"status":"ok","data":{"id":"2d38166d-2a9e-4df7-9aa3-c3b8decc63ab","title":"new1","description":"new","createdAt":"2018-02-20T12:19:57-03:00"}}
```


| URI | Метод | Описание | Требует аутентификации |
| --- | ----- | -------- | ------------ |
| recipes/{recipe_id} | DELETE  |  Удаление рецепта | Да |

Запрос:

```
curl -XDELETE -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYmYiOjE1MTkxNDAzNTUsImV4cCI6MTUxOTE0MjE1NSwidWlkIjoiYzI3YTAzNTYtZGQ4Ni00ODEyLTgzYWUtN2NhNDY1Y2I1ZDg0In0.GIVHwQLbc83hFH1QqtUrvlMeFl9eOzftc1dhmzTVvow' -H "Content-type: application/json" 'http://b2b.loc/api/recipes/2d38166d-2a9e-4df7-9aa3-c3b8decc63ab'
```

Ответ:

```json
{"status":"ok","data":1}
```

Изображения по умолчанию сохраняются в директории `public/images`