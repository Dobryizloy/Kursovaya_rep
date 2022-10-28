<?php
header('Content-Type: application/json; charset=utf-8');
require 'rb-mysql.php';
require '../db.php';

/** Инициализация подключения */
R::Setup('mysql:host='.$db['host'].';dbname='.$db['db'], $db['user'], $db['pass']);

/**
 * Проверяем статус соединения с БД
 */
if (!R::TestConnection()) {
   /**
    * Запрос ошибки, почему не удалось 
    * подключиться к БД
    */
   try{
     $db = new PDO('mysql:host='.$db['host'].';dbname='.$db['db'], $db['user'], $db['pass']);
   } catch(PDOException $e){
     $db_error = $e->getmessage();
   }

   unset($db);

   exit(json_encode([
      'ok'    => false,
      'error' => [
         'message' => "Ошибка соединения с Базой данных: {$db_error}"
      ]
   ]));
}



$request    = explode("/", $_REQUEST['request']);
$controller = $request[0];
$action     = $request[1];

//sleep(1);
switch ($controller) {

   /**
    * Модуль поставщиков
    */
   case 'suppliers':
      petSleet();
      switch ($action) {

         case 'add':
            if (@$_REQUEST['name'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Имя указано неверно или не указано."
                  ]
               ]));
            }

            /**
             * Создаем объект БД
             */
            $new = R::dispense('supplier');
            $new->name = $_REQUEST['name'];
            $new->ts = time();

            if ($id = R::store($new)) {
               exit(json_encode([
                  'ok'    => true,
                  'data' => [
                     'id' => $id
                  ]
               ]));
            }else{
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Не удалось добавить объект в БД"
                  ]
               ]));
            }
            break;
         
         case 'get':
            $all = R::GetAll('SELECT id, name from supplier ORDER BY id DESC');

            if (count($all) == 0) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Нет записей в БД."
                  ]
               ]));
            }

            exit(json_encode([
               'ok'    => true,
               'data' => $all
            ]));
            break;

         case 'getPie':
            $all = R::GetAll('SELECT * FROM supplier');
            $all = R::convertToBeans('supplier', $all);

            $colors = [
               'rgb(255, 99, 132)',
               'rgb(54, 162, 235)',
               'rgb(255, 205, 86)',
               'rgb(75, 192, 192)',
               'rgb(201, 203, 207)',
               'rgba(255, 99, 132, 0.2)',
               'rgba(255, 159, 64, 0.2)',
               'rgba(255, 205, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(201, 203, 207, 0.2)'
            ];

            foreach ($all as $key) {
               $key->ownCarList;
            }

            $response = [
               'labels' => [],
               'datasets' => [
                  [
                     'label' => 'Авто по поставщикам',
                     'data' => [],
                     'backgroundColor' => [],
                     'hoverOffset' => 4
                  ]
               ]
            ];

            foreach ($all as $key) {
               $response['labels'][] = $key->name;
               $response['datasets'][0]['data'][] = count($key->ownCar);
               $response['datasets'][0]['backgroundColor'][] = $colors[rand(0, count($colors) - 1)];
            }

            exit(json_encode([
               'ok' => true,
               'data' => $response
            ]));
            break;

         case 'edit':
            if (@$_REQUEST['col'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Наименование столбца не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['value'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Новое значение не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['id'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "id не указан."
                  ]
               ]));
            }

            $ed = R::load('supplier', $_REQUEST['id']);

            if (!$ed->id) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Запись с id '{$_REQUEST['id']}' не найдена."
                  ]
               ]));
            }

            if (empty($ed->{$_REQUEST['col']})) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Колонка '{$_REQUEST['col']}' не найдена."
                  ]
               ]));
            }

            $ed->{$_REQUEST['col']} = $_REQUEST['value'];

            R::store($ed);

            exit(json_encode([
               'ok'    => true
            ]));

            break;

         case 'remove':
            if (@$_REQUEST['id'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "id не указан."
                  ]
               ]));
            }

            $ed = R::load('supplier', $_REQUEST['id']);

            if (!$ed->id) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Запись с id '{$_REQUEST['id']}' не найдена."
                  ]
               ]));
            }

            R::trash($ed);

            exit(json_encode([
               'ok'    => true
            ]));

            break;

         default:
            exit(json_encode([
               'ok'    => false,
               'error' => [
                  'message' => "Метод '{$action}' в контроллере '{$controller}' не найден."
               ]
            ]));
            break;
      }

      break;
   
   /**
    * Модуль Услуг
    */
   case 'services':
      petSleet();
      switch ($action) {
         case 'add':
            if (@$_REQUEST['name'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Имя указано неверно или не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['price'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Цена указано неверно или не указано."
                  ]
               ]));
            }

            $price = round($_REQUEST['price'], 2);

            /**
             * Создаем объект БД
             */
            $new = R::dispense('service');
            $new->name = $_REQUEST['name'];
            $new->price = $price;
            $new->ts = time();

            if ($id = R::store($new)) {
               exit(json_encode([
                  'ok'    => true,
                  'data' => [
                     'id' => $id
                  ]
               ]));
            }else{
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Не удалось добавить объект в БД"
                  ]
               ]));
            }
            break;
         
         case 'get':
            $all = R::GetAll('SELECT id, name, price from service ORDER BY id DESC');

            if (count($all) == 0) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Нет записей в БД."
                  ]
               ]));
            }

            exit(json_encode([
               'ok'    => true,
               'data' => $all
            ]));
            break;

         case 'edit':
            if (@$_REQUEST['col'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Наименование столбца не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['value'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Новое значение не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['id'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "id не указан."
                  ]
               ]));
            }

            $ed = R::load('service', $_REQUEST['id']);

            if (!$ed->id) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Запись с id '{$_REQUEST['id']}' не найдена."
                  ]
               ]));
            }

            if (empty($ed->{$_REQUEST['col']})) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Колонка '{$_REQUEST['col']}' не найдена."
                  ]
               ]));
            }

            $ed->{$_REQUEST['col']} = $_REQUEST['value'];

            R::store($ed);

            exit(json_encode([
               'ok'    => true
            ]));

            break;

         case 'remove':
            if (@$_REQUEST['id'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "id не указан."
                  ]
               ]));
            }

            $ed = R::load('service', $_REQUEST['id']);

            if (!$ed->id) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Запись с id '{$_REQUEST['id']}' не найдена."
                  ]
               ]));
            }

            R::trash($ed);

            exit(json_encode([
               'ok'    => true
            ]));

            break;

         default:
            exit(json_encode([
               'ok'    => false,
               'error' => [
                  'message' => "Метод '{$action}' в контроллере '{$controller}' не найден."
               ]
            ]));
            break;
      }

      break;

   /**
    * Модуль Авто
    */ 
   case 'auto':
      petSleet();
      switch ($action) {
         case 'add':
            
            if (@$_REQUEST['name'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Имя указано неверно или не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['factory'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "id завода указано неверно или не указано."
                  ]
               ]));
            }

            /** Получение завода по id */
            $factory = R::load('supplier', $_REQUEST['factory']);

            if (!$factory->id) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Завод с id '{$_REQUEST['factory']}' не найден."
                  ]
               ]));
            }

            if (@$_REQUEST['mark'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Марка указана неверно или не указана."
                  ]
               ]));
            }

            if (@$_REQUEST['model'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Модель указана неверно или не указана."
                  ]
               ]));
            }

            if (@$_REQUEST['mileage'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Пробег указан неверно или не указан."
                  ]
               ]));
            }

            if (@$_REQUEST['price'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Цена указана неверно или не указана."
                  ]
               ]));
            }

            if (@$_REQUEST['color'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Цвет указан неверно или не указан."
                  ]
               ]));
            }

            if (@$_REQUEST['year'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Год выпуска указан неверно или не указан."
                  ]
               ]));
            }


            $car = R::dispense('car');

            $car->name    = $_REQUEST['name'];
            $car->mark    = $_REQUEST['mark'];
            $car->model   = $_REQUEST['model'];
            $car->mileage = $_REQUEST['mileage'];
            $car->price   = $_REQUEST['price'];
            $car->color   = $_REQUEST['color'];
            $car->year    = $_REQUEST['year'];
            $car->ts      = time();

            $factory->ownCarList[] = $car;

            R::store($factory);

            exit(json_encode([
               'ok'    => true
            ]));


            break;
         
         case 'get':

            $pat = array(
               ':color' => "%".$_REQUEST['color']."%",
               ':mark'  => "%".$_REQUEST['mark']."%",
               ':year'  => (@$_REQUEST['year'] == '') ? 0 : $_REQUEST['year'],
               ':price'  => (@$_REQUEST['pricemin'] == '') ? 0 : $_REQUEST['pricemin'],
               ':mileage'  => (@$_REQUEST['mileagemin'] == '') ? 0 : $_REQUEST['mileagemin']
            );

            /**
             * Максимальная цена
             */
            if (@$_REQUEST['pricemax'] != '') {
               $pat[':pricemax'] = $_REQUEST['pricemax'];
               $max_price_sql = 'AND price <= :pricemax ';
            }else{
               $max_price_sql = '';
            }

            /**
             * Максимальный пробег
             */
            if (@$_REQUEST['mileagemax'] != '') {
               $pat[':mileagemax'] = $_REQUEST['mileagemax'];
               $max_mileage_sql = 'AND mileage <= :mileagemax ';
            }else{
               $max_mileage_sql = '';
            }


            $all = R::GetAll('SELECT * from car WHERE color LIKE :color AND mark LIKE :mark AND year >= :year AND price >= :price AND mileage >= :mileage '.$max_price_sql.$max_mileage_sql.'ORDER BY id DESC', $pat);

            $all = R::ConvertToBeans('car', $all);

            foreach ($all as $key) {
               $key->supplier;
            }

            if (count($all) == 0) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Нет записей в БД."
                  ]
               ]));
            }

            exit(json_encode([
               'ok'    => true,
               'data' => $all
            ]));
            break;

            break;

         default:
            exit(json_encode([
               'ok'    => false,
               'error' => [
                  'message' => "Метод '{$action}' в контроллере '{$controller}' не найден."
               ]
            ]));
            break;
      }

      break;

   /**
    * Модуль Продаж
    */ 
   case 'order':
      petSleet();
      switch ($action) {
         case 'add':

            if (@$_REQUEST['name'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Имя покупателя указано неверно или не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['sname'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Фамилия покупателя указано неверно или не указано."
                  ]
               ]));
            }

            if (@$_REQUEST['pat'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Отчество покупателя указано неверно или не указано."
                  ]
               ]));
            }


            /**
             * Если нет ни авто, ни услуг, выход
             */
            if (@($_REQUEST['serv'] == '') && ($_REQUEST['car-id'] == 0)) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Не выбрано ни одного элемента оплаты."
                  ]
               ]));
            }


            /**
             * Если есть машина, ищем машину
             */
            if ($_REQUEST['car-id'] != 0) {
               $car = R::load('car', $_REQUEST['car-id']);
               if (!$car->id) {
                  exit(json_encode([
                     'ok'    => false,
                     'error' => [
                        'message' => "Авто из списка не найдено."
                     ]
                  ]));
               }
            }

            /**
             * Проверяем услуги
             */
            if (@($_REQUEST['serv'] != '') and (count($_REQUEST['serv']) > 0)) {
               foreach ($_REQUEST['serv'] as $key) {
                  if (!R::load('service', $key)->id) {
                     exit(json_encode([
                        'ok'    => false,
                        'error' => [
                           'message' => "Услуга с id '{$key}' не найдена."
                        ]
                     ]));
                  }
               }
            }

            /**
             * Создаем клиента
             */
            $client          = R::dispense('client');
            $client->name    = $_REQUEST['name'];
            $client->sname   = $_REQUEST['sname'];
            $client->pat     = $_REQUEST['pat'];
            $client->ts      = time();


            /**
             * Создаем ордер
             */
            $new_order          = R::dispense('order');
            $new_order->ts      = time();

            $total = 0;
            if ($car->id) {
               $new_order->sharedCar[] = $car;
               $total += $car->price;
            }

            if (@($_REQUEST['serv'] != '') and (count($_REQUEST['serv']) > 0)) {
               foreach ($_REQUEST['serv'] as $key) {
                  $serv = R::load('service', $key);
                  
                  $new_order->sharedService[] = $serv;
                  $total += $serv->price;
               }
            }

            $new_order->total = $total;
            
            $client->ownOrderList[] = $new_order;
            
            R::store($client);

            exit(json_encode([
               'ok'    => true,
               'data' => [
                  'total' => $total
               ]
            ]));

            break;
         
         case 'get':
            
            if (@$_REQUEST['id'] == '') {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "id ордера указано неверно или не указано."
                  ]
               ]));
            }

            $order = R::load('order', $_REQUEST['id']);

            if (!$order->id) {
               exit(json_encode([
                  'ok'    => false,
                  'error' => [
                     'message' => "Ордер с id '{$_REQUEST['id']}' не найден."
                  ]
               ]));
            }

            $order->sharedService;
            $order->sharedCar;
            $order->client;

            exit(json_encode([
               'ok' => true,
               'data' => $order
            ]));

            break;

         default:
            exit(json_encode([
               'ok'    => false,
               'error' => [
                  'message' => "Метод '{$action}' в контроллере '{$controller}' не найден."
               ]
            ]));
            break;
      }

      break;

   /**
    * Модуль Пользователей
    */ 
   case 'users':
      
      switch ($action) {
         case 'getName':
            
            $values = R::GetAll("SELECT name FROM client WHERE name LIKE ?", array("%".$_REQUEST['query']."%"));
            $response = array();

            foreach ($values as $key) {
               $response['suggestions'][] = [
                  'value' => $key['name'],
                  'data' => $key['name']
               ];
            }
            exit(json_encode($response));

            break;

         case 'getSname':
            
            $values = R::GetAll("SELECT sname FROM client WHERE sname LIKE ?", array("%".$_REQUEST['query']."%"));
            $response = array();

            foreach ($values as $key) {
               $response['suggestions'][] = [
                  'value' => $key['sname'],
                  'data' => $key['sname']
               ];
            }
            exit(json_encode($response));

            break;


         case 'getPat':
            
            $values = R::GetAll("SELECT pat FROM client WHERE pat LIKE ?", array("%".$_REQUEST['query']."%"));
            $response = array();

            foreach ($values as $key) {
               $response['suggestions'][] = [
                  'value' => $key['pat'],
                  'data' => $key['pat']
               ];
            }
            exit(json_encode($response));

            break;

         
         default:
            exit(json_encode([
               'ok'    => false,
               'error' => [
                  'message' => "Метод '{$action}' в контроллере '{$controller}' не найден."
               ]
            ]));
            break;
      }

      break;


   default:
      exit(json_encode([
         'ok'    => false,
         'error' => [
            'message' => "Контроллер '{$controller}' не найден."
         ]
      ]));
      break;
}

function petSleet()
{
   sleep(1);
}