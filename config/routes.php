<?php

// ============================================================
// Quy ước đặt $roles:
//   []              => public (không cần đăng nhập)
//   ['*']           => phải đăng nhập, không giới hạn role
//   ['manager']     => chỉ quản lý
//   ['student']     => chỉ sinh viên
//   ['manager','student'] => cả hai
// ============================================================

// --- Auth (public) ---
$router->get('/auth/login',  'AuthController@loginForm');
$router->post('/auth/login', 'AuthController@login');
$router->get('/auth/logout', 'AuthController@logout',     ['*']);

// --- Dashboard ---
$router->get('/', 'DashboardController@index', ['*']);

// --- Student ---
// Manager: full CRUD | Student: chỉ xem hồ sơ bản thân
$router->get('/student',           'StudentController@index',  ['manager']);
$router->get('/student/create',    'StudentController@create', ['manager']);
$router->post('/student/create',   'StudentController@create', ['manager']);
$router->get('/student/edit/{id}', 'StudentController@edit',   ['manager']);
$router->post('/student/edit/{id}','StudentController@edit',   ['manager']);
$router->get('/student/delete/{id}','StudentController@delete',['manager']);
$router->get('/student/profile',   'StudentController@profile',['student']); // Hồ sơ bản thân

// --- Room ---
// Manager: full CRUD | Student: chỉ xem phòng mình đang ở
$router->get('/room',              'RoomController@index',    ['manager']);
$router->get('/room/create',       'RoomController@create',   ['manager']);
$router->post('/room/create',      'RoomController@create',   ['manager']);
$router->get('/room/edit/{id}',    'RoomController@edit',     ['manager']);
$router->post('/room/edit/{id}',   'RoomController@edit',     ['manager']);
$router->get('/room/delete/{id}',  'RoomController@delete',   ['manager']);
$router->get('/room/my-room',      'RoomController@myRoom',   ['student']); // Phòng đang ở

// --- Contract ---
// Chỉ manager quản lý hợp đồng
$router->get('/contract',              'ContractController@index',  ['manager']);
$router->get('/contract/create',       'ContractController@create', ['manager']);
$router->post('/contract/create',      'ContractController@create', ['manager']);
$router->get('/contract/edit/{id}',    'ContractController@edit',   ['manager']);
$router->post('/contract/edit/{id}',   'ContractController@edit',   ['manager']);
$router->get('/contract/delete/{id}',  'ContractController@delete', ['manager']);

// --- Bill ---
// Manager: full CRUD | Student: chỉ xem hóa đơn phòng mình
$router->get('/bill',              'BillController@index',   ['manager']);
$router->get('/bill/create',       'BillController@create',  ['manager']);
$router->post('/bill/create',      'BillController@create',  ['manager']);
$router->get('/bill/edit/{id}',    'BillController@edit',    ['manager']);
$router->post('/bill/edit/{id}',   'BillController@edit',    ['manager']);
$router->get('/bill/delete/{id}',  'BillController@delete',  ['manager']);
$router->get('/bill/export/{id}',  'BillController@export',  ['manager']);
$router->get('/bill/my-bills',     'BillController@myBills', ['student']);

// --- Notice ---
// Manager: full CRUD | Student: chỉ xem thông báo liên quan đến mình
$router->get('/notice',              'NoticeController@index',   ['manager', 'student']);
$router->get('/notice/create',       'NoticeController@create',  ['manager']);
$router->post('/notice/create',      'NoticeController@create',  ['manager']);
$router->get('/notice/edit/{id}',    'NoticeController@edit',    ['manager']);
$router->post('/notice/edit/{id}',   'NoticeController@edit',    ['manager']);
$router->get('/notice/delete/{id}',  'NoticeController@delete',  ['manager']);
?>
