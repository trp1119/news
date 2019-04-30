<?php
/**
 * 分类管理
 */

// 载入脚本
// ========================================

require '../functions.php';

// 访问控制
// ========================================

// 获取登录用户信息
tang_get_current_user();

// 处理表单提交
// ========================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 表单校验
  if (empty($_POST['slug']) || empty($_POST['name'])) {
    // 表单不合法，提示错误信息（可以分开判断，提示更加具体的信息）
    $message = '完整填写表单内容';
  } else if (empty($_POST['id'])) {
    // 表单合法，数据持久化（通俗说法就是保存数据）
    // 没有提交 ID 代表新增，则新增数据
    $sql = sprintf("insert into categories values (null, '%s', '%s')", $_POST['slug'], $_POST['name']);
    // 响应结果
    $message = tang_execute($sql) > 0 ? '保存成功' : '保存失败';
  } else {
    // 提交 ID 就代表是更新，则更新数据
    $sql = sprintf("update categories set slug = '%s', name = '%s' where id = %d", $_POST['slug'], $_POST['name'], $_POST['id']);
    // 响应结果
    $message = tang_execute($sql) > 0 ? '保存成功' : '保存失败';
  }
}

// 查询数据
// ========================================

// 查询全部分类信息
$categories = tang_query('select * from categories');


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="../static/css/admin.css">
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

            <div>

              <form action="./categories.php" class="content-select" method="post">
                <input id="id" name="id" type="hidden">
                <div class="content-select-item">
                  <div class="content-form-item">
                    <div class="content-form-item-label">
                      <label for="name" title="分类名称">分类名称</label>
                    </div>
                    <div class="content-form-item-control-wrapper">
                      <input placeholder="请输入分类名称" type="text" id="name" name="name" class="content-input" value="">
                    </div>
                  </div>
                </div>
                <div class="content-select-item">
                  <div class="content-form-item">
                    <div class="content-form-item-label">
                      <label for="name" title="分类别名">分类别名</label>
                    </div>
                    <div class="content-form-item-control-wrapper">
                      <input placeholder="请输入分类别名" type="text" id="name" name="slug" class="content-input" value="">
                    </div>
                  </div>
                </div>
                <div class="content-select-item">
                  <span>
                    <button type="submit" class="form-btn form-btn-primary">
                      <span>添加</span>
                    </button>
                    <button type="submit" class="form-btn form-btn-primary" style="margin-left: 8px;display: none">
                      <span>重置</span>
                    </button>
                    <button type="button" class="btn-delete form-btn form-btn-danger" style="margin-left: 8px; display: none">
                      <a href="./categories-delete.php" style="color: #fff">批量删除</a>
                    </button>
                  </span>
                </div>
              </form>

            </div>
            <div class="content-table">
              <table>
                <thead class="content-table-thead">
                  <tr>
                    <th>
                      <input type="checkbox" class="content-table-checkbox">
                     <!--  <label><span class="content-table-checkbox"></span></span></label> -->
                    </th>
                    <th><span>名称</span></th>
                    <th><span>别名</span></th>
                    <th><span>操作</span></th>
                  </tr>
                </thead>
                <tbody class="content-table-tbody">
                  <?php foreach ($categories as $item) { ?>
                  <tr data-id="<?php echo $item['id']; ?>">
                    <td>
                      <input type="checkbox" class="content-table-checkbox">
                    </td>
                    <td><?php echo $item['name'] ?></td>
                    <td><?php echo $item['slug'] ?></td>
                    <td>
                      <!-- <a href="javascript:;">编辑</a>
                      <div class="content-divider">
                      </div> -->
                      <a href="./category-delete.php?id=<?php echo $item['id']; ?>">删除</a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
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
      // 获取所需操作的界面元素
      var $btnDelete = $('.btn-delete')
      var $thCheckbox = $('th > input[type=checkbox]')
      var $tdCheckbox = $('td > input[type=checkbox]')

      // 用于记录界面上选中行的数据 ID
      var checked = []

      /**
       * 表格中的复选框选中发生改变时控制删除按钮的链接参数和显示状态
       */
      $tdCheckbox.on('change', function () {
        var $this = $(this)

        // 为了可以在这里获取到当前行对应的数据 ID
        // 在服务端渲染 HTML 时，给每一个 tr 添加 data-id 属性，记录数据 ID
        // 这里通过 data-id 属性获取到对应的数据 ID
        var id = parseInt($this.parent().parent().data('id'))

        // ID 如果不合理就忽略
        if (!id) return

        if ($this.prop('checked')) {
          // 选中就追加到数组中
          checked.push(id)
        } else {
          // 未选中就从数组中移除
          checked.splice(checked.indexOf(id), 1)
        }
        console.log(checked)

        // 有选中就显示操作按钮，没选中就隐藏
        checked.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut()

        // 批量删除按钮链接参数
        // search 是 DOM 标准属性，用于设置或获取到的是 a 链接的查询字符串
        $('.btn-delete > a').prop('search', '?id=' + checked.join(','))
      })

      /**
       * 全选 / 全不选
       */
      $thCheckbox.on('change', function () {
        var checked = $(this).prop('checked')
        // 设置每一行的选中状态并触发 上面 👆 的事件
        $tdCheckbox.prop('checked', checked).trigger('change')
      })
    })
  </script>
</body>
</html>