#说明
CMake是一个跨平台的安装（编译）工具，可以用简单的语句来描述所有平台的安装(编译过程)。
他能够输出各种各样的makefile或者project文件，能测试编译器所支持的C++特性,类似UNIX下的automake。
只是 CMake 的组态档取名为 CmakeLists.txt。
Cmake 并不直接建构出最终的软件，
而是产生标准的建构档（如 Unix 的 Makefile 或 Windows Visual C++ 的 projects/workspaces），
然后再依一般的建构方式使用。
这使得熟悉某个集成开发环境（IDE）的开发者可以用标准的方式建构他的软件，
这种可以使用各平台的原生建构系统的能力是 CMake 和 SCons 等其他类似系统的区别之处。


#用处：mysql 5.6以后使用，此编译器

#下载地址
http://www.cmake.org/download/

#安装编译
tar zxvf cmake-3.1.0.tar.gz
cd cmake-3.1.0
./bootstrap ;
make ;
make install
