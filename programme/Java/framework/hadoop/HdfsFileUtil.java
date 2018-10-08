
import org.apache.hadoop.conf.Configuration;

import java.io.FileOutputStream;
import java.io.IOException;
import java.net.URI;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.FSDataInputStream;
import org.apache.hadoop.fs.FileStatus;
import org.apache.hadoop.fs.FileSystem;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IOUtils;

public class HdfsFileUtil {

    public static void main(String[] args) throws Exception  {
        String uri="hdfs://nameservice1:8020/";   //hdfs 地址
        String remote="hdfs://nameservice1:8020/user/hive/warehouse/temp_db.db/temp_auto_table_1536035209493/uuids.txt"; //hdfs上的路径

        String local="/data/log/abtest/1/uuids/uuids.txt";  //本地路径
        Configuration conf = new Configuration();
        //conf.set("fs.default.name", "hdfs://192.168.1.200:9000");  // “ hdfs://192.168.1.200:9000 ” 是 hdfs master 地址
        //conf.set("hadoop.tmp.dir", "/home/hadoop/tmp");

        //cat(conf ,uri,"hdfs://LL-167:8020/lin/in1/1.txt");
        //download(conf ,uri,remote,local);
        //  delete(conf ,uri,"hdfs://192.168.0.173:8020/Workspace/houlinlin");
        // markDir(conf ,uri,"hdfs://192.168.0.151:8020/Workspace/houlinlin/file/apply/2014/8a8380c7459dd8b90145a1fafb500235");

        //  checkDir(uri,"d:/file");
        // getFile(conf ,uri,"","hdfs://192.168.0.151:8020/Workspace/houlinlin/a.txt");
        // copyFile(conf,uri,"C:/Users/Administrator/Desktop/8a8380c745d05d370145d06719aa3c89.txt","hdfs://192.168.0.151:8020/Workspace/houlinlin/file/apply/2014/8a8380c7459dd8b90145a1fafb500235");
        //
        ls(conf ,uri,"/user/hive/warehouse/temp_db.db/temp_auto_table_1536035209493");

        //download(conf ,uri, remote,local);
    }





    /**
     * 下载 hdfs上的文件
     * @param conf
     * @param uri
     * @param remote
     * @param local
     * @throws IOException
     */
    public static void download(Configuration conf , String uri, String remote, String local) throws IOException {
        Path path = new Path(remote);
        FileSystem fs = FileSystem.get(URI.create(uri), conf);
        fs.copyToLocalFile(path, new Path(local));
        System.out.println("download: from" + remote + " to " + local);
        fs.close();
    }


    /**
     * 上传文件
     * @param conf
     * @param local
     * @param remote
     * @throws IOException
     */
    public static void copyFile(Configuration conf , String uri , String local, String remote) throws IOException {
        FileSystem fs = FileSystem.get(URI.create(uri), conf);
        fs.copyFromLocalFile(new Path(local), new Path(remote));
        System.out.println("copy from: " + local + " to " + remote);
        fs.close();
    }


    /**
     * 查看目录下面的文件
     * @param conf
     * @param uri
     * @param folder
     * @throws IOException
     */
    public static void ls(Configuration conf , String uri , String folder) throws IOException {
        Path path = new Path(folder);
        FileSystem fs = FileSystem.get(URI.create(uri), conf);
        FileStatus[] list = fs.listStatus(path);
        System.out.println("ls: " + folder);
        System.out.println("==========================================================");
        for (FileStatus f : list) {
            System.out.printf("name: %s, folder: %s, size: %d\n", f.getPath(),f.isDirectory() , f.getLen());
        }
        System.out
                .println("==========================================================");
        fs.close();
    }



    /**
     * 获取hdfs上文件流
     * @param conf
     * @param uri
     * @param local
     * @param remote
     * @throws IOException
     */
    public static  void getFileStream(Configuration conf , String uri , String local, String remote) throws IOException{
        FileSystem fs = FileSystem.get(URI.create(uri), conf);
        Path path= new Path(remote);
        FSDataInputStream in = fs.open(path);//获取文件流
        FileOutputStream fos = new FileOutputStream("C:/Users/Administrator/Desktop/b.txt");//输出流
        int ch = 0;
        while((ch=in.read()) != -1){
            fos.write(ch);
        }
        System.out.println("-----");
        in.close();
        fos.close();
    }


    /**
     * 创建文件夹
     * @param conf
     * @param uri
     * @param remoteFile
     * @throws IOException
     */
    public static void markDir(Configuration conf , String uri , String remoteFile ) throws IOException{
        FileSystem fs = FileSystem.get(URI.create(uri), conf);
        Path path = new Path(remoteFile);

        fs.mkdirs(path);
        System.out.println("创建文件夹"+remoteFile);

    }


    /**
     * 查看文件
     * @param conf
     * @param uri
     * @param remoteFile
     * @throws IOException
     */
    public static void cat(Configuration conf , String uri ,String remoteFile) throws IOException {
        Path path = new Path(remoteFile);
        FileSystem fs = FileSystem.get(URI.create(uri), conf);
        FSDataInputStream fsdis = null;
        System.out.println("cat: " + remoteFile);
        try {
            fsdis = fs.open(path);
            IOUtils.copyBytes(fsdis, System.out, 4096, false);
        } finally {
            IOUtils.closeStream(fsdis);
            fs.close();
        }
    }


    /**
     * 删除文件或者文件夹
     * @param conf
     * @param uri
     * @param filePath
     * @throws IOException
     */
    public static void delete(Configuration conf , String uri,String filePath) throws IOException {
        Path path = new Path(filePath);
        FileSystem fs = FileSystem.get(URI.create(uri), conf);
        fs.deleteOnExit(path);
        System.out.println("Delete: " + filePath);
        fs.close();
    }





}
