package com.ajk.dw.scheduler.utils;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.lang.management.ManagementFactory;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.sql.SQLException;
import java.text.ParseException;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.commons.lang.time.DateFormatUtils;
import org.apache.commons.lang.time.DateUtils;
import org.hibernate.Hibernate;
import org.quartz.CronExpression;
import org.quartz.CronTrigger;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.ajk.dw.scheduler.common.SchedulerConfig;
import com.ajk.dw.scheduler.common.SchedulerConfigFactory;
import com.ajk.dw.scheduler.common.SchedulerResources;
import com.ajk.dw.scheduler.common.exception.SchedulerException;
import com.ajk.dw.scheduler.job.DWJob;
import com.google.common.base.Preconditions;

/**
 * 调度工具类
 * @author Jason
 */
public abstract class SchedulerUtils {

    private static final Logger LOG=LoggerFactory.getLogger(SchedulerUtils.class);

    private static final String QUEUE_NAME_CONTACT = "_job_submit_queue_";

    private static final String DATE_FORMAT = "yyyy-MM-dd HH:mm:ss";

    private static final SchedulerConfig DRONE_CONF= SchedulerConfigFactory.getDroneConf();

    private static final String KILL_PROC_GROUP_PY = System.getProperty("schedule.home",
    ".") + File.separator + "schedule_kill_proc_gorup.py";

    private SchedulerUtils() {

    }

    public static String hostname() {
        try {
            return InetAddress.getLocalHost().getHostName();
        } catch (UnknownHostException e) {
            throw new SchedulerException(e);
        }
    }

    public static String queueName(String namespace, String groupId) {
        return namespace + QUEUE_NAME_CONTACT + groupId;
    }

    public static String groupIdFromQueueName(String value, String namespace) {
        Preconditions.checkArgument(value.startsWith(namespace
                + QUEUE_NAME_CONTACT));
        return value
                .substring(namespace.length() + QUEUE_NAME_CONTACT.length());
    }

    public static String channelName(String namespace, DWJob job) {
        return namespace + "_job_channel_" + job.getTaskId() + "_event_"
                + SchedulerUtils.getUniqueId(job);
    }

    public static String queueSetName(String namespace) {
        return namespace + "_job_submit_set";
    }

    public static String schedulerJobFinishEventQueueName(String namespace) {
        return namespace + "_scheduler_finish_queue";
    }

    public static void registerShutdownHook(Runnable runnable) {
        Runtime.getRuntime().addShutdownHook(new Thread(runnable));
    }

    public static void deleteSignalFile(String fileName){
       if(fileName != null && fileName.trim().length()>0){
            try {
                File file = new File(DRONE_CONF.getSignalFilePath()+File.separator+fileName.trim());
                if (file.exists()) {
                    file.delete();
                }
            } catch (Exception e) {
                LOG.info("deleteSignalFile：" + fileName + " 失败",e);
            }
        }
    }

    public static void generateSignalFile(String fileName){
       if(fileName != null && fileName.trim().length()>0){
            File file = new File(DRONE_CONF.getSignalFilePath()+File.separator+fileName.trim());
            if (file.exists()) {
                file.delete();
                LOG.info("信号文件已存在,删除信号文件" + fileName + "成功");
            }
            try {
                file.createNewFile();
                LOG.info("信号文件" + fileName + "创建成功");
            } catch (IOException e) {
                LOG.error("信号文件" + fileName + "创建失败",e);
            }
        }
    }

    //判断是否存在依赖的信号文件
    public static boolean haveSignalFile(String dependent_jobs){
        if(dependent_jobs!=null && dependent_jobs.length()>0){
            return isExistFiles(dependent_jobs);
        }else{
            return true;
        }
    }

    public static boolean isExistFiles(String fileNames){
        String[] fileArray = fileNames.split(",");
        for(String fileName : fileArray){
            File file = new File(DRONE_CONF.getSignalFilePath()+File.separator+fileName.trim());
            if(!file.exists()){
                return false;
            }
        }
        return true;
    }

    public static void close(SchedulerResources resources) {
        try {
            resources.getDataSource().close();
        } catch (SQLException e) {
            LOG.warn("Close scheduler DataSource error",e);
        }
    }

    public static Integer getUniqueId(DWJob job) {
        Integer uuid = job.getExcuteId();
        Integer id;
        if (uuid != null && "".equals(uuid)) {
            id = uuid;
        } else {
            id = job.getJobId();
        }
        return id;
    }

    /*
     * 将对象转化成java.sql.Blob
     * 要求 对象是序列化的
      */
     @SuppressWarnings("deprecation")
    public static java.sql.Blob ObjectToBlob(Object obj){
        if(obj == null) return null;
         try {
            ByteArrayOutputStream out = new ByteArrayOutputStream();
            ObjectOutputStream outputStream = new ObjectOutputStream(out);
            outputStream.writeObject(obj);
            byte [] bytes = out.toByteArray();
            outputStream.close();
            return Hibernate.createBlob(bytes);
        } catch (Exception e) {
            LOG.error("ObjectToBlob"+e.getMessage());
            return null ;
        }

     }

