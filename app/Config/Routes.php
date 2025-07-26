<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

$routes->get('/course/access/(:num)', 'Course::access/$1');
$routes->match(['get', 'post'], '/course/enroll/(:num)', 'Course::enroll/$1');

$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
// $routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/admin', 'Dashboard::admin', ['filter' => 'auth']);
$routes->get('/dashboard/member', 'Dashboard::member', ['filter' => 'auth']);


$routes->get('/material/upload/(:num)', 'Material::upload/$1', ['filter' => 'auth']);
$routes->post('/material/upload/(:num)', 'Material::upload/$1', ['filter' => 'auth']);
$routes->get('/material/view/(:num)', 'Material::view/$1', ['filter' => 'auth']);
$routes->get('material/edit/(:num)', 'Material::edit/$1', ['filter' => 'auth']);
$routes->post('material/edit/(:num)', 'Material::edit/$1', ['filter' => 'auth']);
$routes->post('material/delete/(:num)', 'Material::delete/$1', ['filter' => 'auth']);

$routes->get('/course/(:num)', 'Course::detail/$1');

$routes->get('/admin/approval', 'Admin::pendingMaterials', ['filter' => 'auth']);
$routes->get('/admin/approve/(:num)', 'Admin::approve/$1', ['filter' => 'auth']);

$routes->get('/uploads/images/(:any)', 'FileController::image/$1');
// tidak harus login untuk akses gambar
$routes->get('uploads/public/images/(:any)', 'FileController::publicImage/$1');

$routes->get('my-course/create', 'UserCourse::create');
$routes->post('my-course/create', 'UserCourse::store');
$routes->get('my-course', 'UserCourse::myCourses');
$routes->get('my-course/edit/(:num)', 'UserCourse::edit/$1', ['filter' => 'auth']);
$routes->post('my-course/edit/(:num)', 'UserCourse::update/$1', ['filter' => 'auth']);


$routes->post('profile/update', 'Profile::update');

$routes->post('user/course/toggle-publish', 'Course::togglePublish', ['filter' => 'auth']);

$routes->post('material/comment/(:num)', 'Material::comment/$1');
$routes->post('material/comment/reply/(:num)', 'Material::reply/$1');
$routes->post('material/comment/delete/(:num)', 'Material::deleteComment/$1', ['filter' => 'auth']);

$routes->group('admin/course', ['filter' => 'role:admin'], function($routes) {
    $routes->get('/', 'AdminCourseController::index');
    $routes->get('create', 'AdminCourseController::create');
    $routes->post('store', 'AdminCourseController::store');
    $routes->get('edit/(:num)', 'AdminCourseController::edit/$1');
    $routes->post('update/(:num)', 'AdminCourseController::update/$1');
    $routes->get('delete/(:num)', 'AdminCourseController::delete/$1');
    $routes->get('materials/(:num)', 'Material::upload/$1'); // redirect ke upload materi
    $routes->post('materials/(:num)', 'Material::upload/$1', ['filter' => 'auth']);

    $routes->post('toggle-status', 'AdminCourseController::toggleStatus');

});
