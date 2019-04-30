<?php
/**
 * 新增文章
 */

// 载入脚本
// ========================================

require '../functions.php';

// 访问控制
// ========================================

// 获取登录用户信息
tang_get_current_user();

// 处理提交请求
// ========================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 数据校验
  // ------------------------------

  if (empty($_POST['title'])
    || empty($_POST['created'])
    || empty($_POST['category'])
    || empty($_POST['content'])
    || empty($_POST['status'])) {
    // 缺少必要数据
    $message = '请完整填写所有内容';
  } else {

    // 接收数据
    // ------------------------------

    $title = $_POST['title'];
    $created = $_POST['created'];
    $category_id = $_POST['category'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $user_id = $current_user['id'];

    // 保存数据
    // ------------------------------

    // 拼接查询语句
    $sql = sprintf(
      "insert into posts values (null, '%s', '%s', %d, '%s', '%s', %d)",
      $title,
      $created,
      $category_id,
      $content,
      $status,
      $user_id
    );
    // 执行 SQL 保存数据
    if (tang_execute($sql) > 0) {
      // 保存成功 跳转
      header('Location: ./posts.php');
      exit;
    } else {
      // 保存失败
      $message = '保存失败，请重试';
    }
  }
}

// 查询数据
// ========================================

// 查询全部分类数据
$categories = tang_query('select * from categories');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="../static/css/admin.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
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

        <?php if (isset($message)) : ?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $message; ?>
        </div>
        <?php endif; ?>

        <main class="layout-content">
          <div class="content-body">
            <form  action="./post-add.php" method="post" enctype="multipart/form-data">
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="title" title=""><span>标题</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <input id="title" name="title" placeholder="给目标起个名字" type="text" class="add-input" value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>">
                  </div>
                </div>
              </div>
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="created" title=""><span>发布时间</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <input id="created" name="created" class="add-input" placeholder="选择发布时间" type="datetime-local" value="<?php echo isset($_POST['created']) ? $_POST['created'] : ''; ?>">
                  </div>
                </div>
              </div>
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="category" title=""><span>分类</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <select id="category" class="form-control" name="category">
                      <?php foreach ($categories as $item) { ?>
                      <option class="add-input" value="<?php echo $item['id']; ?>"<?php echo isset($_POST['category']) && $_POST['category'] == $item['id'] ? ' selected' : ''; ?>><?php echo $item['name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="content" title=""><span>内容</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control">
                    <textarea id="content" name="content" placeholder="请输入目标内容，支持MarkDown语法" rows="10" class="add-input"><?php echo isset($_POST['content']) ? $_POST['content'] : ''; ?></textarea>
                  </div>
                </div>
              </div>
              <div class="add-form-item">
                <div class="add-form-item-label add-col-sm">
                  <label for="status" title=""><span>状态</span></label>
                </div>
                <div class="add-col-md">
                  <div class="add-form-item-control" id="status">
                    <label class="add-radio-wrapper">
                      <span class="add-radio add-radio-checked">
                        <input type="radio" name="status" class="add-radio-input"  value="published"<?php echo isset($_POST['status']) && $_POST['status'] == 'published' ? ' selected' : ''; ?>>
                        <span class="add-radio-inner"></span>
                      </span>
                      <span>
                        发布
                      </span>
                    </label>
                    <label class="add-radio-wrapper">
                      <span class="add-radio">
                        <input type="radio" name="status" class="add-radio-input" value="drafted"<?php echo isset($_POST['status']) && $_POST['status'] == 'drafted' ? ' selected' : ''; ?>>
                        <span class="add-radio-inner"></span>
                      </span>
                      <span>
                        草稿
                      </span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="add-col-sm add-col-sm-offset">
                <button type="submit" class="form-btn form-btn-primary">
                  <span>提 交</span>
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
  <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
  <script>
    var simplemde = new SimpleMDE({ element: document.getElementById("content") });
  </script>
</body>
</html>