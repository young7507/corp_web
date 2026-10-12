# 企业站项目

> 前端基于 Vue3、PureAdmin，后端基于 ThinkPHP6，带管理后台的企业站项目。
> 包括产品中心、新闻中心、关于我们、联系我们、留言板、后台管理等功能。

## 安装步骤

1. 将项目文件上传到服务器
2. 创建数据库，导入数据库文件
3. 修改根目录配置文件 `.env`，设置数据库连接信息
4. 修改配置文件 `config/app.php`，设置网站域名等信息
5. 访问网站，完成安装
6. 网页 title、关键字、描述等信息，可在 `app/lang/zh-cn.php` 中修改

## 访问地址

| 类型 | 地址 |
|------|------|
| 前台 | `http://www.yourdomain.com/index` |
| 后台 | `http://www.yourdomain.com/kcxxadmin` |

## 目录配置

基于不同服务器的配置，网站主目录可选择在**根目录**下，或者**子目录**下（如 `/根目录/public`）。

### 选择根目录时

在配置文件 `config/app.php` 中设置 `'ispublic' => false`。

需要做以下替换操作：

**编辑 `indexstatic/index-xxxxx.js`**

| 原路径 | 替换为 |
|--------|--------|
| `/static/logo.png` | `/public/static/logo.png` |
| `/static/email2.png` | `/public/static/email2.png` |

**编辑 `public/frontindex.html`**

| 原路径 | 替换为 |
|--------|--------|
| `/indexstatic/index-CQr55OI6.js` | `/public/indexstatic/index-CQr55OI6.js` |
| `/indexstatic/index-CuvjDoJR.css` | `/public/indexstatic/index-CuvjDoJR.css` |
| `/wow.min.js` | `/public/wow.min.js` |

**编辑 `public/kcadmin.html`**

| 原路径 | 替换为 |
|--------|--------|
| `/kcxxadmin/kcadminstatic/index-BN-d3JkO.js` | `/public/kcadminstatic/index-BN-d3JkO.js` |
| `/kcxxadmin/kcadminstatic/index-BCSyA5tp.css` | `/public/kcadminstatic/index-BCSyA5tp.css` |

### 选择子目录 `/根目录/public` 时

在配置文件 `config/app.php` 中设置 `'ispublic' => true`，无需做上述替换。

## 示例网站

- 前台：<https://kcnetlv.free.je/index>
- 后台：<https://kcnetlv.free.je/kcxxadmin>