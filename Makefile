# Dùng để build hệ thống

# Đường dẫn Framework
FRA = /WebServer/VHMIS/Framework
# Đường dẫn chứa Hệ thống VHMIS
SYS = /WebServer/VHMIS/VHMIS
# Đường dẫn Nơi cài đặt
BUILD = /WebServer/www/VHMIS
# Đường dẫn thư mục web
WBUILD = /WebServer/www/VHMIS_WWW

# Nhung khai bao duoi day danh cho viec thay doi cac pre-config trong file
# Đường dẫn web
URL = /VHMIS_WWW/
# Ten he thong
SNAME = Vhmis

# Đường dẫn của các thư viện
ZEND = /WebServer/Zend
ICNL = /WebServer/

# Build chính
build: prepare framework systems library clear
	@echo Build done

# Chuẩn bị trước khi build
prepare:
	@echo Preparing ...
	@rm -rf ${BUILD}
	@mkdir ${BUILD}
	@mkdir ${BUILD}/temp

# Build framework
framework:
	@echo Framework is building ...
	@cp -R ${FRA}/* ${BUILD}
	@sed s:WEB_PATH:${URL}: ${BUILD}/www/.htaccess > ${WBUILD}/.htaccess
	@sed s:BUILD:${BUILD}/: ${BUILD}/www/index.php > ${BUILD}/temp/index.php
	@sed s:SNAME:${SNAME}: ${BUILD}/temp/index.php > ${WBUILD}/index.php

# Build he thong
systems:
	@echo System is building ...
	@mkdir ${BUILD}/System/${SNAME}
	@cp -R ${SYS}/* ${BUILD}/System/${SNAME}
	@cp -R ${BUILD}/Config/* ${BUILD}/System/${SNAME}/Config

# Copy thu vien
library:
	@echo Copying external libs ...
	@mkdir ${BUILD}/Libs/Zend
	@cp -R ${ZEND}/Db* ${BUILD}/Libs/Zend
	@cp -R ${ZEND}/Loader* ${BUILD}/Libs/Zend
	@cp -R ${ZEND}/Session* ${BUILD}/Libs/Zend

# Clear
clear:
	@echo Clearing ...
	@rm -rf ${BUILD}/temp
	@rm -rf ${BUILD}/www
	@rm -rf ${BUILD}/README
	@rm -rf ${BUILD}/Makefile
	@rm -rf ${BUILD}/Config