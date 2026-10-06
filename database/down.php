<?php

require_once __DIR__ . "/create_users_table.php";
require_once __DIR__ . "/create_api_tokens_table.php";
require_once __DIR__ . "/create_authors_table.php";
require_once __DIR__ . "/create_books_table.php";
require_once __DIR__ . "/create_orders_items_table.php";
require_once __DIR__ . "/create_orders_table.php";


create_api_tokens_table::down();
create_orders_items_table::down();
create_orders_table::down();
create_books_table::down();
create_authors_table::down();
create_users_table::down();