     /*
     * 将java.sql.Blob 转化成 对象 相应对象
     * 要求 对象是序列化的
      */
     public static Object BlobToObject(java.sql.Blob desblob){
        if(desblob == null) return null;
         try {
            Object obj = null;
            ObjectInputStream in = new ObjectInputStream(desblob.getBinaryStream());
            obj = in.readObject();
            in.close();
            return obj;
        } catch (Exception e) {
            LOG.error("BlobToObject"+e.getMessage());
            e.printStackTrace();

        }
        return null;

     }

     /**
      * 由字符串解析Date，如果格式不满足 yyyy-MM-dd，则抛出异常
      * @param day  yyyy-MM-dd形式的日期
      * @return Date类型的日期
      */
     public static Date parseDate(String day)
     {
         try
         {
            return DateUtils.parseDate(day, new String[]{DATE_FORMAT});
         }
         catch (ParseException e)
         {
            e.printStackTrace();
            return null;
         }
     }

     /**
      * 返回date的字符串形式
      * @param date Date类型的日期
      * @return  yyyy-MM-dd形式的日期
      */
     public static String formatDate(Date date)
     {
        if(date == null) return null;
         return DateFormatUtils.format(date, DATE_FORMAT);
     }

     /**
     * 通过crontab表达式获取下一次运行时间
     * @param cronExpression
     * @return
     */
    public static Date getNextFireTime(String cronExpression) {
         if (isEmpty(cronExpression)){
             return null;
         }
         try {
             CronExpression cron = new CronExpression(cronExpression);
             Date nextFireDate = cron.getNextValidTimeAfter(new Date(System.currentTimeMillis()));
             return  nextFireDate;

        } catch (ParseException e) {
            LOG.error(e.getMessage());
            return null;
         }
    }

    /**
     * 通过crontab表达式获取之前运行时间
     * @param cronExpression
     * @return
     */
    public static Date getPreviousFireTime(String cronExpression,String startTime) {
         if (isEmpty(cronExpression) || isEmpty(startTime)){
             return null;
         }
         try {
             CronExpression cron = new CronExpression(cronExpression);
             CronTrigger trigger = new CronTrigger();
             trigger.setCronExpression(cron);
             trigger.setNextFireTime(parseDate(startTime));
             Date previousFireDate = trigger.getPreviousFireTime();
             return  previousFireDate;

        } catch (ParseException e) {
            LOG.error(e.getMessage());
            return null;
         }
    }

    /**
    * Returns true is String is empty
    * @param val
    * @return
    */
    public static boolean isEmpty(String val) {
        if(val == null)
            return true;
        else
            return (val.length()==0);
    }

