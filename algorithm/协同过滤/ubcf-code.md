# 协同过滤 UBCF python 伪代码

``` python

#coding=utf-8

u'''
协同过滤；UBCF 伪代码

原理文章: http://blog.csdn.net/yeruby/article/details/44137257

整体思路:

一、用户与用户关系矩阵算法:
    1. 收集用户 -> 物品的集合 train
    2. 根据 train 建立 物品 -> 用户集合的倒排表 item_users
    3. 根据 item_users , 为每个物品下的用户 二二配对生成一个矩阵 B
    4. 把所有的 B 矩阵合并相加等，等到一个矩阵 C , C 矩阵保存用户 -> 用户, 共同出现的次数
    5. 根据 C 矩阵, 通过余弦相似度计算 用户 -> 用户的相似度, 得到最终矩阵 W

二、根据用户关系矩阵进行推荐
    1. 根据当前用户唯一标识符, 如 userId 获取, 在 train[userId] 查找用户的喜欢的物品集合 interacted_items
    2. 使用用户相似度矩阵 W[userId] , 获取与 userId 相似的用户集合 ,  W[userId] 集合中的用户与 userId 相似度排序，从高到低排序，获得 sortSimilarityUserList (按照相似度排序好的用户集合)
    3. 遍历 sortSimilarityUserList , 得到其中每个用户的 item_users[sUserId] 喜欢的物品集合
    4. 把所有 item_users[sUserId] 集合中的物品, 不存在与 train[userId] 中的, 添加到 train[userId] 的物品集合中
    5. 最终 train[userId] 累加完成的数据, 就是推荐结果

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

def UserSimilarty(train):

    u' 构建物品 -> 用户集合数据'
    item_users = dict()

    u'''
    遍历用户喜欢的物品集合
        u: 当前用户
        items: 用户喜欢物品的集合
    '''
    for u, items in train.items():

        u' 遍历当前用户喜欢的所有物品集合'
        for i in items.keys():

            u'''
            i 表示当前用户的物品
            如果当前物品 i 不在(物品与用户的矩阵中)
            '''
            if i not in item_users:

                u' 则创建一个新的集合, 用来保存 物品与用户集合'
                item_users[i] = set()

            u' 保存物品下的用户集合'
            item_users[i].add(u)

    u'''
    item_users 集合数据结构是
      物品i  用户j   
      a      u1, u2, u3
      b      u2, u4, u5
      c      u3, u4, u5
      d      u4 ,u3 ,u1
    '''


    u'''
    基于 item 下的 user 集合 , 构架 user -> uesr 相似度矩阵 C
    '''

    u' 定义字典, 保存共同喜欢物品 i 和物品 j 的次数的大矩阵'
    C = dict()

    u' 定义字典, 保存用户 u 被喜欢过的总次数'
    N = dict()

    u' 遍历物品与用户集合数据'
    for i, users in item_users.items():

        u' 遍历物品下的用户集合'
        for u in users:
            u' 保存用户 u 相同的次数'
            N[u] += 1

            u'''
              基于 item 下的 user 集合, 为每个 item 下的 users 集合生成一个矩阵 B
              ps: user 二二配对, 生成一个矩阵 B, 如果有 N 个物品就有 N 个 B 矩阵
            '''
            for v in users:
                if u == v:
                    continue
                u' 最终把 N 个 B 矩阵, 合并成一个用户 u 和 用户 v 物品数的大矩阵 C'
                #C[u][v] += 1 / math.log(1 + len(users))
                C[u][v] += 1

    u'''
    C 矩阵最终出来的数据结构是
      用户 u  用户 v   相同的次数
      a      b       1
      a      c       2
      a      d       1
      b      e       10
      b      a       1
      c      a       2
    '''

    u' calculate finial similarity matrix W'
    u' 计算用户和用户之间的相似度'
    W = dict()

    u'''
    遍历用户与用户的相似度矩阵
        u : 当前用户
        related_users : 当前用户下的关联用户
    '''
    for u, related_users in C.items():

        u' 遍历当前用户下的关联用户'
        for v, cur in related_users.items():
            u'''
            余弦相似度计算公式:
            N[u] : 表示用户 u 相同的次数
            N[v] : 表示物品 v 相同的次数
            cur : 表示当前用户相同的次数

                   cur
            -------------------          喵~O(∩_∩)O哈哈~
            开平方根(N[u] * N[v])
            '''
            u' 为用户 u 和每个用户 v 计算余弦相似度'
            W[u][v] = cuv / math.sqrt(N[u] * N(v))


        u'''
    W 矩阵最终出来的数据结构是
      用户 u  用户 v   相似度
      a      b       0.78123
      a      c       0.18123
      a      d       0.2123
      b      e       0.2423
      b      a       0.623
    '''
    return W



u'''
对用户进行推荐
    userId : 用户标识符
    train : 用户喜欢的物品集合
    W : 用户与用户相似度矩阵
'''
def userRecommend(userId, train, W):
    rank = dict()

    u' 用户喜欢的物品集合'
    interacted_items = train(userId)

    u'''
    排序当前 W[userId] 矩阵下的所有 v 用户的相似度, 按照从高到低排序, 取出前 K 个数量
    W = {
      'u1': {'v1': 0.123},
      'u1': {'v2': 0.123123},
      'u2': {'v1': 0.434234}
    }
    sortSimilarityUserList = {
       'u1': {'v1': 0.123},
       'u1': {'v2': 0.123123}
    }
    '''
    sortSimilarityUserList = sorted(W[userId].items, key=itemgetter(1), reverse=True)[0:K]

    u'''
    遍历当前用户下的所有相似用户
        v :   W[u] 下的所有用户
        wuv : v 用户与 u 用户的相似度
    '''
    for v, wuv in sortSimilarityUserList:

        u'''
        遍历每个 v 用户喜欢的物品集合
            i :     v 用户下的当前物品
            rvi :   v 用户对 i 物品的兴趣(ps: 可以理解为 用户 -> 物品喜欢的次数)
        '''

        for i, rvi in train[v].items():

            u'''
            用户 v 下的物品 i,  存在于 用户 v 喜欢的物品集合中, 则不推荐
            '''
            if i in interacted_items[v].items():
                # we should filter items user interacted before
                continue

            u' 物品 i += wuv(用户相似度) * rvi(用户对物品的相似度)'
            rank[i] += wuv * rvi

    return rank


```
