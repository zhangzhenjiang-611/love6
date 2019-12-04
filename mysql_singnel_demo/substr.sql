-- mysql 截取字符串函数

-- 用法：left(str, length)，即：left(被截取字符串， 截取长度)
SELECT LEFT('www.baidu.com',2) str; -- ww 从左边开始截取2个字符长度

-- 用法：right(str, length)，即：righ(被截取字符串， 截取长度)
SELECT right('www.baidu.com',2) str; -- om 从右边开始截取2个字符长度


SELECT LEFT('www.baidu.com',20) str;
SELECT LEFT('www.baidu.com',-1) str;
SELECT LEFT('www.baidu.com',0) str;
SELECT LEFT('www.baidu.com',0.1) str;

-- 从左边第三个字符开始，截取到末尾
SELECT SUBSTR('www.baidu.com',3 ) str;    -- w.baidu.com

-- 从左边第三个字符开始，截取2个字符
SELECT SUBSTR('www.baidu.com',3,2 ) str;   -- w.
SELECT SUBSTR('www.baidu.com',4,2 ) str;   -- .b

-- 从字符串的倒数第2个字符开始读取直至结束
SELECT SUBSTR('www.baidu.com',-2 ) str;  -- om

-- 从字符串的倒数第2个字符开始读取直至结束
SELECT SUBSTR('www.baidu.com',-6,2 ) str;  -- du

-- 截取第二个“.”之前的所有字符
SELECT SUBSTRING_INDEX('www.baidu.com','.',2) str;  -- www.baidu

-- 截取倒数第二个“.”之后的所有字符
SELECT SUBSTRING_INDEX('www.baidu.com','.',-2) str; -- baidu.com

-- 如果关键字不存在，则返回整个字符串
SELECT SUBSTRING_INDEX('www.baidu.com','@',-2) str;  -- www.baidu.com