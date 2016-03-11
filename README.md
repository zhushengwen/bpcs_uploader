bpcs_uploader
=============

百度pcs上传脚本, 相比原项目添加了目录中文件递归上传的脚本<br>
## Usage 
1.首先按照原项目的配置好.原项目:<a href="https://github.com/oott123/bpcs_uploader">点击进入</a><br>
2.增加可执行权限
```shell
chmod u+x ./bpcs_uploader.php
chmod u+x ./kb_upload.php
```
3.上传一个文件(夹)
```shell
./kb_upload.php /path/to/dir/files
```
4.上传多个文件(夹)
```shell
./kb_upload.php /path1/to1/dir1/files1 /path2/to2/dir2/files2 .....
```