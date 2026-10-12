https://github.com/young7507/corp_web.git

这是一个前端基于vue3、pureadmin,后端基于thinkphp6带管理后台的企业站项目，包括产品中心、新闻中心、关于我们、联系我们、留言板、后台管理等功能。

安装步骤：
1. 将项目文件上传到服务器
2. 创建数据库，导入数据库文件
3. 修改根目录配置文件.env，设置数据库连接信息
4. 修改配置文件config/app.php，设置网站域名等信息
5. 访问网站，完成安装

前台访问地址：http://www.yourdomain.com/index
后台访问地址：http://www.yourdomain.com/kcxxadmin


基于不同服务器的配置，网站主目录可选择在根目录下，或者子目录下，如：/根目录/public,如果选择在根目录，则做如下操作

在配置文件config/app.php中设置'ispublic' => false,在子目录/根目录/public设置为true。

编辑indexstatic/index-xxxxx.js
/static/logo.png替换为/public/static/logo.png
/static/email2.png替换为/public/static/email2.png

编辑public/frontindex.html
/indexstatic/index-CQr55OI6.js替换为/public/indexstatic/index-CQr55OI6.js
/indexstatic/index-CuvjDoJR.css替换为/public/indexstatic/index-CuvjDoJR.css
/wow.min.js替换为/public/wow.min.js

编辑public/kcadmin.html
/kcxxadmin/kcadminstatic/index-BN-d3JkO.js替换为/public/kcadminstatic/index-BN-d3JkO.js
/kcxxadmin/kcadminstatic/index-BCSyA5tp.css替换为/public/kcxxadmin/kcadminstatic/index-BCSyA5tp.css

如果选择在子目录/根目录/public，则

编辑public/frontindex.html
/kcxxadmin删除

编辑public/kcadmin.html
/kcxxadmin删除