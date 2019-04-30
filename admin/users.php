<?php
/**
 * 网站设置管理
 */

// 载入脚本
// ========================================

require '../functions.php';

// 访问控制
// ========================================

// 获取登录用户信息
tang_get_current_user();

// 处理表单请求
// ========================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $sql = sprintf("update users set nickname = '%s', intro = '%s', avatar = '%s'", $_POST['nickname'], $_POST['intro'], $_POST['avatar']);
  tang_execute($sql);
}

// 查询数据
// ========================================

$users = tang_query('select * from users')[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="../static/css/admin.css">
  <script src="../static/js/nprogress.js"></script>
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
          <div class="content-body" style="display: flex;">
          <form action="./users.php" method="post" style="width: 100%">
            <div style="width: 50%; float: left;">
                <div>
                  <div class="user-form-item-label user-col-sm">
                    <label for="email" title="邮箱"><span>邮箱</span></label>
                  </div>
                  <div class="user-col-md">
                    <div class="add-form-item-control">
                      <input type="text" id="email" name="email" class="add-input" value="<?php echo $users['email']; ?>">
                    </div>
                  </div>
                </div>
                <div>
                  <div class="user-form-item-label user-col-sm">
                    <label for="nickname" title="昵称"><span>昵称</span></label>
                  </div>
                  <div class="user-col-md">
                    <div class="add-form-item-control">
                      <input type="text" id="nickname" name="nickname" class="add-input" value="<?php echo $users['nickname']; ?>">
                    </div>
                  </div>
                </div>
                <div>
                  <div class="user-form-item-label user-col-sm">
                    <label for="intro" title="个人简介"><span>个人简介</span></label>
                  </div>
                  <div class="user-col-md">
                    <div class="add-form-item-control">
                      <textarea rows="10" id="intro" name="intro" class="add-input"><?php echo $users['intro']; ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="user-col-sm" style="margin-top: 24px;">
                  <button type="submit" class="form-btn form-btn-primary">
                    <span>更新基本信息</span>
                  </button>
                </div>
            </div>

            <div style="width: 50%; float: left;">
              <div>
                <div class="user-form-item-label user-col-sm">
                  <label><span>头像</span></label>
                </div>
                <div class="user-form-avatar">
                  <img src="<?php echo $users['avatar']; ?>">
                </div>
              </div>
              <div class="user-col-sm" style="margin-top: 24px; text-align: center;">
                  <input id="avatar" name="avatar" type="hidden">
                  <label>
                    <input id="upload" class="add-input" type="file" style="width: 50%">
                  </label>
              </div>
            </div>
           </form>
          </div>
        </main>
        <!-- 右侧通用底部 -->
        <?php include "./inc/footer.php"; ?>
      </section>
    </section>
  </div>
  <script src="../static/js/jquery.js"></script> 
  <script src="../static/js/index.js"></script>
  <script>
    $(function () {
      // 异步上传文件
      $('#upload').on('change', function () {
        // 选择文件后异步上传文件
        var formData = new FormData()
        formData.append('file', $(this).prop('files')[0])

        // 上传图片
        $.ajax({
          url: './upload.php',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'post',
          success: function (res) {
            if (res.success) {
              $('#avatar').val(res.data)
              $('#upload').siblings('img').attr('src', res.data).fadeIn()
            } else {
              alert('上传文件失败')
            }
          }
        })
      })
    })
  </script>
</body>
</html>