<?php
/**
 * status code
 * create by XinyuLi
 * @since 06/06/2020 13:52
 */

return [
  "success" => 1,
  "error" => 0,
  "not_login" => -1,
  "user_is_register" => -2,
  "action_not_found" => -3,
  "function_not_found" => -3,
  "controller_not_found" => -4,

  "mysql" => [
    "table_normal" => 1, //ok
    "table_pending" => 0, //verify
    "table_delete" => 99, //delete
  ],

];
