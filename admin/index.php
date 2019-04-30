<?php
/**
 * 后台首页
 */

// 载入脚本
// ========================================

require '../functions.php';

// 访问控制
// ========================================

// 获取登录用户信息
tang_get_current_user();

// 查询数据
// ========================================

// 查询文章总数
$post_count = tang_query('select count(1) from posts')[0][0];
// select count(1) 查询出来的永远是单行单列的数据，所以[0][0]

// 查询草稿总数
$drafted_count = tang_query('select count(1) from posts where status = \'drafted\'')[0][0];

// 查询分类总数
$category_count = tang_query('select count(1) from categories')[0][0];


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="../static/css/admin.css">
  <style>
        .masked{
            display: block;
            /*width: 600px;
            height: 100px;*/
            /*渐变背景*/
            background-image: -webkit-linear-gradient(left, #3498db, #f47920 10%, #d71345 20%, #f7acbc 30%,
            #ffd400 40%, #3498db 50%, #f47920 60%, #d71345 70%, #f7acbc 80%, #ffd400 90%, #3498db);
            color: transparent; /*文字填充色为透明*/
            -webkit-text-fill-color: transparent;
            -webkit-background-clip: text;          /*背景剪裁为文字，只将文字显示为背景*/
            background-size: 200% 100%;            /*背景图片向水平方向扩大一倍，这样background-position才有移动与变化的空间*/
            /* 动画 */
            animation: masked-animation 4s infinite linear;
        }
        @keyframes masked-animation {
            0% {
                background-position: 0 0;   /*background-position 属性设置背景图像的起始位置。*/
            }
            100% {
                background-position: -100% 0;
            }
        }
    </style>
</head>
<body>
  <div>
    <section class="layout layout-sider-flex">
      <!-- 通用侧边栏 -->
      <?php include "./inc/sidebar.php"; ?>
      <!-- 右侧内容 -->
      <section class="layout">
      <!-- 右侧通用顶部 -->
        <?php include "./inc/header.php"; ?>
        <main class="layout-content">
          <div class="content-body" style="height: 250px; margin-bottom: 20px; text-align: center;">
            <span class="masked" style="font-size: 80px; font-weight: 800;">Say Less, Do More!</span>
            <button class="form-btn form-btn-primary" style="margin-top:30px">写博客</button>
          </div>
          <div class="content-body" style="width: 20%; height: 150px; float: left; margin-right: 20px; padding: 20px;">
            <p><h1>系统信息</h1></p>
            <p><h3>PHP版本：</h3><?php echo PHP_VERSION; ?></p>
            <p><h3>MySQL版本：</h3>
              <?php 
                $con = mysqli_connect('localhost','root','root'); 
                echo(mysqli_get_server_info($con)); 
                mysqli_close($con);
              ?>
            </p>
          </div>
          <div class="content-body" style="width: 20%; height: 150px; float: left; padding: 20px;">
            <p><h1>文章信息</h1></p>
            <p><h3>文章数目：</h3>
              <?php echo $post_count; ?>篇文章（<strong><?php echo $drafted_count; ?></strong>篇草稿）
            </p>
            <p><h3>分类数目：</h3>
              <?php echo $category_count; ?>个分类
            </p>
          </div>

          
          


        </main>
        <!-- 右侧通用底部 -->
        <?php include "./inc/footer.php"; ?>
      </section>
    </section>
  </div>
  <script src="../static/js/jquery.js"></script> 
  <script src="../static/js/index.js"></script>
</body>
</html>