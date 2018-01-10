This project is based of Folklore\GraphQL package, adding Helpers that make more easy the integration with Laravel.

For more details check:  https://github.com/Folkloreatelier/laravel-graphql


- Getting Started
  - [Installation](#installation)
  - [GraphiQL](#graphiql)
  - [Types](#types)
  - [Queries](#queries)
  - [Query Arguments](#query-arguments)

- Custom Types
  - [ConnectionType](#connectiontype)
  - [DateType](#datetype)
  - [HasManyType](#hasmanytype)
  - [HasOneType](#hasonetype)
  - [MetaType](#metatype)
  - [PageInfoType](#pageinfotype)


# Installation

## 1) Composer
```
composer require xpromx/laravel-graphql
```

## 2) Create Config File
in  **/config/graphql.php**  check the example inside this repository.

## 3) Edit bootstrap/app.php
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

## 4) Create the GraphQL folder
inside this folder: **/app/GraphQL** with these other folders inside: **/Types** and **/Query**

# Graphiql
An in-browser IDE for exploring GraphQL.
```
http://project-name.test/graphiql
```

# Types
Creating new Types = Your Laravel Models check the examples in **/Examples/Type** folder inside this repository. check the custom types section in this doc.

Register the Types in your **/config/graphql.php**.

```php
<?php
// app/GraphQL/Type/UserType.php

namespace App\GraphQL\Type;

use Xpromx\GraphQL\Definition\Type;
use Xpromx\GraphQL\Type as BaseType;

class UserType extends BaseType
{
    protected $attributes = [
        'name' => 'UserType',
        'description' => 'A User',
        'model' => \App\User::class // Laravel Model
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user'
            ],

            'created_at' => [
                'type' => Type::date(),
                'description' => 'When the user was created'
            ],

            'updated_at' => [
                'type' => Type::date(),
                'description' => 'When the user was updated'
            ],

        ];
    }

}
```

# Queries
Creating new Queries = Endpoints of your API.
Check the examples in **/Examples/Query** folder inside this repository. example:

Register the Queries in your **/config/graphql.php**.

```php
<?php
// app/GraphQL/Query/UserQuery.php

namespace App\GraphQL\Query;

use Xpromx\GraphQL\Query;
use Xpromx\GraphQL\Definition\Type;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'UsersQuery',
        'description' => 'A Users Query'
    ];

    public function type()
    {
        return Type::connection('user'); // UserType
    }

}

```

# Query Arguments
This are the filters you can apply automatically for your Queries
```json
users(
    id: 1,
    limit: 30,
    page: 2,
    hasRelation: 'user',
    doesntHaveRelation: 'comments',
    orderBy: 'id DESC',
    filter: {field: "email", condition:CONTAINS, value:"@gmail.com"}
)
{
    nodes {
        ...
    }

    pageInfo {
        ...
    }

}
```

the variable


# ConnectionType
Will create the connection for the Type selected, this connection will simplify the queries and adapt the results to http://graphql.org/ standars. format:

```json
{
    userQuery(page:1, limit:20){
        nodes{
            id,
            first_name
            email
            ...
        },
        pageInfo{
            current_page
            total
        }
    }
}
```

# DateType
Return the dates formated for humans
```php
'updated_at' => [
    'type' => Type::date(),
    'description' => 'When the user was updated'
]
```

# HasManyType
This one return a list of the Type selected, for Relations **OneToMany**
```php
// UserType.php
'comments' => Type::hasMany('comment')
```

# HasOneType
For **OneToOne** Relations
```php
// CommentType
'user' => Type::hasOne('user')
```

# MetaType
When you need to return a Json object use the MetaType field
```php
// UserType
'meta' => [
    'type' => Type::meta(),
    'description' => 'Extra information about this user'
]
```

# PageInfoType
Return the pagination fields, this one is automatically applied in the ConnectionType, and the fields are:
```json
{
    pageInfo
    {
        current_page,
        next_page,
        prev_page,
        last_page,
        per_page,
        total
    }
}
```