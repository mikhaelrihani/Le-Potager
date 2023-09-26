<<<<<<< HEAD
<?php

$id = 1;
$city = "annecy";
$dist = 100;
$gardenId = 1;

$routes = [
    "routesApi"  => [
        'GET'    => [
            "app_api_garden_getGardens",
            "app_api_garden_getGardensBySearch",
            "app_api_garden_getGardenById",
            "app_api_garden_getPictureByGarden",
            "app_api_user_getUsers",
            "app_api_user_getUsersById",
            "app_api_user_getFavoriteUser",
            "app_api_user_getGardensUser",
            "app_api_imageKitAuth_authenticateImageKit",
        ],

        'POST'   => [
            "app_api_garden_postGarden",
            "app_api_garden_addPictureToRegisteredGarden",
            "app_api_user_postUsers",
            "app_api_user_postFavoriteUser",
        ],

        'PUT'    => [
            "app_api_garden_putGardenById",
            "app_api_user_putUser",
        ],

        'DELETE' => [
          
            "app_api_user_deleteFavoriteById",
            "app_api_user_deleteFavorites",
            "app_api_garden_deletePictureFromRegisteredGarden",
            "app_api_user_deleteUser",
            "app_api_garden_deleteGardenById",
            
        ],
    ],

    "routesBack" => [
        'GET'  => [
            "app_back_garden_list",
            "app_back_garden_show",
            "app_back_garden_edit",
            "app_back_main_home",
            "app_security_login",
            "app_back_user_list",
            "app_back_user_show",
            "app_back_user_edit",
            "app_back_user_add"
        ],

        'POST' => [

            "app_back_garden_edit",
            "app_back_garden_delete",
            "app_back_garden_deletePicture",
            "app_security_logout",
            "app_back_user_edit",
            "app_back_user_delete",
            "app_back_user_add"
        ],

    ]
];



$jsonDataPostPutGarden = '{"title": "garden test.","description": "Similique voluptatem aut eum impedit non unde assumenda. Maiores molestias sit nihil et quo deserunt eos. Quia voluptate odio corrupti maiores voluptas est ut. Inventore quas in sit veniam ut harum.","address": "46, rue de Marechal","postalCode": "78189","city": "orleans","water": false,"tool": false,"shed": true,"cultivation": true,"surface": 73,"phoneAccess": true,"state": "Officia.","pictures": [{"id": 30,"url": "https:\/\/images.unsplash.com\/photo-1563127673-b35a42ef206c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0OTEyOTR8MHwxfHJhbmRvbXxfHx8fHx8fDE2OTM3MzI5Mjl8&ixlib=rb-4.0.3&q=80&w=800"}],"user":1 }';

$jsonDataPostPutUser = '{"id":8,"username":"nouveau_nom","userIdentifier":"nouveau_nom","password":"$2y$04$iZQv42VkmdP62XJJvRp.XuomlKG6a9wYJEoU\/aeS4YIayhIIaVlk2","email":"nouvel.email@example.com","phone":"0123456789","roles":["ROLE_ADMIN","ROLE_USER"],"avatar":"https:\/\/us.123rf.com\/450wm\/lerarelart\/lerarelart2001\/lerarelart200100084\/137333196-l-illustration-vectorielle-des-oignons-et-des-carottes-sont-des-amis-l%C3%A9gumes-dr%C3%B4les-de-personnages.jpg?ver=6","createdAt":"2002-11-14T00:00:00+00:00","updatedAt":null,"gardens":[],"favorites":[],"salt":null}';

$jsonDataPostImage = '{
    
    "url": "https:\/\/images.unsplash.com\/photo-1563127673-b35a42ef206c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0OTEyOTR8MHwxfHJhbmRvbXxfHx8fHx8fDE2OTM3MzI5Mjl8&ixlib=rb-4.0.3&q=80&w=800"
=======
<?php

$id = 1;
$city = "annecy";
$dist = 100;
$gardenId = 1;

$routes = [
    "routesApi"  => [
        'GET'    => [
            "app_api_garden_getGardens",
            "app_api_garden_getGardensBySearch",
            "app_api_garden_getGardenById",
            "app_api_garden_getPictureByGarden",
            "app_api_user_getUsers",
            "app_api_user_getUsersById",
            "app_api_user_getFavoriteUser",
            "app_api_user_getGardensUser",
            "app_api_imageKitAuth_authenticateImageKit",
        ],

        'POST'   => [
            "app_api_garden_postGarden",
            "app_api_garden_addPictureToRegisteredGarden",
            "app_api_user_postUsers",
            "app_api_user_postFavoriteUser",
        ],

        'PUT'    => [
            "app_api_garden_putGardenById",
            "app_api_user_putUser",
        ],

        'DELETE' => [
            "app_api_user_deleteFavorites",
            "app_api_garden_deletePictureFromRegisteredGarden",
            "app_api_user_deleteFavoriteById",
            "app_api_garden_deleteGardenById",
            "app_api_user_deleteUser",
        ],
    ],

    "routesBack" => [
        'GET'  => [
            "app_back_garden_list",
            "app_back_garden_show",
            "app_back_garden_edit",
            "app_back_main_home",
            "app_security_login",
            "app_back_user_list",
            "app_back_user_show",
            "app_back_user_edit",
            "app_back_user_add"
        ],

        'POST' => [

            "app_back_garden_edit",
            "app_back_garden_delete",
            "app_back_garden_deletePicture",
            "app_security_logout",
            "app_back_user_edit",
            "app_back_user_delete",
            "app_back_user_add"
        ],

    ]
];



$jsonDataPostPutGarden = '{"title": "garden test.","description": "Similique voluptatem aut eum impedit non unde assumenda. Maiores molestias sit nihil et quo deserunt eos. Quia voluptate odio corrupti maiores voluptas est ut. Inventore quas in sit veniam ut harum.","address": "46, rue de Marechal","postalCode": "78189","city": "orleans","water": false,"tool": false,"shed": true,"cultivation": true,"surface": 73,"phoneAccess": true,"state": "Officia.","pictures": [{"id": 30,"url": "https:\/\/images.unsplash.com\/photo-1563127673-b35a42ef206c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0OTEyOTR8MHwxfHJhbmRvbXxfHx8fHx8fDE2OTM3MzI5Mjl8&ixlib=rb-4.0.3&q=80&w=800"}],"user":1 }';

$jsonDataPostPutUser = '{"id":8,"username":"nouveau_nom","userIdentifier":"nouveau_nom","password":"$2y$04$iZQv42VkmdP62XJJvRp.XuomlKG6a9wYJEoU\/aeS4YIayhIIaVlk2","email":"nouvel.email@example.com","phone":"0123456789","roles":["ROLE_ADMIN","ROLE_USER"],"avatar":"https:\/\/us.123rf.com\/450wm\/lerarelart\/lerarelart2001\/lerarelart200100084\/137333196-l-illustration-vectorielle-des-oignons-et-des-carottes-sont-des-amis-l%C3%A9gumes-dr%C3%B4les-de-personnages.jpg?ver=6","createdAt":"2002-11-14T00:00:00+00:00","updatedAt":null,"gardens":[],"favorites":[],"salt":null}';

$jsonDataPostImage = '{
    
    "url": "https:\/\/images.unsplash.com\/photo-1563127673-b35a42ef206c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0OTEyOTR8MHwxfHJhbmRvbXxfHx8fHx8fDE2OTM3MzI5Mjl8&ixlib=rb-4.0.3&q=80&w=800"
>>>>>>> 8f1c19c3377b51069f02aeaf5ca90b377b493c27
  }';