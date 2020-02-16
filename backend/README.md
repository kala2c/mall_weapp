###春韵艺术培训

####后台系统

基于thinkphp5.1和layui开发

拉取项目后 
#####1.注意修改/config/database.php配置文件中的数据库配置信息
#####2.并且运行composer install
#####3.给予文件权限 775+
#####4.注意服务器配置 root指向/public
#####5.并添加重写规则：
````
        location / {    
            if (!-e $request_filename) {
               rewrite  ^(.*)$  /index.php?s=/$1  last;
               break;
            }
        }