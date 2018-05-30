# 协同过滤 IBCF python 伪代码

``` python

#coding=utf-8

u'''
协同过滤；IBCF 伪代码

原理文章: http://blog.csdn.net/yeruby/article/details/44154009

'''

u' 用户喜欢物品的集合'
train = {
    # u1 表示用户, a 表示物品, 2 表示被喜欢的次数
    'u1' : {'a':'2'},
    'u1' : {'b':'2'},
    'u1' : {'b':'4'},
    'u2' : {'b':'2'},
    'u2' : {'c':'2'},
    'u2' : {'d':'2'}
}

def ItemSimilarity(train):
    u' 定义字典, 保存共同喜欢物品 i 和物品 j 的次数的大矩阵'
    C = dict()
    u' 定义字典, 保存物品 i 被喜欢过的总次数'
    N = dict()

    u' 遍历用户喜欢的物品集合'
    for u, items in train.items():

        u' 遍历当前用户喜欢的所有物品集合'
        for i in items:
            u' 保存物品 i 被喜欢的次数'
            N[i] += 1

            u' 为每个用户, 物品二二配对, 生成一个矩阵 B, 如果有 N 个用户就有 N 个 B 矩阵'
            for j in items:
                u' 排除, 物品二二配对, 相同的物品'
                if i == j:
                    continue
                u' 最终把 N 个 B 矩阵, 生成一个同时喜欢物品 i 和 物品 j 用户数的大矩阵 C '
                C[i][j] += 1

    u' 根据 C 矩阵, 生成物品之间的余弦相似度的矩阵 W'
    W = dict()

    u'''
C 矩阵最终出来的数据结构是
  物品i  物品j   被喜欢的次数
  a      b       1
  a      c       2
  a      d       1
  b      e       10
  b      a       1
  c      a       2
    '''

    u' 遍历 C 矩阵所有的物品集合'
    for i, relatedItems in C.items():

        u' 遍历物品 i 下的所有 ij 数据'
        for i, cur_ij in relatedItems.itmes():
            u'''
            余弦相似度计算公式:
            N[i] : 表示物品 i 被喜欢的总次数
            N[j] : 表示物品 j 被喜欢的总次数
            cur_ij : 表示当前物品被喜欢的次数

                   cur_ij
            -------------------          喵~O(∩_∩)O哈哈~
            开平方根(N[i] * N[j])
            '''

            u' 为物品 i 和每个物品 j 计算余弦相似度'
            W[i][j] = cur_ij / math.sqrt(N[i] * N[j])
    return W





def ItemSimilarityRun(train):

    C = dict()
    N = dict()

    u' 遍历用户喜欢的物品集合'
    for u, items in train:
        pass

train = {
    # u1 表示用户, a 表示物品, 2 表示被喜欢的次数
    'u1' : {'a':'2'},
    'u1' : {'b':'2'},
    'u1' : {'b':'4'},
    'u2' : {'b':'2'},
    'u2' : {'c':'2'},
    'u2' : {'d':'2'}
}

rs = ItemSimilarityRun(like)

print rs    

u'''
for u, items in like:
    print u, items

    for i in items:
        print i
'''


```
