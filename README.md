# Installation

### 1) Composer
```
composer require xpromx/laravel-graphql
```

### 2) Create Config File
in  **/config/graphql.php**  check the example inside this repository 

### 3) Edit bootstrap/app.php
uncomment the follow lines:
```php
$app->withFacades();
$app->withEloquent();

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
```

then add this lines in the same file
```php
$app->configure('graphql');
$app->register(Folklore\GraphQL\LumenServiceProvider::class);
```

### 4) Access to the Graphiql
```
http://project-name.dev/graphiql
```

