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
  if (!empty($_POST['site_logo'])) {
    tang_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_logo\'', $_POST['site_logo']));
  }
  if (!empty($_POST['site_name'])) {
    tang_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_name\'', $_POST['site_name']));
  }
  if (!empty($_POST['site_description'])) {
    tang_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_description\'', $_POST['site_description']));
  }
  if (!empty($_POST['site_keywords'])) {
    tang_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_keywords\'', $_POST['site_keywords']));
  }
}

// 查询数据
// ========================================

$data = tang_query('select * from options');
$options = array();

foreach ($data as $item) {
  $options[$item['key']] = $item['value'];
}

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
          <div class="content-body">
            <form action="./settings.php" method="post">
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="site_logo"><span>网站图标</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <input id="site_logo" name="site_logo" type="hidden">
                    <label>
                      <input id="upload" class="add-input" type="file">
                      <img style="width: 20%" src="<?php echo $options['site_logo']; ?>">
                    </label>
                  </div>
                </div>
              </div>
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="site_name"><span>网站名称</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <input id="site_name" name="site_name" placeholder="网站名称" type="text" class="add-input" value="<?php echo $options['site_name']; ?>">
                  </div>
                </div>
              </div>
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="site_description"><span>网站描述</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <textarea id="site_description" name="site_description" placeholder="网站描述" rows="5" class="add-input"><?php echo $options['site_description']; ?></textarea>
                  </div>
                </div>
              </div>
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="site_keywords"><span>网站关键词</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <input id="site_keywords" name="site_keywords" placeholder="网站关键词" type="text" class="add-input" value="<?php echo $options['site_keywords']; ?>">
                  </div>
                </div>
              </div>
              <div class="add-col-sm add-col-sm-offset">
                <button type="submit" class="form-btn form-btn-primary">
                  <span>保存设置</span>
                </button>
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
              $('#site_logo').val(res.data)
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