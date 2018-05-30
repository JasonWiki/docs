# 图形

``` java
//窗口 流动式布局
import java.awt.Color;
import java.awt.FlowLayout;
import javax.swing.JButton;
import javax.swing.JFrame;

public class MyFlowLayout extends JFrame{

    public MyFlowLayout (String a) {
        super(a);
    }

    public static void main (String[] args) {
        MyFlowLayout frm = new MyFlowLayout("流式布局设置管理器 FlowLayout");


        FlowLayout flow = new FlowLayout(FlowLayout.CENTER,5,10);

        JButton b1 = new JButton("第一个按钮");
        JButton b2 = new JButton("第二个按钮");
        JButton b3 = new JButton("第三个按钮");

        frm.setLayout(flow);

        frm.add(b1);
        frm.add(b2);
        frm.add(b3);

        frm.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frm.setVisible(true);
    }
}


Pattern p=Pattern.compile("^on_t1ime");
        Matcher m=p.matcher(aa);
        System.out.println(m.find());


```
