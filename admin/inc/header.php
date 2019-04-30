        <header class="layout-header">
          <div style="width: 100%; height: 64px;display: block;">
          <div class="header-right">
            <a target="_blank" href="#" class="header-icon">
              <i class="menu-submenu-icon iconfont">&#xe782;</i>
              <span>帮助</span>
            </a>
            <a target="_blank" href="#" class="header-icon">
              <i class="menu-submenu-icon iconfont">&#xe7c6;</i>
              <span>首页</span>
            </a>
            <span class="header-icon">
              <span class="header-avatar">
                <img src="<?php echo $current_user['avatar']; ?>" alt="avatar">
              </span>
              <span><?php echo $current_user['nickname']; ?></span>
            </span>
            <a href="./logout.php" class="header-icon">
              <i class="menu-submenu-icon iconfont">&#xe78d;</i>
              <span>退出</span>
            </a>
          </div>
          </div>
        </header>