<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// Admin Routes
$routes->group('admin', ['filter' => 'auth', 'namespace' => 'App\Controllers\Admin'] ,function($routes)
{
	// Home Route
	$routes->get('/',    'Home::index');
	// User Routes
	$routes->group('users', function($routes)
	{
		$routes->get('/',                          'Users::index');
		$routes->get('create',                     'Users::create');
		$routes->post('store',                     'Users::store');
		$routes->get('edit/(:num)',                'Users::edit/$1');
		$routes->post('update/(:num)',             'Users::update/$1');
		$routes->get('delete/(:num)',              'Users::delete/$1');
		$routes->get('case-detail/(:num)',         'Users::caseDetail/$1');
	});

	$routes->group('case-receipt', function($routes)
	{
		$routes->get('/',                          'CaseReceipt::index');
		$routes->get('create',                     'CaseReceipt::create');
		$routes->post('store',                     'CaseReceipt::store');
	});
});


// Auth Routes
$routes->get('login',   'Auth\Login::index');
$routes->post('login',  'Auth\Login::action');
$routes->get('logout',  'Auth\Login::logout', ['filter' => 'auth']);


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
