<?php
/**
 * 文章管理
 */

// 载入脚本
// ========================================

require '../functions.php';

// 访问控制
// ========================================

// 获取登录用户信息
tang_get_current_user();

// 处理筛选逻辑
// ========================================

// 数据库查询筛选条件（默认为 1 = 1，相当于没有条件）
$where = '1 = 1';

// 记录本次请求的查询参数
$query = '';

// 状态筛选
if (isset($_GET['s']) && $_GET['s'] != 'all') {
  $where .= sprintf(" and posts.status = '%s'", $_GET['s']);
  $query .= '&s=' . $_GET['s'];
}

// 分类筛选
if (isset($_GET['c']) && $_GET['c'] != 'all') {
  $where .= sprintf(" and posts.category_id = %d", $_GET['c']);
  $query .= '&c=' . $_GET['c'];
}

// 处理分页
// ========================================

// 定义每页显示数据量（一般把这一项定义到配置文件中）
$size = 10;

// 获取分页参数 没有或传过来的不是数字的话默认为 1
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? intval($_GET['p']) : 1;

if ($page <= 0) {
  // 页码小于 1 没有任何意义，则跳转到第一页
  header('Location: ./posts.php?p=1' . $query);
  exit;
}

// 查询总条数
$total_count = intval(tang_query('select count(1)
from posts
inner join users on posts.user_id = users.id
inner join categories on posts.category_id = categories.id
where ' . $where)[0][0]);

// 计算总页数
$total_pages = ceil($total_count / $size);

if ($page > $total_pages) {
  // 超出范围，则跳转到最后一页
  header('Location: ./posts.php?p=' . $total_pages . $query);
  exit;
}

// 查询数据
// ========================================

// 查询文章数据
$posts = tang_query(sprintf('select
  posts.id,
  posts.title,
  posts.created,
  posts.status,
  categories.name as category_name,
  users.nickname as author_name
from posts
inner join users on posts.user_id = users.id
inner join categories on posts.category_id = categories.id
where %s
order by posts.created desc
limit %d, %d', $where, ($page - 1) * $size, $size));

// 查询全部分类数据
$categories = tang_query('select * from categories');

// 数据过滤函数
// ========================================

/**
 * 将英文状态描述转换为中文
 * @param  string $status 英文状态
 * @return string         中文状态
 */
function convert_status ($status) {
  switch ($status) {
    case 'drafted':
      return '草稿';
    case 'published':
      return '已发布';
    case 'trashed':
      return '回收站';
    default:
      return '未知';
  }
}

/**
 * 格式化日期
 * @param  string $created 时间字符串
 * @return string          格式化后的时间字符串
 */
function format_date ($created) {
  // 设置默认时区！！！ PRC 指的是中华人民共和国
  date_default_timezone_set('PRC');

  // 转换为时间戳
  $timestamp = strtotime($created);

  // 格式化并返回 由于 r 是特殊字符，所以需要 \r 转义一下
  return date('Y年m月d日 H:i:s', $timestamp);
}

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
              <form action="" class="content-select">
                <div class="content-select-item">
                  <div class="content-form-item">
                    <div class="content-form-item-label">
                      <label for="status" title="所有分类">所有分类</label>
                    </div>
                    <div id="status" class="content-form-item-control-wrapper">
                      <div class="form-select">
                        <div class="form-select-content">
                          <div class="form-select-selection" style="display: block;">
                          
                            <select name="c" class="select-opt">
                              <option value="all">所有分类</option>
                              <?php foreach ($categories as $item) { ?>
                              <option value="<?php echo $item['id']; ?>"<?php echo isset($_GET['c']) && $_GET['c'] == $item['id'] ? ' selected' : ''; ?>><?php echo $item['name']; ?></option>
                              <?php } ?>
                            </select>

                          </div>
                        </div>
                        <span class="content-select-arrow">
                          <i aria-label="图标: down" class="iconfont">&#xe7ec;</i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="content-select-item">
                  <div class="content-form-item">
                    <div class="content-form-item-label">
                      <label for="status" title="所有分类">所有分类</label>
                    </div>
                    <div id="status" class="content-form-item-control-wrapper">
                      <div class="form-select">
                        <div class="form-select-content">
                          <div class="form-select-selection" style="display: block;">
                            <select name="s" class="select-opt">
                              <option value="all">所有状态</option>
                              <option value="drafted"<?php echo isset($_GET['s']) && $_GET['s'] == 'drafted' ? ' selected' : ''; ?>>草稿</option>
                              <option value="published"<?php echo isset($_GET['s']) && $_GET['s'] == 'published' ? ' selected' : ''; ?>>已发布</option>
                              <option value="trashed"<?php echo isset($_GET['s']) && $_GET['s'] == 'trashed' ? ' selected' : ''; ?>>回收站</option>
                            </select>
                          </div>
                        </div>
                        <span class="content-select-arrow">
                          <i aria-label="图标: down" class="iconfont">&#xe7ec;</i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="content-select-item">
                  <span>
                    <button type="submit" class="form-btn form-btn-primary">
                      <span>查 询</span>
                    </button>
                    <button type="button" class="btn-delete form-btn form-btn-danger" style="margin-left: 8px; display: none">
                      <a href="./post-delete.php" style="color: #fff">批量删除</a>
                    </button>
                  </span>
                </div>
              </form>
            </div>
            <div class="content-table">
              <table>
                <thead class="content-table-thead">
                  <tr>
                    <th><input type="checkbox" class="content-table-checkbox"></th>
                    <th><span>标题</span></th>
                    <th><span>作者</span></th>
                    <th><span>分类</span></th>
                    <th><span>发表时间</span></th>
                    <th><span>状态</span></th>
                    <th><span>操作</span></th>
                  </tr>
                </thead>
                <tbody class="content-table-tbody">
                  <?php foreach ($posts as $item) { ?>
                  <tr data-id="<?php echo $item['id']; ?>">
                    <td><input type="checkbox" class="content-table-checkbox"></td>
                    <td><a><?php echo $item['title']; ?></a></td>
                    <td><?php echo $item['author_name']; ?></td>
                    <td><?php echo $item['category_name']; ?></td>
                    <td><?php echo format_date($item['created']); ?></td>
                    <td>
                        <span class="content-status-text"><?php echo convert_status($item['status']); ?></span>
                    </td>
                    <td>
                      <!-- <a href="javascript:;">编辑</a>
                      <div class="content-divider">
                      </div> -->
                      <a href="./post-delete.php?id=<?php echo $item['id']; ?>">删除</a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div>
              <ul class="content-pagination">
                <?php tang_pagination($page, $total_pages, '?p=%d' . $query); ?>
              </ul>
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
  <script src="../static/js/jquery.dialog.js"></script>
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