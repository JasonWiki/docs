# sqoop 参数手册

- [优秀文章](http://www.aboutyun.com/thread-9983-1-1.html)

## 查看方式

``` sh

#查看可以用命令
sqoop help

#查看看某个命令
sqoop import --help

```

## 详细参数

### 1、import

```
Common arguments:
   --connect <jdbc-uri>                         Specify JDBC connect
                                                string
   --connection-manager <class-name>            Specify connection manager
                                                class name
   --connection-param-file <properties-file>    Specify connection
                                                parameters file
   --driver <class-name>                        Manually specify JDBC
                                                driver class to use
   --hadoop-home <hdir>                         Override
                                                $HADOOP_MAPRED_HOME_ARG
   --hadoop-mapred-home <dir>                   Override
                                                $HADOOP_MAPRED_HOME_ARG
   --help                                       Print usage instructions
-P                                              Read password from console
   --password <password>                        Set authentication
                                                password
   --password-alias <password-alias>            Credential provider
                                                password alias
   --password-file <password-file>              Set authentication
                                                password file path
   --relaxed-isolation                          Use read-uncommitted
                                                isolation for imports
   --skip-dist-cache                            Skip copying jars to
                                                distributed cache
   --username <username>                        Set authentication
                                                username
   --verbose                                    Print more information
                                                while working

Import control arguments:
   --append                                                   Imports data
                                                              in append
                                                              mode
   --as-avrodatafile                                          Imports data
                                                              to Avro data
                                                              files
   --as-parquetfile                                           Imports data
                                                              to Parquet
                                                              files
   --as-sequencefile                                          Imports data
                                                              to
                                                              SequenceFile
                                                              s
   --as-textfile                                              Imports data
                                                              as plain
                                                              text
                                                              (default)
   --boundary-query <statement>                               Set boundary
                                                              query for
                                                              retrieving
                                                              max and min
                                                              value of the
                                                              primary key
   --columns <col,col,col...>                                 Columns to
                                                              import from
                                                              table
   --compression-codec <codec>                                Compression
                                                              codec to use
                                                              for import
   --delete-target-dir                                        Imports data
                                                              in delete
                                                              mode
   --direct                                                   Use direct
                                                              import fast
                                                              path
   --direct-split-size <n>                                    Split the
                                                              input stream
                                                              every 'n'
                                                              bytes when
                                                              importing in
                                                              direct mode
-e,--query <statement>                                        Import
                                                              results of
                                                              SQL
                                                              'statement'
   --fetch-size <n>                                           Set number
                                                              'n' of rows
                                                              to fetch
                                                              from the
                                                              database
                                                              when more
                                                              rows are
                                                              needed
   --inline-lob-limit <n>                                     Set the
                                                              maximum size
                                                              for an
                                                              inline LOB
-m,--num-mappers <n>                                          Use 'n' map
                                                              tasks to
                                                              import in
                                                              parallel
   --mapreduce-job-name <name>                                Set name for
                                                              generated
                                                              mapreduce
                                                              job
   --merge-key <column>                                       Key column
                                                              to use to
                                                              join results
   --split-by <column-name>                                   Column of
                                                              the table
                                                              used to
                                                              split work
                                                              units
   --table <table-name>                                       Table to
                                                              read
   --target-dir <dir>                                         HDFS plain
                                                              table
                                                              destination
   --validate                                                 Validate the
                                                              copy using
                                                              the
                                                              configured
                                                              validator
   --validation-failurehandler <validation-failurehandler>    Fully
                                                              qualified
                                                              class name
                                                              for
                                                              ValidationFa
                                                              ilureHandler
   --validation-threshold <validation-threshold>              Fully
                                                              qualified
                                                              class name
                                                              for
                                                              ValidationTh
                                                              reshold
   --validator <validator>                                    Fully
                                                              qualified
                                                              class name
                                                              for the
                                                              Validator
   --warehouse-dir <dir>                                      HDFS parent
                                                              for table
                                                              destination
   --where <where clause>                                     WHERE clause
                                                              to use
                                                              during
                                                              import
-z,--compress                                                 Enable
                                                              compression

Incremental import arguments:
   --check-column <column>        Source column to check for incremental
                                  change
   --incremental <import-type>    Define an incremental import of type
                                  'append' or 'lastmodified'
   --last-value <value>           Last imported value in the incremental
                                  check column

Output line formatting arguments:
   --enclosed-by <char>               Sets a required field enclosing
                                      character
   --escaped-by <char>                Sets the escape character
   --fields-terminated-by <char>      Sets the field separator character
   --lines-terminated-by <char>       Sets the end-of-line character
   --mysql-delimiters                 Uses MySQL's default delimiter set:
                                      fields: ,  lines: \n  escaped-by: \
                                      optionally-enclosed-by: '
   --optionally-enclosed-by <char>    Sets a field enclosing character

Input parsing arguments:
   --input-enclosed-by <char>               Sets a required field encloser
   --input-escaped-by <char>                Sets the input escape
                                            character
   --input-fields-terminated-by <char>      Sets the input field separator
   --input-lines-terminated-by <char>       Sets the input end-of-line
                                            char
   --input-optionally-enclosed-by <char>    Sets a field enclosing
                                            character

Hive arguments:
   --create-hive-table                         Fail if the target hive
                                               table exists
   --hive-database <database-name>             Sets the database name to
                                               use when importing to hive
   --hive-delims-replacement <arg>             Replace Hive record \0x01 and row delimiters (\n\r) from imported string fields with user-defined string

   --hive-drop-import-delims                   Drop Hive record \0x01 and
                                               row delimiters (\n\r) from
                                               imported string fields
   --hive-home <dir>                           Override $HIVE_HOME
   --hive-import                               Import tables into Hive
                                               (Uses Hive's default
                                               delimiters if none are
                                               set.)
   --hive-overwrite                            Overwrite existing data in
                                               the Hive table
   --hive-partition-key <partition-key>        Sets the partition key to
                                               use when importing to hive
   --hive-partition-value <partition-value>    Sets the partition value to
                                               use when importing to hive
   --hive-table <table-name>                   Sets the table name to use
                                               when importing to hive
   --map-column-hive <arg>                     Override mapping for specific column to hive types.
    1、field_name_1=STRING,field_name_2=STINRG 把字段替换成指定的类型



HBase arguments:
   --column-family <family>    Sets the target column family for the
                               import
   --hbase-bulkload            Enables HBase bulk loading
   --hbase-create-table        If specified, create missing HBase tables
   --hbase-row-key <col>       Specifies which input column to use as the
                               row key
   --hbase-table <table>       Import to <table> in HBase

HCatalog arguments:
   --hcatalog-database <arg>                        HCatalog database name
   --hcatalog-home <hdir>                           Override $HCAT_HOME
   --hcatalog-partition-keys <partition-key>        Sets the partition
                                                    keys to use when
                                                    importing to hive
   --hcatalog-partition-values <partition-value>    Sets the partition
                                                    values to use when
                                                    importing to hive
   --hcatalog-table <arg>                           HCatalog table name
   --hive-home <dir>                                Override $HIVE_HOME
   --hive-partition-key <partition-key>             Sets the partition key
                                                    to use when importing
                                                    to hive
   --hive-partition-value <partition-value>         Sets the partition
                                                    value to use when
                                                    importing to hive
   --map-column-hive <arg>                          Override mapping for
                                                    specific column to
                                                    hive types.

HCatalog import specific options:
   --create-hcatalog-table            Create HCatalog before import
   --hcatalog-storage-stanza <arg>    HCatalog storage stanza for table
                                      creation

Accumulo arguments:
   --accumulo-batch-size <size>          Batch size in bytes
   --accumulo-column-family <family>     Sets the target column family for
                                         the import
   --accumulo-create-table               If specified, create missing
                                         Accumulo tables
   --accumulo-instance <instance>        Accumulo instance name.
   --accumulo-max-latency <latency>      Max write latency in milliseconds
   --accumulo-password <password>        Accumulo password.
   --accumulo-row-key <col>              Specifies which input column to
                                         use as the row key
   --accumulo-table <table>              Import to <table> in Accumulo
   --accumulo-user <user>                Accumulo user name.
   --accumulo-visibility <vis>           Visibility token to be applied to
                                         all rows imported
   --accumulo-zookeepers <zookeepers>    Comma-separated list of
                                         zookeepers (host:port)

Code generation arguments:
   --bindir <dir>                        Output directory for compiled
                                         objects
   --class-name <name>                   Sets the generated class name.
                                         This overrides --package-name.
                                         When combined with --jar-file,
                                         sets the input class.


   --input-null-non-string <null-str>    Input null non-string representation (输入的非空表示)
   --input-null-string <null-str>        Input null string representation (表示 mysql 输入的空字符串表示:  )
     1.hive中 为 null 的是以 \N 代替的，所以要导入时要加入下面2个参数
     如: --input-null-non-string '\\N' --input-null-string '\\N'


   --jar-file <file>                     Disable code generation; use
                                         specified jar
   --map-column-java <arg>               Override mapping for specific
                                         columns to java types


   --null-non-string <null-str>          Null non-string representation (非空字符串表示)
   --null-string <null-str>              Null string representation (空字符串表示)
     1.sqoop 会自动把 NULL 转换为 null 处理，但是 hive 中默认是把 \N 来表示 null
     2.使用 --null-string 和 --null-non-string 来处理空值 把 \N 转为 \\N
     如：(--null-non-string '\\N' --null-string '\\N' )

   --outdir <dir>                        Output directory for generated
                                         code
   --package-name <name>                 Put auto-generated classes in
                                         this package

```

###
