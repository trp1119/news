<?php
/**
 * 登录页面
 */

// 载入配置文件
require_once '../config.php';

// 判断当前是否是 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 如果是 POST 提交则处理登录业务逻辑
  if (empty($_POST['email']) || empty($_POST['password'])) {
    // 没有完整填写表单，定义一个变量存放错误消息，在渲染 HTML 时显示到页面上
    $message = '请完整填写表单';
  } else {
    // 接收表单参数
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 邮箱与密码是否匹配（数据库查询）
    // 建立与数据的连接
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$connection) {
      // 链接数据库失败，打印错误信息，注意：生产环境不能输出具体的错误信息（不安全）
      die('<h1>Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . '</h1>');
    }

    // 根据邮箱查询用户信息，limit 是为了提高查询效率
    $result = mysqli_query($connection, sprintf("select * from users where email = '%s' limit 1", $email));

    if ($result) {
      // 查询成功，获取查询结果
      if ($user = mysqli_fetch_assoc($result)) {
        // 用户存在，密码比对
        if ($user['password'] == $password) {
          // 启用新会话或使用已有会话（打开用户的箱子，如果该用户没有箱子，给他一个新的空箱子）
          session_start();
          // 记住登录状态
          // $_SESSION['is_logged_in'] = true;
          $_SESSION['current_logged_in_user_id'] = $user['id'];
          // 匹配则跳转到 /admin/index.php
          header('Location: ./index.php');
          exit; // 结束脚本的执行
        }
      }
      $message = '邮箱与密码不匹配';
      // 释放资源
      mysqli_free_result($result);
    } else {
      // 查询失败
      $message = '邮箱与密码不匹配';
    }
    // 关闭与数据库之间的连接
    mysqli_close($connection);
  }
}
// 以下就是直接输出 HTML 内容
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>登录-TANG's WebSite</title>
  <link rel="stylesheet" href="../static/css/admin.css">
</head>
<body>
  <div class="login-container">
    <div class="login-content">
      <div class="login-user-top">
        <div class="login-user-header">
          <a href="#">
            <img class="login-user-logo" src="../static/img/logo.png" alt="logo">
            <span class="login-user-title">TANG's WebSite</span>
          </a>
        </div>
        <div class="login-user-desc">
          一个想做WEB全栈攻城狮的化工男
        </div>
      </div>
      <div class="login-user-main">
          <form action="./login.php" method="post">
            <div class="login-user-bar">账户密码登录</div>
            <?php if (isset($message)) : ?>
            <div class="alert alert-danger">
              <strong>错误！</strong><?php echo $message; ?>
            </div>
            <?php endif; ?>
            <div class="login-form-item">
              <span class="login-input-prefix">
                <i class="iconfont">&#xe7ae;</i>
              </span>
              <input placeholder="邮箱：测试账号admin@trp1119.com" type="email" id="email" name="email" class="login-input" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            </div>

            <div class="login-form-item">
              <span class="login-input-prefix">
                <i class="iconfont">&#xe7c9;</i>
              </span>
              <input placeholder="密码：测试密码admin" type="password" id="password" name="password" class="login-input">
            </div>
            <div class="login-form-item">
              <button type="submit" class="login-btn">
                <span>登录</span>
              </button>
            </div>
          </form>
      </div>
    </div>
    <footer class="login-footer">
      <div class="login-footer-links">
        <a title="home" target="_self" href="">首页</a>
        <a title="github" class="iconfont" target="_self" href="">&#xe885;</a>
        <a title="resume" target="_self" href="">简历下载</a>
      </div>
      <div>
        Copyright
        <i class="iconfont">&#xe77e;</i>
        2019 TANG's WebSite
      </div>
    </footer>
  </div>
</body>
</html>