    /**
    * kill本进程和所有子进程
    * @param pid
    */
    public static void killProcessGroup(String pid) {
        try {
            LOG.info("killProcessGroup:start");
            Process pr = Runtime.getRuntime().exec("python "+KILL_PROC_GROUP_PY + " "+ pid);
            BufferedReader in = new BufferedReader(new InputStreamReader(
                    pr.getInputStream()));
            String line;
            while ((line = in.readLine()) != null) {
                LOG.info("kill process:"+line);
            }
            in.close();
            pr.waitFor();
            int exitCode = pr.exitValue();
            LOG.info("执行killProcessGroup时返回值=" + exitCode);
            LOG.info("killProcessGroup:end");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    /**
    * 获取本程序的进程Id
    * @return
    */
    public static String getProcessId(){
        try {
            String name = ManagementFactory.getRuntimeMXBean().getName();
            LOG.info("getProcessId-name:"+name);
            // get pid
            String pid = name.split("@")[0];
            LOG.info("dw_scheduler_agent's PID:"+pid);
            return pid;
        } catch (Exception e) {
            e.printStackTrace();
            LOG.error("getProcessId fail", e);
            return null;
        }
    }


    /**
    * 运行脚本命令
    * @param command  运行的命令
    * @return Map<String, String> {
    *     code:       结果状态码, 0 为正确, 非 0 为错误
    *     outPut:     执行结果
    * }
    */
    public static Map<String, String> runSyncScript(String command) {
        Map<String,String> result = new HashMap<String,String>();

        try {
            Process process = Runtime.getRuntime().exec(command);
            // shell 进程是 JAVA 进程的子进程，JAVA 作为父进程需要等待子进程执行完毕。
            process.waitFor();
            // 执行状态
            Integer code = process.exitValue();

            // 输出
            BufferedReader is = new BufferedReader(new InputStreamReader(process.getInputStream()));
            BufferedReader es = new BufferedReader(new InputStreamReader(process.getErrorStream()));
            BufferedReader out;
            if (code == 0) out = is; else out = es;

            // 执行结果
            StringBuffer sb = new StringBuffer();
            String line = "";
            while ((line = out.readLine()) != null) {
                //sb.append(line).append("\n");
                sb.append(line);
            }

            result.put("code", Integer.toString(code));
            result.put("outPut", sb.toString());

            is.close();
            es.close();
            process.destroy();
        } catch (Exception e) {
            e.printStackTrace();
        }

        return result;
    }


    /**
     * 执行批量命令
     * @param command
     * @return
     */
    public static Map<String, String> runBatchSyncScript(String command) {
        Map<String,String> result = new HashMap<String,String>();

        try {
            // 解析参数打散
            List<String> commandList = parseCommand(command);
            // 配置执行命令
            ProcessBuilder pb = new ProcessBuilder();
            pb.command(commandList);
            pb.redirectErrorStream(true);
            Process process = pb.start();
            process.waitFor();
            Integer code = process.exitValue();

            // 输出
            BufferedReader is = new BufferedReader(new InputStreamReader(process.getInputStream()));
            BufferedReader es = new BufferedReader(new InputStreamReader(process.getErrorStream()));
            BufferedReader out;
            if (code == 0) out = is; else out = es;

            StringBuffer sb = new StringBuffer();
            while (true) {
                String line = out.readLine();
                if(line == null) {
                    break;
                }
                sb.append(line);
            }

            result.put("code", Integer.toString(code));
            result.put("outPut", sb.toString());

            process.destroy();
            is.close();
            es.close();
        } catch (IOException e) {
            e.printStackTrace();
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        return result;
    }


    /**
     * 解析名称 list 模式
     * @param command
     * @return List<String>
     * 案例 :
     *  java -Dfile.encoding=UTF-8 -jar ~/app/ddw_genera1l_loader/run1/dwd_general_loader.jar 123123 $(date -v -1d +%Y-%m) bbb $(date -v -1d '+%Y-%m-%d') ccc $(date -v -1d +%H:%M:%S) dads $(date -d "-1 hou1rs" "+%Y-%m-%d %H:%M:%S") "123123"
     * 解析后 List<String>:
     * java
        -Dfile.encoding=UTF-8
        -jar
        ~/app/ddw_genera1l_loader/run1/dwd_general_loader.jar
        123123
        $(date -v -1d +%Y-%m)
         bbb
        $(date -v -1d '+%Y-%m-%d')
         ccc
        $(date -v -1d +%H:%M:%S)
         dads
        $(date -d "-1 hou1rs" "+%Y-%m-%d %H:%M:%S")
         "123123"
     */
    public static List<String> parseCommand(String command) {
        // 预处理
        command = command + " ";
        // 解析正则
        String argsPattern = "(\".+?\"|'.+?'|\\$\\(.+?\\)|[^$\"']+?\\s)";
        Matcher mCommandTest = Pattern.compile(argsPattern).matcher(command);
        // 存储
        List<String> commandList = new ArrayList<String>();
        while (mCommandTest.find()) {
            String curArgs = mCommandTest.group();
            // 去除两边空格
            curArgs = trim(curArgs, " ");
            // 祛除两边双引号, 单引号
            curArgs = trim(curArgs, "\'");
            curArgs = trim(curArgs, "\"");
            commandList.add(curArgs);
            // System.out.println(curArgs);
        }
        LOG.info(commandList.toString());
        return commandList;
    }


    /**
     * 去除前后指定字符
     * @param source 目标字符串
     * @param beTrim 要删除的指定字符
     * @return 删除之后的字符串
     * 调用示例：System.out.println(trim(", ashuh  ",","));
     */
    public static String trim(String source, String beTrim) {
        if(source==null){
            return "";
        }
        source = source.trim(); // 循环去掉字符串首的beTrim字符
        if(source.isEmpty()){
            return "";
        }
        String beginChar = source.substring(0, 1);
        if (beginChar.equalsIgnoreCase(beTrim)) {
            source = source.substring(1, source.length());
            beginChar = source.substring(0, 1);
        }
        // 循环去掉字符串尾的beTrim字符
        String endChar = source.substring(source.length() - 1, source.length());
        if (endChar.equalsIgnoreCase(beTrim)) {
            source = source.substring(0, source.length() - 1);
            endChar = source.substring(source.length() - 1, source.length());
        }
        return source;
    }


    /**
     * 解析 Command 中的 shell 参数, 运行后替换到 Command
     * @param command
     * @return command
     * 案例
     */
    public static String parseCommandVar(String command) {
        // 解析命令参数 $(date -v -1d +%Y-%m)
        Matcher mCommand = Pattern.compile("\\$\\(.+?\\)").matcher(command);
        while (mCommand.find()) {
          String curArgs = mCommand.group();
          // 把当前命令的参数 $() 替换掉，拿出实际执行的命令 date -v -1d +%Y-%m
          String curRunArgs = curArgs.replaceAll("[\\$\\(\\)]", "");
          // 实际执行的脚本命令 date -v -1d +%Y-%m 放到系统环境中执行
          Map<String, String> curArgsValue = runBatchSyncScript(curRunArgs);
          // 执行后得到的结果, 替换到 command 中
          command =  mCommand.replaceFirst(curArgsValue.get("outPut"));
          mCommand = Pattern.compile("\\$\\(.+?\\)").matcher(command);
        }
        return command;
    }




    public static void main(String[] args){
        System.out.println(SchedulerUtils.getPreviousFireTime("0 0 14 * * ? *","2015-02-06 14:00:00"));
    }
}
