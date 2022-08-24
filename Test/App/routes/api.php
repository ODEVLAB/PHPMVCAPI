<?php


namespace App;

use App\UsersController;
use App\ProductsController;
use App\CatalogsController;

$Klein = new \Klein\Klein();

/******************** User Routes || Authentication Routes **********************/
$Klein->respond('POST', '/api/v1/user', [ new UsersController(), 'createNewUser' ]);
$Klein->respond('POST', '/api/v1/user-auth', [ new UsersController(), 'login' ]);

/******************** Catalog Routes **********************/
$Klein->respond('POST', '/api/v1/catalog', [ new CatalogsController(), 'createNewCatalog' ]);
$Klein->respond(['PATCH', 'PUT'], '/api/v1/catalog/[:id]', [ new CatalogsController(),  'updateCatalog']);
$Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-id/[:id]', [ new CatalogsController(), 'fetchCatalogById' ]);
$Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-name/[:name]', [ new CatalogsController(), 'fetchCatalogByName' ]);
$Klein->respond(['GET', 'HEAD'], '/api/v1/catalogs', [ new CatalogsController(), 'fetchCatalogs' ]);
$Klein->respond('DELETE', '/api/v1/del-catalog/[:id]', [ new CatalogsController(), 'deleteCatalog' ]);

/******************** Product Routes  **********************/
$Klein->respond('POST', '/api/v1/product', [ new ProductsController(), 'createProduct' ]);
$Klein->respond('POST', '/api/v1/product/[:id]', [ new ProductsController(), 'updateProduct' ]);
$Klein->respond('GET', '/api/v1/fetch/[:id]', [ new ProductsController(), 'getProductById' ]);
$Klein->respond('GET', '/api/v1/products', [ new ProductsController(), 'fetchProducts' ]);
$Klein->respond('DELETE', '/api/v1/delete-product/[:id]', [ new ProductsController(), 'deleteProduct' ]);

// Dispatch all routes....
$Klein->dispatch();

?>