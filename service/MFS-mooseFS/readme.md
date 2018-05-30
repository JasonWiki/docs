一、介绍
	MFS文件系统结构: 
	包含4种角色:  
	        管理服务器managing server (master) 
	        元数据日志服务器Metalogger server（Metalogger）
	        数据存储服务器data servers (chunkservers)  
	        客户机挂载使用client computers  (mfsmount)(Users’ computers)mfsmount
	        
	4种角色作用: 
        管理服务器:负责各个数据存储服务器的管理,文件读写调度,文件空间回收以及恢复.多节点拷贝 
        元数据日志服务器: 负责备份master服务器的变化日志文件，文件类型为changelog_ml.*.mfs，以便于在master server出问题的时候接替其进行工作
        数据存储服务器:负责连接管理服务器,听从管理服务器调度,提供存储空间，并为客户提供数据传输. 
        客户端: 通过fuse内核接口挂接远程管理服务器上所管理的数据存储服务器,.看起来共享的文件系统和本地unix文件系统使用一样的效果. 
        
        
        
二、安装使用moosefs
	1、下载
		最新的版本在：http://sourceforge.net/projects/moosefs/files/moosefs/
		
		安装前先下载：http://sourceforge.net/projects/fuse/

		
三、安装
	
	
	1、安装master server、chunkservers 在一台计算集中
		1) 配置 master server 
			第一步：创建用户
				useradd mfs –s /sbin/nolog in
				groupadd mfs		//添加组	
			
			第二步：安装元数据服务 (master server)
				tar -zvx -f mfs-1.6.27-5.tar.gz
				cd mfs-1.6.27-5
				./configure –prefix=/usr/local/mfs –with-default-user=mfs –with-default-group=mfs
				make 
				make install
				
			第三步：开启服务	
				cp /usr/local/mfs/etc/mfs/* /usr/local/mfs/etc							//复制配置文件
				/usr/local/mfs/sbin/mfsmaster start				//启动
				/usr/local/mfs/sbin/mfsmaster -s 					//停止服务器
				
				
		2) 配置chunkservers
			第一步：创建用户
				useradd mfs –s /sbin/nolog in
				groupadd mfs		//添加组
				
			第二步：安装	
				tar zxvf mfs-1.6.27-5.tar.gz
				./configure –prefix=/usr/local/mfs –with-default-user=mfs –with-default-group=mfs
				make 
				make install	
			
			第三步：配置文件和目录	
				a) cp /usr/local/mfs/etc/mfs/* /usr/local/mfs/etc							//复制配置文件
				
				b) vi mfschunkserver.cfg				//主配置文件
					配置：MASTER_HOST = 192.168.1.106					//master server服务器IP
								MASTER_PORT = 9420								//master server服务器端口
				
				c) 先分区，然后格式化，再挂载到一个目录上。
					i) 大文件的处理方式，供演示。
						mfschunks1		//
						mkdir -p /storage/mfschunks		//创建目录
						dd if=/dev/mapper/server-myhome of=/storage/mfschunks/mfschunks1  bs=1M count=1024		//创建镜像文件mfschunks1
						mkfs -t ext3 /storage/mfschunks/mfschunks1		//创建文件系统
						mkdir -p /mnt/mfschunks1			//创建挂接点
						mount -t ext3 -o loop /storage/mfschunks/mfschunks1/mnt/mfschunks1		//挂载
					
						mfschunks2		//
						dd if=/dev/mapper/server-myhome of=/storage/mfschunks/mfschunks2  bs=1M count=1024		//创建镜像文件mfschunks1
						mkfs -t ext3 /storage/mfschunks/mfschunks2		//创建文件系统
						mkdir -p /mnt/mfschunks2			//创建挂接点
						mount -t ext3 -o loop /storage/mfschunks/mfschunks2 /mnt/mfschunks2		//挂载
						
					//设置文件权限	
					chown -R mfs:mfs /mnt/mfschunks1
					chown -R mfs:mfs /mnt/mfschunks2
				
				d) vi mfshdd.cfg					//mfshdd.cfg 是服务器用来分配给MFS 使用的空间,最好是单独的raid 卷，最低要求是一个分区。
						这个配置写入要加入到MFS系统中的磁盘存储的挂载点
						配置：	/mnt/mfschunks1							
									/mnt/mfschunks2

			第四步：启动服务
				/usr/local/mfs/sbin/mfschunkserver start				//启动服务	
				/usr/local/mfs/sbin/mfschunkserver -s 				//停止服务
		
		2) 客户端：
			第一步：安装fuse，依赖于fuse
				tar -zvx -f fuse-2.9.3.tar.gz
				cd fuse-2.9.3
				./configure
				make && make install
				export PKG_CONFIG_PATH=/usr/local/lib/pkgconfig:$PKG_CONFIG_PATH
				source /etc/profile
			
			第二步：创建mfs 用户
				groupadd mfs		//添加组
				useradd mfs –s /sbin/nolog in		//添加用户
		
			第三步：安装mfs
				tar -zvx -f mfs-1.6.27-5.tar.gz	
				cd mfs-1.6.27-5
				./configure --prefix=/usr/local/mfs --with-default-user=mfs --with-default-group=mfs --enable-mfsmount
				make
				make install
				
			第四步：挂接MFS文件系统	
				mkdir /mnt/mfs		//创建挂接点
				modprobe fuse 		//加载fuse 模块到内核：
				
				cp /usr/local/mfs/etc/mfs/* /usr/local/mfs/etc							//复制配置文件
				/usr/local/mfs/bin/mfsmount /mnt/mfs -H 192.168.1.106 		//挂载到master server服务器目录		
				
				* 所有的MFS 都是挂接同一个元数据服务器master 的IP,而不是其他数据存储
				  服务器chunkserver 的IP。
			
				/usr/local/mfs/sbin/ -s 				//停止服务
	
	
	
	2、集群安装：http://yunjuanyunsu.blog.163.com/blog/static/189317242201001401137332/
		修改/etc/hosts 文件，以绑定主机名mfsmaster 与ip 地址192.168.1.1:
		192.168.1.1 mfsmaster
		# MASTER_HOST = mfsmasterq
		MASTER_HOST = 192.168.1.106		//主机名


		
四、常见错误
	1、出现 zlib没有找到 
		zlib-1.2.8.tar.gz
		tar -zvx -f zlib-1.2.8.tar.gz
		./configure
		make 
		make install
		

	2、编译安装客户端时：
		在这个过程中，当执行到–enable-mfsmount 时可能出现”checking for FUSE… no configure: error:
		mfsmount build was forced, but fuse development package is not installed ”这样的错误，
		........
		checking for FUSE... no
		******************************** mfsmount disabled ********************************
		* fuse library is too old or not installed - mfsmount needs version 2.6 or higher *
		***********************************************************************************
		......
		而不能正确安装MFS 客户端程序，这是因为环境变量没有设置，先编辑/etc/profile 在此文件中加
		入如下条目：
		export PKG_CONFIG_PATH=/usr/local/lib/pkgconfig:$PKG_CONFIG_PATH
		然后再利用source 命令/etc/profile 使修改生效：source /etc/profile 即可，也可直接在命令行中直
		接执行：
		export PKG_CONFIG_PATH=/usr/local/lib/pkgconfig:$PKG_CONFIG_PATH		





















































 