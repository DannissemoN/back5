<?php

header('Content-Type: text/html; charset=UTF-8');

session_start();

if (!empty($_SESSION['login'])) {
  session_destroy();
  
  header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();

  $errors = array();
  $errors['login'] = !empty($_COOKIE['login_error']);
  $errors['pass'] = !empty($_COOKIE['pass_error']);
  $errors['avtor'] = !empty($_COOKIE['avtor_error']);
  
  if ($errors['login']) {
      setcookie('login_error', '', 100000);
    $messages[] = '<div class="error">Заполните логин.</div>';
  }
  if ($errors['pass']) {
    setcookie('pass_error', '', 100000);
    $messages[] = '<div class="error">Заполните пароль.</div>';
  }
  if ($errors['avtor']) {
    setcookie('avtor_error', '', 100000);
    $mes = '<div class="error">Неправильный логин и/или пароль.</div>';
  }



?>
<style>
.error {
  border: 2px solid red;
}
    </style>
<?php
if (!empty($messages) && empty($mes)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
} else if (!empty($mes))
    print($mes);
?>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <meta charset="utf-8">
  <title>Авторизация</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div id="main-aside-wrapper">
        <div id="cont" class="container">
            <div id="form" class="col-12 order-lg-3 order-sm-2">
              <div id="vhod">
                <a href="index.php" ><-Назад</a>
              </div>
                <form action="" method="post">
                  Логин:
                  <br/>
                    <input name="login" <?php if ($errors['login'] || $errors['avtor']) {print 'class="error"';} ?> /><br/>
                  Пароль:
                  <br/>
                    <input name="pass" <?php if ($errors['pass'] || $errors['avtor']) {print 'class="error"';} ?>/><br/><br/>
                    <input type="submit" value="Войти" />
                </form>
            </div>
        </div>
    </div>
</body>
<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  setlocale(LC_ALL, "ru_RU.UTF-8");
  $errors = FALSE;
  if (empty($_POST['login'])) {
    setcookie('login_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  if (empty($_POST['pass'])) {
    setcookie('pass_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  $l=$_POST['login'];
  $p=md5($_POST['pass']);

  $user = 'u47606';
  $pass = '8549349';
  $db = new PDO('mysql:host=localhost;dbname=u47606', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

  $sel = $db->query("SELECT password FROM baza WHERE login = $l");
    foreach($sel as $el)
      $pas=$el['password'];
  // Если все ок, то авторизуем пользователя.
  if (!empty($l) && !empty($pas) && $p==$pas){
  $_SESSION['login'] = $_POST['login'];
  $sel = $db->query("SELECT id FROM baza WHERE login=$l");
  foreach($sel as $el){
  $id = $el['id'];}
    print $id;
    exit();
  // Записываем ID пользователя.
  $_SESSION['uid'] = $id;
  } 
  else{
    $errors= TRUE;
    setcookie('avtor_error', '1', time() + 24 * 60 * 60);
  }
  if ($errors) {
    header('Location: login.php');
    exit();
  }
  else {
    setcookie('login_error', '', 100000);
    setcookie('pass_error', '', 100000);
    setcookie('avtor_error', '', 100000);
  }
  // Делаем перенаправление.
  header('Location: ./');
}
