# Dùng để build hệ thống

# Đường dẫn Framework
FRA = /WebServer/VHMIS/Framework
# Đường dẫn chứa Hệ thống VHMIS
SYS = /WebServer/VHMIS/VhmisSystem
# Đường dẫn Nơi cài đặt
BUILD = /WebServer/www/VHMIS
# Đường dẫn thư mục web
WBUILD = /WebServer/www/VHMIS_WWW

# Nhung khai bao duoi day danh cho viec thay doi cac pre-config trong file
# Đường dẫn web
URL = /VHMIS_WWW/
# Ten he thong
SNAME = VhmisSystem

# Đường dẫn của các thư viện
DOCT = /WebServer/Doctrine
ZEND = /WebServer/Zend
ICNL = /WebServer/

# Build chính
build: prepare framework systems clear makeconfig
	@echo Build done

# Chuẩn bị trước khi build
prepare:
	@echo Preparing ...
	@mv ${WBUILD}/index.php ${WBUILD}/index.php.bak
	@rm -rf ${BUILD}
	@mkdir ${BUILD}
	@mkdir ${BUILD}/temp

# Build framework
framework:
	@echo Framework is building ...
	@cp -R ${FRA}/* ${BUILD}
	@sed s:WEB_PATH:${URL}: ${BUILD}/www/htaccess.build > ${WBUILD}/.htaccess
	@sed s:BUILD:${BUILD}/: ${BUILD}/www/index.build > ${BUILD}/temp/index.php
	@sed s:SNAME:${SNAME}: ${BUILD}/temp/index.php > ${WBUILD}/index.php
	@sed -e s:WEB_PATH:${URL}: ${BUILD}/www/old.html > ${WBUILD}/old.html

# Build he thong
systems:
	@echo System is building ...
	@mkdir ${BUILD}/System	
	@mkdir ${BUILD}/System/${SNAME}
	@cp -R ${SYS}/* ${BUILD}/System/${SNAME}

# Copy thu vien
library:
	@echo Copying external libs ...
	@mkdir ${BUILD}/Libs/Zend
	@cp -R ${ZEND}/Db* ${BUILD}/Libs/Zend
	@cp -R ${ZEND}/Loader* ${BUILD}/Libs/Zend
	@cp -R ${ZEND}/Session* ${BUILD}/Libs/Zend
	@cp -R ${ZEND}/Exception.php ${BUILD}/Libs/Zend

# Clear
clear:
	@echo Clearing ...
	@rm -rf ${BUILD}/temp
	@rm -rf ${BUILD}/www
	@rm -rf ${BUILD}/README
	@rm -rf ${BUILD}/Makefile
	@rm -rf ${BUILD}/Config
	@rm -rf ${BUILD}/nbproject
	@rm -rf ${BUILD}/System/${SNAME}/nbproject

makeconfig:
	@for i in $(wildcard ${BUILD}/System/${SNAME}/Apps/*/Config/*.example); do \
	mv $${i} `dirname $${i}`/`basename -s .example $${i}`.php; \
	done;
	@for i in $(wildcard ${BUILD}/System/${SNAME}/Config/*.example); do \
	mv $${i} `dirname $${i}`/`basename -s .example $${i}`.php; \
	done;
	@for i in $(wildcard ${BUILD}/System/${SNAME}/Configs/*.example); do \
	mv $${i} `dirname $${i}`/`basename -s .example $${i}`.php; \
	done;